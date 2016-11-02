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

use Teknoo\States\LifeCycle\Event\EventDispatcherBridgeInterface;
use Teknoo\States\LifeCycle\Observing\ObservedFactoryInterface;
use Teknoo\States\LifeCycle\Observing\ObserverInterface;
use Teknoo\States\LifeCycle\Scenario\ManagerInterface;
use Teknoo\States\LifeCycle\Tokenization\TokenizerInterface;
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
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.tokenizer.class']));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.tokenizer']));
        self::assertTrue(isset($definitions[EventDispatcherBridgeInterface::class]));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.bridge.event_dispatcher.class']));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.bridge.event_dispatcher']));
        self::assertTrue(isset($definitions[ManagerInterface::class]));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.manager.class']));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.manager']));
        self::assertTrue(isset($definitions[ObservedFactoryInterface::class]));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.observed.factory.class']));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.observed.factory']));
        self::assertTrue(isset($definitions[ObserverInterface::class]));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.observer.class']));
        self::assertTrue(isset($definitions['teknoo.states.lifecyclable.service.observer']));
        self::assertTrue(isset($definitions['teknoo.vendor.yaml.parser']));
        self::assertTrue(isset($definitions['teknoo.vendor.service.gaufrette.adapter']));
        self::assertTrue(isset($definitions['teknoo.vendor.service.gaufrette.filesystem']));
    }
}