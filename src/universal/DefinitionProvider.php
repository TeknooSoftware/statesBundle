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

use Assembly\ArrayDefinitionProvider;
use Assembly\ObjectDefinition;
use Assembly\ParameterDefinition;
use Assembly\Reference;
use Gaufrette\Adapter\Local;
use Gaufrette\Filesystem;
use Symfony\Component\EventDispatcher\EventDispatcher;
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
class DefinitionProvider extends ArrayDefinitionProvider
{
    /**
     * DefinitionProvider constructor.
     */
    public function __construct()
    {
        parent::__construct([
            //teknoo.states.lifecyclable.service.tokenizer
            TokenizerInterface::class => (new ObjectDefinition(Tokenizer::class)),
            'teknoo.states.lifecyclable.service.tokenizer.class' => new ParameterDefinition(Tokenizer::class),
            'teknoo.states.lifecyclable.service.tokenizer' => new Reference(TokenizerInterface::class),

            //teknoo.states.lifecyclable.bridge.event_dispatcher
            EventDispatcherBridgeInterface::class => (new ObjectDefinition(EventDispatcherBridge::class))
                ->addConstructorArgument(new Reference(EventDispatcher::class)),
            'teknoo.states.lifecyclable.bridge.event_dispatcher.class' => new ParameterDefinition(ObjectDefinition::class),
            'teknoo.states.lifecyclable.bridge.event_dispatcher' => new Reference(EventDispatcherBridgeInterface::class),

            //teknoo.states.lifecyclable.service.manager
            ManagerInterface::class => (new ObjectDefinition(Manager::class))
                ->addConstructorArgument(new Reference(EventDispatcherBridgeInterface::class)),
            'teknoo.states.lifecyclable.service.manager.class' => new ParameterDefinition(ObjectDefinition::class),
            'teknoo.states.lifecyclable.service.manager' => new Reference(ManagerInterface::class),

            //teknoo.states.lifecyclable.service.observed.factory
            ObservedFactoryInterface::class => (new ObjectDefinition(ObservedFactory::class))
                ->addConstructorArgument(Observed::class)
                ->addConstructorArgument(Event::class)
                ->addConstructorArgument(Trace::class),
            'teknoo.states.lifecyclable.service.observed.factory.class' => new ParameterDefinition(ObjectDefinition::class),
            'teknoo.states.lifecyclable.service.observed.factory' => new Reference(ObservedFactoryInterface::class),

            //teknoo.states.lifecyclable.service.observer
            ObserverInterface::class => (new ObjectDefinition(Observer::class))
                ->addConstructorArgument(new Reference(ObservedFactoryInterface::class))
                ->addMethodCall('addEventDispatcher', new Reference(EventDispatcherBridgeInterface::class))
                ->addMethodCall('setTokenizer', new Reference(TokenizerInterface::class)),
            'teknoo.states.lifecyclable.service.observer.class' => new ParameterDefinition(Observer::class),
            'teknoo.states.lifecyclable.service.observer' => new Reference(ObserverInterface::class),

            //teknoo.vendor.service.yaml.parser
            'teknoo.vendor.yaml.parser' => (new ObjectDefinition(Parser::class)),
            //teknoo.vendor.service.gaufrette.adapter
            'teknoo.vendor.service.gaufrette.adapter' => (new ObjectDefinition(Local::class))
                ->addConstructorArgument(new Reference('%kernel.root_dir%/../')),
            //teknoo.vendor.service.gaufrette.filesystem
            'teknoo.vendor.service.gaufrette.filesystem' => (new ObjectDefinition(Filesystem::class))
                ->addConstructorArgument(new Reference('teknoo.vendor.service.gaufrette.adapter')),
        ]);
    }
}
