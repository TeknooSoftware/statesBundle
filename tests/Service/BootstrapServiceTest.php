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

namespace UniAlteri\Tests\Bundle\StatesBundle\Service;

use UniAlteri\Bundle\StatesBundle\Service\BootstrapService;
use Doctrine\Common\EventManager;
use UniAlteri\Bundle\StatesBundle\Doctrine\LoadClassMetaListener;
use UniAlteri\Bundle\StatesBundle\Service\ComposerFinderService;
use UniAlteri\States\Loader\LoaderComposer;
use UniAlteri\States\Loader\LoaderInterface;

/**
 * Class BootstrapServiceTest.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @license     http://teknoo.it/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ComposerFinderService
     */
    protected function getComposerFinderServiceMock()
    {
        if (!$this->composerFinderServiceMock instanceof ComposerFinderService) {
            $this->composerFinderServiceMock = $this->getMock('UniAlteri\Bundle\StatesBundle\Service\ComposerFinderService', [], [], '', false);
        }

        return $this->composerFinderServiceMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\ArrayAccess
     */
    protected function getFactoryRepositoryInstanceMock()
    {
        if (!$this->factoryRepositoryInstanceMock instanceof \ArrayAccess) {
            $this->factoryRepositoryInstanceMock = new \ArrayObject();
        }

        return $this->factoryRepositoryInstanceMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|LoadClassMetaListener
     */
    protected function getLoadClassMetaListenerMock()
    {
        if (!$this->loadClassMetaListenerMock instanceof LoadClassMetaListener) {
            $this->loadClassMetaListenerMock = $this->getMock('UniAlteri\Bundle\StatesBundle\Doctrine\LoadClassMetaListener', [], [], '', false);
        }

        return $this->loadClassMetaListenerMock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EventManager
     */
    protected function getEventManagerMock()
    {
        if (!$this->eventManagerMock instanceof EventManager) {
            $this->eventManagerMock = $this->getMock('Doctrine\Common\EventManager', [], [], '', false);
        }

        return $this->eventManagerMock;
    }

    /**
     * @param callable $splAutoloadRegisterFunction
     *
     * @return BootstrapService
     */
    public function buildService(callable $splAutoloadRegisterFunction)
    {
        return new BootstrapService(
            $this->getComposerFinderServiceMock(),
            $this->getfactoryRepositoryInstanceMock(),
            $this->getLoadClassMetaListenerMock(),
            $this->getEventManagerMock(),
            $splAutoloadRegisterFunction
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testGetLoaderInstanceMissingLoaderClass()
    {
        $this->buildService(function () {})->getLoaderInstance('badClass', 'UniAlteri\States\Loader\FinderComposerIntegrated');
    }

    /**
     * @expectedException \Exception
     */
    public function testGetLoaderInstanceBadLoaderClass()
    {
        $this->buildService(function () {})->getLoaderInstance('\DateTime', 'UniAlteri\States\Loader\FinderComposerIntegrated');
    }

    /**
     * @expectedException \Exception
     */
    public function testGetLoaderInstanceMissingFinderClass()
    {
        $this->buildService(function () {})->getLoaderInstance('UniAlteri\States\Loader\LoaderComposer', 'badClass');
    }

    /**
     * @expectedException \Exception
     */
    public function testGetLoaderInstanceBadFinderClass()
    {
        $this->buildService(function () {})->getLoaderInstance('UniAlteri\States\Loader\LoaderComposer', '\DateTime');
    }

    public function testGetLoaderInstanceIntegrated()
    {
        $composerMock = $this->getMock('Composer\Autoload\ClassLoader', [], [], '', false);

        $this->getComposerFinderServiceMock()
            ->expects($this->once())
            ->method('getComposerInstance')
            ->willReturn($composerMock);

        $loaderClassName = 'UniAlteri\States\Loader\LoaderComposer';
        $finderClassName = 'UniAlteri\States\Loader\FinderComposerIntegrated';

        $registerMock = function ($function, $throw, $prepend) use ($loaderClassName) {
            $this->assertTrue(is_callable($function) && is_array($function));
            $this->assertInstanceOf($loaderClassName, $function[0]);
            $this->assertEquals('loadClass', $function[1]);
            $this->assertTrue($throw);
            $this->assertTrue($prepend);
        };

        $this->getLoadClassMetaListenerMock()
            ->expects($this->once())
            ->method('registerLoader')
            ->with($this->callback(function ($arg) {return $arg instanceof LoaderComposer;}));

        $this->getEventManagerMock()
            ->expects($this->once())
            ->method('addEventListener')
            ->with('loadClassMetadata', $this->getLoadClassMetaListenerMock());

        $loader = $this->buildService($registerMock)->getLoaderInstance($loaderClassName, $finderClassName);
        $this->assertInstanceOf(
            $loaderClassName,
            $loader
        );

        if ($loader instanceof LoaderInterface) {
            $this->assertTrue(is_callable($loader->getFinderFactory()));

            $finderFactory = $loader->getFinderFactory();
            $this->assertInstanceOf(
                $finderClassName,
                $finderFactory('foo', 'bar')
            );
        }
    }

    public function testGetLoaderInstanceStandard()
    {
        $composerMock = $this->getMock('Composer\Autoload\ClassLoader', [], [], '', false);

        $this->getComposerFinderServiceMock()
            ->expects($this->once())
            ->method('getComposerInstance')
            ->willReturn($composerMock);

        $loaderClassName = 'UniAlteri\States\Loader\LoaderComposer';
        $finderClassName = 'UniAlteri\States\Loader\FinderComposer';

        $registerMock = function ($function, $throw, $prepend) use ($loaderClassName) {
            $this->assertTrue(is_callable($function) && is_array($function));
            $this->assertInstanceOf($loaderClassName, $function[0]);
            $this->assertEquals('loadClass', $function[1]);
            $this->assertTrue($throw);
            $this->assertTrue($prepend);
        };

        $this->getLoadClassMetaListenerMock()
            ->expects($this->once())
            ->method('registerLoader')
            ->with($this->callback(function ($arg) {return $arg instanceof LoaderComposer;}));

        $this->getEventManagerMock()
            ->expects($this->once())
            ->method('addEventListener')
            ->with('loadClassMetadata', $this->getLoadClassMetaListenerMock());

        $loader = $this->buildService($registerMock)->getLoaderInstance($loaderClassName, $finderClassName);
        $this->assertInstanceOf(
            $loaderClassName,
            $loader
        );

        if ($loader instanceof LoaderInterface) {
            $this->assertTrue(is_callable($loader->getFinderFactory()));

            $finderFactory = $loader->getFinderFactory();
            $this->assertInstanceOf(
                $finderClassName,
                $finderFactory('foo', 'bar')
            );
        }
    }
}
