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
use Symfony\Component\Debug\DebugClassLoader;
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
                if (is_array($autoloadCallback) && isset($autoloadCallback[0])) {
                    if ($autoloadCallback[0] instanceof ClassLoader) {
                        return $autoloadCallback[0];
                    }

                    if ($autoloadCallback[0] instanceof DebugClassLoader) {
                        $classLoader = $autoloadCallback[0]->getClassLoader();
                        if (is_array($classLoader) && $classLoader[0] instanceof ClassLoader) {
                            return $classLoader[0];
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

        /**
         * Service to generate a finder for Stated class factory
         * @param string $statedClassName
         * @param string $path
         * @return Loader\FinderComposerIntegrated
         * @throws Exception\UnavailableFactory if the local factory is not available
         */
        $finderFactory = function (\string $statedClassName, \string $path) use ($composerInstance) {
            return new Loader\FinderComposerIntegrated($statedClassName, $path, $composerInstance);
        };

        $factoryRepository = new \ArrayObject();
        $loader = new Loader\LoaderComposer($composerInstance, $finderFactory, $factoryRepository);

        //Register autoload function in the spl autoloader stack
        spl_autoload_register(
            array($loader, 'loadClass'),
            true,
            true
        );

        //Register autoload function in the spl autoloader stack
        spl_autoload_register(
            array($loader, 'loadClass'),
            true,
            true
        );

        if ($this->container instanceof Container) {
            $this->container->set('unialteri.states.loader', $loader);
        }
    }
}
