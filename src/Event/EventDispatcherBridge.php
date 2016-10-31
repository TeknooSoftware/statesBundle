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
namespace Teknoo\Bundle\StatesBundle\Event;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Teknoo\States\LifeCycle\Event\EventDispatcherBridgeInterface;
use Teknoo\States\LifeCycle\Event\EventInterface;

class EventDispatcherBridge implements EventDispatcherBridgeInterface
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * EventDispatcherBridge constructor.
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($eventName, EventInterface $event = null): EventDispatcherBridgeInterface
    {
        $this->eventDispatcher->dispatch($eventName, $event);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addListener($eventName, $listener, $priority = 0): EventDispatcherBridgeInterface
    {
        $this->eventDispatcher->addListener($eventName, $listener, $priority);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener($eventName, $listener): EventDispatcherBridgeInterface
    {
        $this->eventDispatcher->removeListener($eventName, $listener);

        return $this;
    }
}