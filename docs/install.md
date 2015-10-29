States Bundle - Instruction to install
======================================

Install with composer
---------------------
You must run the command at the root of your project :

`php composer.phar require teknoo/states-bundle:dev-master`

Register the bundle into the kernel
-----------------------------------
You must register this bundle in your kernel app (file `app/AppKernel.php`), add the line :
`new Teknoo\Bundle\StatesBundle\TeknooStatesBundle()`

in the declared bundle list (commonly defined by the array `$bundles`.

*Warning*, you must declare the States bundle *before* your business bundle.

States library bootstrapping
---------------------------

The states library need a bootloader to initialize the library :

*   initialize an autoloader if no autoloader is available.
*   initialize the DI Container to use with the library (default `States` use `Pimple`).
*   instantiate the finder service to find and initialize all stated class's components (factory, proxy and states).
*   initialize the injection closure service
*   instantiate the stated class loader to detect and load stated class.
*   register the stated class loader in the php autoloader stack

An implementation of these operations is available in the file `/Teknoo/States/bootstrap.php` provided by the library.

This bundle implements its own bootstrap in the class `TeknooStatesBundle`. It is executed when the bundle is
 initialized by Symfony. The behavior of this implementation is like the default implementation, but the stated class
 loader is registered in the DI container of Symfony.

The stated class loader is available under the key `teknoo.states.loader`.

Configure yours bundle
----------------------

To work with the `States library`, you must register in the loader all namespace used to define your stated class.
You can do this operation in your your Bundle class (aka `AcmeDemoBundle` for the bundle acme/demo) :

If it is not already done, override the method `boot()`. Get the `States loader` from the Symfony container like this :
`$loader = $this->container->get('teknoo.states.loader');`

Register your stated class in the loader, like this :
`$loader->registerNamespace('\\Acme\\DemoBundle\\Entity', __DIR__.DIRECTORY_SEPARATOR.'Entity');`