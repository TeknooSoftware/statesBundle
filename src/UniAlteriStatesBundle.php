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
 *
 * @version     1.0.3
 */

namespace UniAlteri\Bundle\StatesBundle;

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
     * To initialize the states library with symfony.
     *
     * @throws DI\Exception\ClassNotFound
     */
    public function boot()
    {
        parent::boot();

        //Initial DI Container
        $diContainer = new DI\Container();

        //Create the factory registry in the container for States library >= 1.2 or >= 2.0
        $factoryReflection = new \ReflectionClass('UniAlteri\States\Factory\FactoryInterface');
        if ($factoryReflection->hasConstant('DI_FACTORY_REPOSITORY')) {
            //Initialize the Factory Repository
            $diContainer->registerInstance(Factory\FactoryInterface::DI_FACTORY_REPOSITORY, new DI\Container());
        }

        //Service to generate a finder for Stated class factory
        /*
         * @param DI\ContainerInterface $container
         * @return Loader\FinderIntegrated
         * @throws Exception\UnavailableFactory if the local factory is not available
         */
        $finderService = function (DI\ContainerInterface $container) {
            if (false === $container->testEntry(Factory\FactoryInterface::DI_FACTORY_NAME)) {
                throw new Exception\UnavailableFactory('Error, the factory is not available into container');
            }

            $factory = $container->get(Factory\FactoryInterface::DI_FACTORY_NAME);

            return new Loader\FinderIntegrated($factory->getStatedClassName(), $factory->getPath());
        };

        //Register finder generator
        $diContainer->registerService(Loader\FinderInterface::DI_FINDER_SERVICE, $finderService);

        //Register injection closure generator
        $injectionClosureService = function () {
            return new DI\InjectionClosure();
        };

        $diContainer->registerService(States\StateInterface::INJECTION_CLOSURE_SERVICE_IDENTIFIER, $injectionClosureService);

        //Stated class loader, initialize
        $includePathManager = new Loader\IncludePathManager();
        $loader = new Loader\LoaderStandard($includePathManager);
        $loader->setDIContainer($diContainer);

        //Register loader into container
        $diContainer->registerInstance(Loader\LoaderInterface::DI_LOADER_INSTANCE, $loader);

        //Register autoload function in the spl autoloader stack
        spl_autoload_register(
            array($loader, 'loadClass'),
            true,
            true //Prepend to the autoloader stack
        );

        if ($this->container instanceof Container) {
            $this->container->set('unialteri.states.loader', $loader);
        }

        StartupFactory::registerLoader($loader);
    }
}
