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
`@ORM\PostLoad()`.

This bundle provides an extension of the integrated proxy to implement a new callback used like a constructor. This callback
 is implemented by the method `postLoadDoctrine()`.

This extension provides also a new method, called `updateState()`. This method is called by the constructor or by the callback
`postLoadDoctrine()` to allow you to enable good states of your entity from entities' data.

To use this extension, your entities must extend the class `\Teknoo\Bundle\StatesBundle\Entity\IntegratedEntity`.
 The factory of your stated class must also inherit the class `\Teknoo\Bundle\StatesBundle\Factory\Integrated`.

Proxy with Twig
---------------

Before test a call the specific getter of a required entity's attribute, `Twig` tests with `isset()` if the attribute
 is available directly. So, PHP will call the method `__isset` of the proxy even if the method is not available in
 enabled states.

To avoid error, your proxy must override the method `__isset`. If the method is not used by your class, the method must
 return only false.

*Nota Bene* : The extension `\Teknoo\Bundle\StatesBundle\Entity\IntegratedEntity` override already this method.

Startup factory
---------------

Doctrine can create its own proxy for each entity when it is loaded to do a lazy loading of its relations. This proxy
 extends the entity's class and its signature can be differ of the original signature. This behavior is not managed by
 the default `\Teknoo\States\Factory\StandardStartupFactory`.

This bundle provides an extended startup factory, called `\Teknoo\Bundle\StatesBundle\Factory\StartupFactory`,
 used by the provided proxy `\Teknoo\Bundle\StatesBundle\Entity\IntegratedEntity`, and can manage Dotrcine's proxy
 to retrieve and return the original class name.