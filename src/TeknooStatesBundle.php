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

namespace Teknoo\Bundle\StatesBundle;

use Composer\Autoload\ClassLoader;
use Symfony\Component\Debug\DebugClassLoader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Teknoo\Bundle\StatesBundle\Factory\StartupFactory;
use Teknoo\States\DI;
use Teknoo\States\Loader;
use Teknoo\States\Exception;
use Teknoo\States\Factory;
use Teknoo\States\States;
use TYPO3\ClassAliasLoader\ClassAliasLoader;

/**
 * TeknooStatesBundle
 * Bundle to use easily the library Teknoo States with Symfony 2 + Doctrine.
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
class TeknooStatesBundle extends Bundle
{
    /**
     * To retrieve the composer loader instance from the __autoload stack with PHP's spl function.
     *
     * @return ClassLoader
     */
    protected function getComposerInstance()
    {
        $autoloadCallbackList = \spl_autoload_functions();

        if (!empty($autoloadCallbackList)) {
            foreach ($autoloadCallbackList as $autoloadCallback) {
                if (is_array($autoloadCallback) && isset($autoloadCallback[0])) {
                    if ($autoloadCallback[0] instanceof ClassLoader) {
                        return $autoloadCallback[0];
                    }

                    if ($autoloadCallback[0] instanceof ClassAliasLoader) {
                        $reflectionObject = new \ReflectionObject($autoloadCallback[0]);
                        $property = $reflectionObject->getProperty('composerClassLoader');
                        $property->setAccessible(true);

                        return $property->getValue($autoloadCallback[0]);
                    }

                    if ($autoloadCallback[0] instanceof DebugClassLoader) {
                        $classLoader = $autoloadCallback[0]->getClassLoader();
                        if (is_array($classLoader) && $classLoader[0] instanceof ClassLoader) {
                            return $classLoader[0];
                        }

                        if (is_array($classLoader) && $classLoader[0] instanceof ClassAliasLoader) {
                            $reflectionObject = new \ReflectionObject($classLoader[0]);
                            $property = $reflectionObject->getProperty('composerClassLoader');
                            $property->setAccessible(true);

                            return $property->getValue($classLoader[0]);
                        }
                    }
                }
            }
        }

        throw new \RuntimeException('Error, the Composer loader component is not available');
    }

    /**
     * To initialize the states library with Symfony.
     *
     * @throws DI\Exception\ClassNotFound
     */
    public function boot()
    {
        parent::boot();

        //Get the composer instance from PHP's configuration
        $composerInstance = $this->getComposerInstance();

        //Initial DI Container
        $diContainer = new DI\Container();

        //Create the factory registry in the container for States library >= 1.2 or >= 2.0
        $factoryReflection = new \ReflectionClass('Teknoo\States\Factory\FactoryInterface');
        if ($factoryReflection->hasConstant('DI_FACTORY_REPOSITORY')) {
            //Initialize the Factory Repository
            $diContainer->registerInstance(Factory\FactoryInterface::DI_FACTORY_REPOSITORY, new DI\Container());
        }

        /*
         * Service to generate a finder for Stated class factory
         * @param DI\ContainerInterface $container
         * @return Loader\FinderComposerIntegrated
         * @throws Exception\UnavailableFactory if the local factory is not available
         */
        $finderService = function (DI\ContainerInterface $container) use ($composerInstance) {
            if (false === $container->testEntry(Factory\FactoryInterface::DI_FACTORY_NAME)) {
                throw new Exception\UnavailableFactory('Error, the factory is not available into container');
            }

            $factory = $container->get(Factory\FactoryInterface::DI_FACTORY_NAME);

            return new Loader\FinderComposerIntegrated($factory->getStatedClassName(), $factory->getPath(), $composerInstance);
        };

        //Register finder generator
        $diContainer->registerService(Loader\FinderInterface::DI_FINDER_SERVICE, $finderService);

        //Register injection closure generator only for States library 1.x versions
        if (interface_exists('Teknoo\States\DI\InjectionClosureInterface')) {
            //Register injection closure generator
            $injectionClosureService = function () {
                if (!defined('DISABLE_PHP_FLOC_OPERATOR') && '5.6' <= PHP_VERSION) {
                    return new DI\InjectionClosurePHP56();
                } else {
                    return new DI\InjectionClosure();
                }
            };

            $diContainer->registerService(States\StateInterface::INJECTION_CLOSURE_SERVICE_IDENTIFIER, $injectionClosureService);
        }

        //Stated class loader, initialize
        $loader = new Loader\LoaderComposer($composerInstance);
        $loader->setDIContainer($diContainer);

        //Register loader into container
        $diContainer->registerInstance(Loader\LoaderInterface::DI_LOADER_INSTANCE, $loader);

        //Register autoload function in the spl autoloader stack
        spl_autoload_register(
            array($loader, 'loadClass'),
            true,
            true
        );

        if ($this->container instanceof Container) {
            $this->container->set('teknoo.states.loader', $loader);
        }

        StartupFactory::registerLoader($loader);
    }
}
