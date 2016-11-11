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
namespace Teknoo\Tests\UniversalPackage\States;

use Gaufrette\Adapter\Local;
use Gaufrette\Filesystem;
use Interop\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Yaml\Parser;
use Teknoo\States\LifeCycle\Event\EventDispatcherBridgeInterface;
use Teknoo\States\LifeCycle\Observing\ObservedFactory;
use Teknoo\States\LifeCycle\Observing\ObservedFactoryInterface;
use Teknoo\States\LifeCycle\Observing\Observer;
use Teknoo\States\LifeCycle\Observing\ObserverInterface;
use Teknoo\States\LifeCycle\Scenario\Manager;
use Teknoo\States\LifeCycle\Scenario\ManagerInterface;
use Teknoo\States\LifeCycle\Tokenization\Tokenizer;
use Teknoo\States\LifeCycle\Tokenization\TokenizerInterface;
use Teknoo\UniversalPackage\States\Event\EventDispatcherBridge;
use Teknoo\UniversalPackage\States\StatesServiceProvider;

/**
 * Class DefinitionProviderTest
 * @covers \Teknoo\UniversalPackage\States\StatesServiceProvider
 */
class StatesServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return StatesServiceProvider
     */
    public function buildProvider(): StatesServiceProvider
    {
        return new StatesServiceProvider();
    }

    public function testGetDefinitions()
    {
        $definitions = $this->buildProvider()->getServices();
        self::assertTrue(isset($definitions[TokenizerInterface::class]));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.tokenizer']));
        self::assertTrue(isset($definitions[EventDispatcherBridgeInterface::class]));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.bridge.event_dispatcher']));
        self::assertTrue(isset($definitions[ManagerInterface::class]));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.manager']));
        self::assertTrue(isset($definitions[ObservedFactoryInterface::class]));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.observed.factory']));
        self::assertTrue(isset($definitions[ObserverInterface::class]));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.observer']));
        self::assertTrue(isset($definitions['teknoo.vendor.yaml.parser']));
        self::assertTrue(isset($definitions['teknoo.vendor.service.gaufrette.adapter']));
        self::assertTrue(isset($definitions['teknoo.vendor.service.gaufrette.filesystem']));
    }

    public function testCreateStatesTokenizer()
    {
        self::assertInstanceOf(
            Tokenizer::class,
            StatesServiceProvider::createStatesTokenizer()
        );
    }

    public function testCreateEventDispatcherBridge()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::any())
            ->method('get')
            ->withConsecutive(['event_dispatcher'])
            ->willReturnOnConsecutiveCalls($this->createMock(EventDispatcherInterface::class));

        self::assertInstanceOf(
            EventDispatcherBridge::class,
            StatesServiceProvider::createEventDispatcherBridge($container)
        );
    }

    public function testCreateStatesManager()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::any())
            ->method('get')
            ->withConsecutive([EventDispatcherBridgeInterface::class])
            ->willReturnOnConsecutiveCalls($this->createMock(EventDispatcherBridgeInterface::class));

        self::assertInstanceOf(
            Manager::class,
            StatesServiceProvider::createStatesManager($container)
        );
    }

    public function testCreateObservedFactory()
    {
        self::assertInstanceOf(
            ObservedFactory::class,
            StatesServiceProvider::createObservedFactory()
        );
    }

    public function testCreateObserver()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::any())
            ->method('get')
            ->withConsecutive(
                [ObservedFactoryInterface::class],
                [EventDispatcherBridgeInterface::class],
                [TokenizerInterface::class]
            )
            ->willReturnOnConsecutiveCalls(
                $this->createMock(ObservedFactoryInterface::class),
                $this->createMock(EventDispatcherBridgeInterface::class),
                $this->createMock(TokenizerInterface::class)
            );

        self::assertInstanceOf(
            Observer::class,
            StatesServiceProvider::createObserver($container)
        );
    }

    public function testCreateYamlParser()
    {
        self::assertInstanceOf(
            Parser::class,
            StatesServiceProvider::createYamlParser()
        );
    }

    public function testCreateGaufretteAdapter()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::any())
            ->method('get')
            ->with('teknoo.vendor.service.gaufrette.root_dir')
            ->willReturn(__DIR__);

        self::assertInstanceOf(
            Local::class,
            StatesServiceProvider::createGaufretteAdapter($container)
        );
    }

    public function testCreateGaufretteFilesystem()
    {
        $container = $this->createMock(ContainerInterface::class);
        $container->expects(self::any())
            ->method('get')
            ->with(StatesServiceProvider::VENDOR_GAUFRETTE_ADAPTER)
            ->willReturn($this->createMock(Local::class));

        self::assertInstanceOf(
            Filesystem::class,
            StatesServiceProvider::createGaufretteFilesystem($container)
        );
    }
}