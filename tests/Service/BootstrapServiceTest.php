<?php

namespace UniAlteri\Tests\Bundle\StatesBundle\Service;

use UniAlteri\Bundle\StatesBundle\Service\BootstrapService;
use Doctrine\Common\EventManager;
use UniAlteri\Bundle\StatesBundle\Doctrine\LoadClassMetaListener;
use UniAlteri\Bundle\StatesBundle\Service\ComposerFinderService;

class BootstrapServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ComposerFinderService
     */
    protected $composerFinderServiceMock;

    /**
     * @var \ArrayAccess
     */
    protected $factoryRepositoryInstanceMock;

    /**
     * @var LoadClassMetaListener
     */
    protected $loadClassMetaListenerMock;

    /**
     * @var EventManager
     */
    protected $eventManagerMock;

    protected function getComposerFinderServiceMock()
    {

    }

    protected function getfactoryRepositoryInstanceMock()
    {

    }

    protected function getLoadClassMetaListenerMock()
    {

    }

    protected function getEventManagerMock()
    {

    }

    /**
     * @return BootstrapService
     */
    public function buildService()
    {
        return new BootstrapService(
            $this->getComposerFinderServiceMock(),
            $this->getfactoryRepositoryInstanceMock(),
            $this->getLoadClassMetaListenerMock(),
            $this->getEventManagerMock()
        );
    }
}