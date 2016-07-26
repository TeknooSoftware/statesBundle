Teknoo Software - States bundle
===========================

Installation
------------
To install this library with composer, run this command :

    composer require teknoo/states-bundle

Integrated entities and factories with doctrine
-----------------------------------------------

By default, Doctrine does not call the constructor when it load an entity from the dbms. The behavior may cause errors
 with the integrated proxy :

*   internal attributes of the proxy are not initialized
*   the proxy is not initialized by the proxy, states are not loaded

But Doctrine provides a mechanism of callbacks, including a callback executed when an entity is loaded, it's the callback
`@PostLoad()`.

This bundle provides an extension of the integrated proxy to implement a new callback used like a constructor. This callback
 is implemented by the method `postLoadDoctrine()`.

This extension provides also a new method, called `updateState()`. This method is called by the constructor or by the callback
`postLoadDoctrine()` to allow you to enable good states of your entity from entities' data.

To use this extension, your entities must extend the class `\Teknoo\Bundle\StatesBundle\Entity\IntegratedEntity` for Doctrine ORM
and `\Teknoo\Bundle\StatesBundle\Entity\IntegratedDocument` for Doctrine ODM. You can also use traits used by these
classes, do not forget to declare also the static attribute `$startupFactoryClassName` like for these classes.
 
The factory of your stated class must also inherit the class `\Teknoo\Bundle\StatesBundle\Factory\Integrated` for Doctrine ORM 
or Doctrine ODM.

Proxy with Twig
---------------

States is now fully compatible with Twig without extra code, `__isset` is not implemented by the default proxy implementation.

Startup factory
---------------

Doctrine can create its own proxy for each entity when it is loaded to do a lazy loading of its relations. This proxy
 extends the entity's class and its signature can be differ of the original signature. This behavior is not managed by
 the default `\Teknoo\States\Factory\StandardStartupFactory`.

This bundle provides an extended startup factory, called `\Teknoo\Bundle\StatesBundle\Factory\StartupFactory`,
 used by the provided proxy `\Teknoo\Bundle\StatesBundle\Entity\IntegratedEntity`.
 
With Doctrine ODM, you must use `\Teknoo\Bundle\StatesBundle\Factory\MongoStartupFactory` instead of.

States Life cyclable extensions
-------------------------------

States bundle supports also the Life cyclable extension available for Teknoo States 2+.

The States bundle provides all needed services, provided by the extension generator, in the service container :

*   `@teknoo.states.lifecyclable.service.tokenizer` for `\Teknoo\States\LifeCycle\Generator::getTokenizer()`
*   `@teknoo.states.lifecyclable.service.manager` for `\Teknoo\States\LifeCycle\Generator::getManager()`
*   `@teknoo.states.lifecyclable.service.observer` for `\Teknoo\States\LifeCycle\Generator::getObserver()`
*   The bundle use the default event dispatcher provided by symfony (`@event_dispatcher`) instead of 
    `\Teknoo\States\LifeCycle\Generator::getObserver()`

Some prototypes are also defined in the service container to get new instances of `ScenarioBuilder`,
`ScenarioYamlBuilder` and `Scenario`

 *  To get a new instance of `ScenarioBuilder` : `$this->get('teknoo.states.lifecyclable.prototype.scenario_builder');`
 *  To get a new instance of `ScenarioYamlBuilder` : `$this->get('teknoo.states.lifecyclable.prototype.scenario_yaml_builder');`
 *  To get a new instance of `Scenario` : `$this->get('teknoo.states.lifecyclable.prototype.scenario');`

Example to build a new scenario with ScenarioBuilder
----------------------------------------------------

    $manager = $this->get('teknoo.states.lifecyclable.service.manager');
    $manager->registerScenario(
        $this->get('teknoo.states.lifecyclable.prototype.scenario_builder')
            ->towardStatedClass('AppBundle\Demo\Acme\Class')
            ->onIncomingState('State3')
            ->onOutgoingState('State2')
            ->ifNotInState('StateDefault')
            ->run(function () use ($instanceB) {
                $instanceB->switchToStateDefault();
            })
            ->build($this->get('teknoo.states.lifecyclable.prototype.scenario'))
    );

Example to build a new scenario with ScenarioYamlBuilder
--------------------------------------------------------

**Warning, Yaml builder is automatically instancied with a Yaml parser and an instance of Gaufrette filesystem wrapper.
The wrapper is mounted on the root folder of your application : `%kernel.root_dir%/../`**

    $adapter = new Local(__DIR__.'/scenarii');
    $filesystem = new Filesystem($adapter);

    $manager = $this->get('teknoo.states.lifecyclable.service.manager');
    $manager->registerScenario(
        $this->get('teknoo.states.lifecyclable.prototype.scenario_yaml_builder')
            ->loadScenario('src/YourBundle/Scenarii/scenario1.yml')
            ->setParameter('instanceB', $instanceB)
            ->build($this->get('teknoo.states.lifecyclable.prototype.scenario'))
    );