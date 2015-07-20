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
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace UniAlteri\Bundle\StatesBundle;

use Composer\Autoload\ClassLoader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use UniAlteri\Bundle\StatesBundle\Factory\StartupFactory;
use UniAlteri\States\DI;
use UniAlteri\States\Loader;
use UniAlteri\States\Exception;
use UniAlteri\States\Factory;
use UniAlteri\States\States;

/**
 * UniAlteriStatesBundle
 * Bundle to use easily the library UniAlteri States with Symfony 2 + Doctrine.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class UniAlteriStatesBundle extends Bundle
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
                if (is_array($autoloadCallback) && isset($autoloadCallback[0])
                    && $autoloadCallback[0] instanceof ClassLoader) {
                    return $autoloadCallback[0];
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
        $factoryReflection = new \ReflectionClass('UniAlteri\States\Factory\FactoryInterface');
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
        if (interface_exists('UniAlteri\States\DI\InjectionClosureInterface')) {
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
            $this->container->set('unialteri.states.loader', $loader);
        }

        StartupFactory::registerLoader($loader);
    }
}
