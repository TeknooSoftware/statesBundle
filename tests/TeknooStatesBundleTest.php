<?php

/**
 * StatesBundle.
 *
 * LICENSE
 *
 * This source file is subject to the MIT license and the version 3 of the GPL3
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Tests\Bundle\StatesBundle;

use Composer\Autoload\ClassLoader;
use Symfony\Component\DependencyInjection\Container;
use Teknoo\Bundle\StatesBundle\TeknooStatesBundle;
use Teknoo\States;
use Teknoo\States\Loader;
use Teknoo\States\Factory;
use Teknoo\States\Exception;
use Teknoo\Tests\Support;
use TYPO3\ClassAliasLoader\ClassAliasLoader;

class TeknooStatesBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    protected $_container = null;

    protected function setUp()
    {
        if (!$this->_container instanceof Container) {
            $this->_container = new Container();
        }
        parent::setUp();
    }

    protected function tearDown()
    {
        if ($this->_container->has('teknoo.states.loader')) {
            spl_autoload_unregister(
                array($this->_container->get('teknoo.states.loader'), 'loadClass')
            );

            $this->_container->set('teknoo.states.loader', null);
        }

        parent::tearDown();
    }

    public function testLoaderInitialisation()
    {
        //Initialize container
        $bundle = new TeknooStatesBundle();
        $bundle->setContainer($this->_container);

        $bundle->boot();

        $this->assertTrue($this->_container->has('teknoo.states.loader'));
        $loader = $this->_container->get('teknoo.states.loader');

        //Check if the loader implements the good interface
        $this->assertInstanceOf('\\Teknoo\\States\\Loader\\LoaderInterface', $loader);

        //Check if the loader is initialized with a di container
        $container = $loader->getDIContainer();
        $this->assertInstanceOf('\\Teknoo\\States\\DI\\ContainerInterface', $container);

        //Check if required services are present into the di container
        $this->assertTrue($container->testEntry(Loader\FinderInterface::DI_FINDER_SERVICE));
        $this->assertTrue($container->testEntry(States\States\StateInterface::INJECTION_CLOSURE_SERVICE_IDENTIFIER));

        $fail = false;
        try {
            $container->get(Loader\FinderInterface::DI_FINDER_SERVICE);
        } catch (Exception\UnavailableFactory $e) {
            $fail = true;
        } catch (\Exception $e) {
        }

        $this->assertTrue($fail, 'Error, the service to create finder must throw exception if the DI Container for the class has not registered factory object');

        //Test behavior of the service to create finder for a stated class
        $container->registerInstance(Factory\FactoryInterface::DI_FACTORY_NAME, new Support\MockFactory());
        $finder = $container->get(Loader\FinderInterface::DI_FINDER_SERVICE);
        $this->assertInstanceOf('\\Teknoo\\States\\Loader\\FinderInterface', $finder);

        //Test behavior of the service to create injection closure
        $injectionClosure = $container->get(States\States\StateInterface::INJECTION_CLOSURE_SERVICE_IDENTIFIER);
        $this->assertInstanceOf('\\Teknoo\\States\\DI\\InjectionClosureInterface', $injectionClosure);
    }

    public function testLoaderInitialisationWithoutFlocOperator()
    {
        if ('5.6' > PHP_VERSION) {
            $this->markTestSkipped('Version of PHP is not supported for this injection closure');

            return;
        }

        defined('DISABLE_PHP_FLOC_OPERATOR') || define('DISABLE_PHP_FLOC_OPERATOR', true);

        //Initialize container
        $bundle = new TeknooStatesBundle();
        $bundle->setContainer($this->_container);

        $bundle->boot();

        $this->assertTrue($this->_container->has('teknoo.states.loader'));
        $loader = $this->_container->get('teknoo.states.loader');

        //Check if the loader implements the good interface
        $this->assertInstanceOf('\\Teknoo\\States\\Loader\\LoaderInterface', $loader);

        //Check if the loader is initialized with a di container
        $container = $loader->getDIContainer();
        $this->assertInstanceOf('\\Teknoo\\States\\DI\\ContainerInterface', $container);

        //Check if required services are present into the di container
        $this->assertTrue($container->testEntry(Loader\FinderInterface::DI_FINDER_SERVICE));
        $this->assertTrue($container->testEntry(States\States\StateInterface::INJECTION_CLOSURE_SERVICE_IDENTIFIER));

        $fail = false;
        try {
            $container->get(Loader\FinderInterface::DI_FINDER_SERVICE);
        } catch (Exception\UnavailableFactory $e) {
            $fail = true;
        } catch (\Exception $e) {
        }

        $this->assertTrue($fail, 'Error, the service to create finder must throw exception if the DI Container for the class has not registered factory object');

        //Test behavior of the service to create finder for a stated class
        $container->registerInstance(Factory\FactoryInterface::DI_FACTORY_NAME, new Support\MockFactory());
        $finder = $container->get(Loader\FinderInterface::DI_FINDER_SERVICE);
        $this->assertInstanceOf('\\Teknoo\\States\\Loader\\FinderInterface', $finder);

        //Test behavior of the service to create injection closure
        $injectionClosure = $container->get(States\States\StateInterface::INJECTION_CLOSURE_SERVICE_IDENTIFIER);
        $this->assertInstanceOf('\\Teknoo\\States\\DI\\InjectionClosureInterface', $injectionClosure);
    }

    public function testLoaderBehaviorIfComposerIsNotAvailable()
    {
        //Fake autoload method to simulate an not empty autoload stack
        spl_autoload_register(function ($className) {return false;});

        //Initialize container
        $bundle = new TeknooStatesBundle();
        $bundle->setContainer($this->_container);

        //Remove autoloader
        $autoloadCallbackList = \spl_autoload_functions();

        $composerAutoloaderCallback = null;
        if (!empty($autoloadCallbackList)) {
            foreach ($autoloadCallbackList as $autoloadCallback) {
                if (is_array($autoloadCallback) && isset($autoloadCallback[0])
                    && ($autoloadCallback[0] instanceof ClassLoader
                        || $autoloadCallback[0] instanceof ClassAliasLoader)
                ) {
                    $composerAutoloaderCallback = $autoloadCallback;
                    spl_autoload_unregister($autoloadCallback);
                }
            }
        }

        try {
            $bundle->boot();
        } catch (\RuntimeException $e) {
            if (is_callable($composerAutoloaderCallback)) {
                spl_autoload_register($composerAutoloaderCallback, true, true);
            }

            return;
        } catch (\Exception $e) { /* ... */
        }

        $this->fail('Error, the boot method must throw an exception when the Composer Loader is not available');

        if (is_callable($composerAutoloaderCallback)) {
            spl_autoload_register($composerAutoloaderCallback, true, true);
        }
    }

    public function testLoaderBehaviorIfComposerIsNotAvailableEmpty()
    {
        //Initialize container
        $bundle = new TeknooStatesBundle();
        $bundle->setContainer($this->_container);

        //Remove autoloader
        $autoloadCallbackList = \spl_autoload_functions();

        $composerAutoloaderCallback = null;
        if (!empty($autoloadCallbackList)) {
            foreach ($autoloadCallbackList as $autoloadCallback) {
                if (is_array($autoloadCallback) && isset($autoloadCallback[0])
                    && ($autoloadCallback[0] instanceof ClassLoader
                        || $autoloadCallback[0] instanceof ClassAliasLoader)
                ) {
                    $composerAutoloaderCallback = $autoloadCallback;
                    spl_autoload_unregister($autoloadCallback);
                }
            }
        }

        try {
            $bundle->boot();
        } catch (\RuntimeException $e) {
            if (is_callable($composerAutoloaderCallback)) {
                spl_autoload_register($composerAutoloaderCallback, true, true);
            }

            return;
        } catch (\Exception $e) { /* ... */
        }

        $this->fail('Error, the boot method must throw an exception when the Composer Loader is not available');

        if (is_callable($composerAutoloaderCallback)) {
            spl_autoload_register($composerAutoloaderCallback, true, true);
        }
    }
}
