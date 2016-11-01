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
namespace Teknoo\Tests\UniversalPackage\States\Event;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Teknoo\UniversalPackage\States\Event\Event;
use Teknoo\UniversalPackage\States\Event\EventDispatcherBridge;
use Teknoo\States\LifeCycle\Event\EventDispatcherBridgeInterface;

/**
 * Test EventDispatcherBridgeTest
 * @covers \Teknoo\UniversalPackage\States\Event\EventDispatcherBridge
 */
class EventDispatcherBridgeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|EventDispatcher
     */
    public function getEventDispatcherMock()
    {
        if (!$this->eventDispatcher instanceof EventDispatcher) {
            $this->eventDispatcher = $this->createMock(EventDispatcher::class);
        }

        return $this->eventDispatcher;
    }

    /**
     * @return EventDispatcherBridge
     */
    public function buildBridge(): EventDispatcherBridge
    {
        return new EventDispatcherBridge($this->getEventDispatcherMock());
    }

    /**
     * @expectedException \TypeError
     */
    public function testDispatchBadEvent()
    {
        $this->buildBridge()->dispatch('foo.bar', new \stdClass());
    }

    public function testDispatch()
    {
        $event = $this->createMock(Event::class);

        $this->getEventDispatcherMock()
            ->expects(self::once())
            ->method('dispatch')
            ->with('foo.bar', $event);

        self::assertInstanceOf(
            EventDispatcherBridgeInterface::class,
            $this->buildBridge()->dispatch('foo.bar', $event)
        );
    }

    public function testAddListener()
    {
        $this->getEventDispatcherMock()
            ->expects(self::once())
            ->method('addListener')
            ->with('foo.bar', ['strlen'], 123);

        self::assertInstanceOf(
            EventDispatcherBridgeInterface::class,
            $this->buildBridge()->addListener('foo.bar', ['strlen'], 123)
        );
    }

    public function testRemoveListener()
    {
        $this->getEventDispatcherMock()
            ->expects(self::once())
            ->method('removeListener')
            ->with('foo.bar', ['strlen']);

        self::assertInstanceOf(
            EventDispatcherBridgeInterface::class,
            $this->buildBridge()->removeListener('foo.bar', ['strlen'])
        );
    }
}