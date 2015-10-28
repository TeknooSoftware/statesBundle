States Bundle - Instruction to install
======================================

Install with composer
---------------------
You must run the command at the root of your project :

`php composer.phar require teknoo/states-bundle`

Register the bundle into the kernel
-----------------------------------
You must register this bundle in your kernel app (file `app/AppKernel.php`), add the line :
`new Teknoo\Bundle\StatesBundle\TeknooStatesBundle()`

in the declared bundle list (commonly defined by the array `$bundles`.

*Warning*, you must declare the States bundle *before* your business bundle.

States library bootstrapping
---------------------------

The states library need a specific bootstrapping to initialize the library :

This bootstrap file instantiate the library.

*   Creates the service to build a new finder (object to locate files of each stated class).
*   Find the Composer instance
*   Instantiates a new loader instance with the composer instance. 
*   Registers the loader in the stack __autoload.

This bootstrapping is provided by `Teknoo\Bundle\StatesBundle\Service\ComposerFinderService` to get the composer instance
from PHP's API and Symfony' components and by `Teknoo\Bundle\StatesBundle\Service\BootstrapService` to reproduce
the behavior of the bootstrap implementation of the `States library` (Not called in this case).

Theses components are implemented via the container and the service definition written in `src/Resources/config/services.yml`.

Configure yours bundle
----------------------
With States 2.x and States Bundle, it is not mandatory to declare bundle namespace in the States library,
The loader reuse now Composer, it's automatic.

By default, the bundle use all components of the States library, but you can change some components :

*  The factory repository to store all instances of loaded stated classes factories. It must implement the interface \ArrayInterface
*  The loader of stated classes. It must implement the interface `Teknoo\States\Loader\LoaderInterface`
*  The finder, used by the loaded to explore stated classes. It must implement the interface `Teknoo\States\Loader\FinderInterface`
*  The function to call to register the loader in the `__autoload` stack. It must has the same signature has `spl_autoload_register`
