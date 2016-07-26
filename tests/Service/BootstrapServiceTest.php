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
 * to richarddeloge@gmail.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
namespace Teknoo\Tests\Bundle\StatesBundle\Service;

use Teknoo\Bundle\StatesBundle\Service\BootstrapService;
use Teknoo\Bundle\StatesBundle\Doctrine\LoadClassMetaListener;
use Teknoo\Bundle\StatesBundle\Service\ComposerFinderService;
use Teknoo\States\Loader\LoaderComposer;
use Teknoo\States\Loader\LoaderInterface;

/**
 * Class BootstrapServiceTest.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @covers Teknoo\Bundle\StatesBundle\Service\BootstrapService
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
     * @return \PHPUnit_Framework_MockObject_MockObject|ComposerFinderService
     */
    protected function getComposerFinderServiceMock()
    {
        if (!$this->composerFinderServiceMock instanceof ComposerFinderService) {
            $this->composerFinderServiceMock = $this->createMock('Teknoo\Bundle\StatesBundle\Service\ComposerFinderService');
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
            $this->loadClassMetaListenerMock = $this->createMock('Teknoo\Bundle\StatesBundle\Doctrine\LoadClassMetaListener');
        }

        return $this->loadClassMetaListenerMock;
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
            $splAutoloadRegisterFunction
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testGetLoaderInstanceMissingLoaderClass()
    {
        $this->buildService(function () {})->getLoaderInstance('badClass', 'Teknoo\States\Loader\FinderComposerIntegrated');
    }

    /**
     * @expectedException \Exception
     */
    public function testGetLoaderInstanceBadLoaderClass()
    {
        $this->buildService(function () {})->getLoaderInstance('\DateTime', 'Teknoo\States\Loader\FinderComposerIntegrated');
    }

    /**
     * @expectedException \Exception
     */
    public function testGetLoaderInstanceMissingFinderClass()
    {
        $this->buildService(function () {})->getLoaderInstance('Teknoo\States\Loader\LoaderComposer', 'badClass');
    }

    /**
     * @expectedException \Exception
     */
    public function testGetLoaderInstanceBadFinderClass()
    {
        $this->buildService(function () {})->getLoaderInstance('Teknoo\States\Loader\LoaderComposer', '\DateTime');
    }

    public function testGetLoaderInstanceIntegrated()
    {
        $composerMock = $this->createMock('Composer\Autoload\ClassLoader');

        $this->getComposerFinderServiceMock()
            ->expects($this->once())
            ->method('getComposerInstance')
            ->willReturn($composerMock);

        $loaderClassName = 'Teknoo\States\Loader\LoaderComposer';
        $finderClassName = 'Teknoo\States\Loader\FinderComposerIntegrated';

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
        $composerMock = $this->createMock('Composer\Autoload\ClassLoader');

        $this->getComposerFinderServiceMock()
            ->expects($this->once())
            ->method('getComposerInstance')
            ->willReturn($composerMock);

        $loaderClassName = 'Teknoo\States\Loader\LoaderComposer';
        $finderClassName = 'Teknoo\States\Loader\FinderComposer';

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
