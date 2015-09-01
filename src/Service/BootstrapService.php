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
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @license     http://teknoo.it/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace UniAlteri\Bundle\StatesBundle\Service;

use Doctrine\Common\EventManager;
use UniAlteri\Bundle\StatesBundle\Doctrine\LoadClassMetaListener;
use UniAlteri\States\Loader\FinderComposerIntegrated;
use UniAlteri\States\Loader\FinderInterface;
use UniAlteri\States\Loader\LoaderInterface;

class BootstrapService
{
    /**
     * @var ComposerFinderService
     */
    protected $composerFinderService;

    /**
     * @var \ArrayAccess
     */
    protected $factoryRepositoryInstance;

    /**
     * @var LoadClassMetaListener
     */
    protected $loadClassMetaListener;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * @param ComposerFinderService $composerFinderService
     * @param \ArrayAccess $factoryRepositoryInstance
     * @param LoadClassMetaListener $loadClassMetaListener
     * @param EventManager $eventManager
     */
    public function __construct(
        ComposerFinderService $composerFinderService,
        \ArrayAccess $factoryRepositoryInstance,
        LoadClassMetaListener $loadClassMetaListener,
        EventManager $eventManager
    ) {
        $this->composerFinderService = $composerFinderService;
        $this->factoryRepositoryInstance = $factoryRepositoryInstance;
        $this->loadClassMetaListener = $loadClassMetaListener;
        $this->eventManager = $eventManager;
    }

    /**
     * @param LoaderInterface $loader
     * @return self
     */
    protected function registerLoadClassMetaListener(LoaderInterface $loader)
    {
        $this->loadClassMetaListener->registerLoader($loader);
        $this->eventManager->addEventListener('loadClassMetadata', $this->loadClassMetaListener);

        return $this;
    }

    /**
     * @param string $loaderClassName
     * @param string $finderClassName
     * @return LoaderInterface
     * @throws \Exception
     */
    protected function buildLoaderInstance(\string $loaderClassName, \string $finderClassName)
    {
        if (!class_exists($loaderClassName)) {
            throw new \Exception('Error, '.$loaderClassName.' does not exist');
        }

        if (!$loaderClassName instanceof LoaderInterface) {
            throw new \Exception('Error, '.$loaderClassName.' does not implement the Loader Interface');
        }

        if (!class_exists($finderClassName)) {
            throw new \Exception('Error, '.$finderClassName.' does not exist');
        }

        if (!$finderClassName instanceof FinderInterface) {
            throw new \Exception('Error, '.$finderClassName.' does not implement the Loader Interface');
        }

        $composerInstance = $this->composerFinderService->getComposerInstance();

        /**
         * Service to generate a finder for Stated class factory
         * @param string $statedClassName
         * @param string $path
         * @return FinderComposerIntegrated
         */
        $finderFactory = function (\string $statedClassName, \string $path) use ($composerInstance, $finderClassName) {
            return new $finderClassName($statedClassName, $path, $composerInstance);
        };

        $factoryRepository = new \ArrayObject();
        $loader = new $loaderClassName($composerInstance, $finderFactory, $factoryRepository);

        //Register autoload function in the spl autoloader stack
        spl_autoload_register(
            array($loader, 'loadClass'),
            true,
            true
        );

        return $loader;
    }

    /**
     * @param string $loaderClassName
     * @param string $finderClassName
     * @return LoaderInterface
     * @throws \Exception
     */
    public function getLoaderInstance(\string $loaderClassName, \string $finderClassName)
    {
        $loader = $this->buildLoaderInstance($loaderClassName, $finderClassName);
        $this->registerLoadClassMetaListener($loader);

        return $loader;
    }
}