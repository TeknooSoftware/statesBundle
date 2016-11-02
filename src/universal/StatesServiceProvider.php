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

namespace Teknoo\UniversalPackage\States;

use Gaufrette\Adapter\Local;
use Gaufrette\Filesystem;
use Interop\Container\ContainerInterface;
use Interop\Container\ServiceProvider;
use Symfony\Component\Yaml\Parser;
use Teknoo\States\LifeCycle\Event\EventDispatcherBridgeInterface;
use Teknoo\States\LifeCycle\Observing\Observed;
use Teknoo\States\LifeCycle\Observing\ObservedFactory;
use Teknoo\States\LifeCycle\Observing\ObservedFactoryInterface;
use Teknoo\States\LifeCycle\Observing\Observer;
use Teknoo\States\LifeCycle\Observing\ObserverInterface;
use Teknoo\States\LifeCycle\Scenario\Manager;
use Teknoo\States\LifeCycle\Scenario\ManagerInterface;
use Teknoo\States\LifeCycle\Tokenization\Tokenizer;
use Teknoo\States\LifeCycle\Tokenization\TokenizerInterface;
use Teknoo\States\LifeCycle\Trace\Trace;
use Teknoo\UniversalPackage\States\Event\Event;
use Teknoo\UniversalPackage\States\Event\EventDispatcherBridge;

/**
 * Definition provider following PSR 11 Draft to build an universal bundle/package.
 */
class StatesServiceProvider implements ServiceProvider
{
    /**
     * Constants to define services' keys in container
     */
    const SERVICE_TOKENIZER_CLASS = 'teknoo.states.lifecyclable.service.tokenizer.class';
    const SERVICE_TOKENIZER = 'teknoo.states.lifecyclable.service.tokenizer';
    const SERVICE_EVENT_DISPATCHER_BRIDGE_CLASS = 'teknoo.states.lifecyclable.bridge.event_dispatcher.class';
    const SERVICE_EVENT_DISPATCHER_BRIDGE = 'teknoo.states.lifecyclable.bridge.event_dispatcher';
    const SERVICE_MANAGER_CLASS = 'teknoo.states.lifecyclable.service.manager.class';
    const SERVICE_MANAGER = 'teknoo.states.lifecyclable.service.manager';
    const SERVICE_OBSERVED_FACTORY_CLASS = 'teknoo.states.lifecyclable.service.observed.factory.class';
    const SERVICE_OBSERVED_FACTORY = 'teknoo.states.lifecyclable.service.observed.factory';
    const SERVICE_OBSERVER_CLASS = 'teknoo.states.lifecyclable.service.observer.class';
    const SERVICE_OBSERVER = 'teknoo.states.lifecyclable.service.observer';
    const VENDOR_YAML_PARSER = 'teknoo.vendor.yaml.parser';
    const VENDOR_GAUFRETTE_ADAPTER = 'teknoo.vendor.service.gaufrette.adapter';
    const VENDOR_GAUFRETTE_FILESYSTEM = 'teknoo.vendor.service.gaufrette.filesystem';

    /**
     * @param ContainerInterface $container
     * @return TokenizerInterface
     */
    public static function createStatesTokenizer(ContainerInterface $container): TokenizerInterface
    {
        $tokenizeClass = $container->get(static::SERVICE_TOKENIZER_CLASS);
        return new $tokenizeClass();
    }

    /**
     * @param ContainerInterface $container
     * @return EventDispatcherBridgeInterface
     */
    public static function createEventDispatcherBridge(ContainerInterface $container): EventDispatcherBridgeInterface
    {
        $eventDispatcherClass = $container->get(static::SERVICE_EVENT_DISPATCHER_BRIDGE_CLASS);
        return new $eventDispatcherClass($container->get('event_dispatcher'));
    }

    /**
     * @param ContainerInterface $container
     * @param callable|null $getPrevious
     * @return ManagerInterface
     */
    public static function createStatesManager(
        ContainerInterface $container,
        callable $getPrevious = null
    ): ManagerInterface {
        if (is_callable($getPrevious)) {
            return $getPrevious();
        }

        $managerClass = $container->get(static::SERVICE_MANAGER_CLASS);
        return new $managerClass($container->get(EventDispatcherBridgeInterface::class));
    }

    /**
     * @param ContainerInterface $container
     * @return ObservedFactoryInterface
     */
    public static function createObservedFactory(ContainerInterface $container): ObservedFactoryInterface
    {
        $factoryClass = $container->get(static::SERVICE_OBSERVED_FACTORY_CLASS);
        return new $factoryClass(
            Observed::class,
            Event::class,
            Trace::class
        );
    }

    /**
     * @param ContainerInterface $container
     * @param callable|null $getPrevious
     * @return ObserverInterface
     */
    public static function createObserver(
        ContainerInterface $container,
        callable $getPrevious = null
    ): ObserverInterface {
        $observer = null;
        if (is_callable($getPrevious)) {
            $observer = $getPrevious();
        } else {
            $observerClass = $container->get(static::SERVICE_OBSERVER_CLASS);
            $observer = new $observerClass($container->get(ObservedFactoryInterface::class));
        }

        if ($observer instanceof ObserverInterface) {
            $observer->addEventDispatcher($container->get(EventDispatcherBridgeInterface::class));
            $observer->setTokenizer($container->get(TokenizerInterface::class));
        }

        return $observer;
    }

    /**
     * @return Parser
     */
    public static function createYamlParser()
    {
        return new Parser();
    }

    /**
     * @param ContainerInterface $container
     * @return Local
     */
    public static function createGaufretteAdapter(ContainerInterface $container)
    {
        return new Local($container->get('teknoo.vendor.service.gaufrette.root_dir'));
    }

    /**
     * @param ContainerInterface $container
     * @return Filesystem
     */
    public static function createGaufretteFilesystem(ContainerInterface $container)
    {
        return new Filesystem($container->get('teknoo.vendor.service.gaufrette.adapter'));
    }

    /**
     * {@inheritdoc}
     */
    public function getServices()
    {
        return [
            //teknoo.states.lifecyclable.service.tokenizer
            static::SERVICE_TOKENIZER_CLASS => Tokenizer::class,
            TokenizerInterface::class => [static::class, 'createStatesTokenizer'],
            static::SERVICE_TOKENIZER => [static::class, 'createStatesLCTokenizer'],

            //teknoo.states.lifecyclable.bridge.event_dispatcher
            static::SERVICE_EVENT_DISPATCHER_BRIDGE_CLASS => EventDispatcherBridge::class,
            EventDispatcherBridgeInterface::class => [static::class, 'createEventDispatcherBridge'],
            static::SERVICE_EVENT_DISPATCHER_BRIDGE => [static::class, 'createEventDispatcherBridge'],

            //teknoo.states.lifecyclable.service.manager
            static::SERVICE_MANAGER_CLASS => Manager::class,
            ManagerInterface::class => [static::class, 'createStatesManager'],
            static::SERVICE_MANAGER => [static::class, 'createStatesManager'],

            //teknoo.states.lifecyclable.service.observed.factory
            static::SERVICE_OBSERVED_FACTORY_CLASS => ObservedFactory::class,
            ObservedFactoryInterface::class => [static::class, 'createObservedFactory'],
            static::SERVICE_OBSERVED_FACTORY => [static::class, 'createObservedFactory'],

            //teknoo.states.lifecyclable.service.observer
            static::SERVICE_OBSERVER_CLASS => Observer::class,
            ObserverInterface::class => [static::class, 'createObserver'],
            static::SERVICE_OBSERVER => [static::class, 'createObserver'],

            //teknoo.vendor.service.yaml.parser
            static::VENDOR_YAML_PARSER => [static::class, 'createYamlParser'],
            //teknoo.vendor.service.gaufrette.adapter
            static::VENDOR_GAUFRETTE_ADAPTER => [static::class, 'createGaufretteAdapter'],
            //teknoo.vendor.service.gaufrette.filesystem
            static::VENDOR_GAUFRETTE_FILESYSTEM => [static::class, 'createGaufretteFilesystem'],
        ];
    }
}
