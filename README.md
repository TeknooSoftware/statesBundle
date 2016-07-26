Teknoo Software - States bundle
===========================

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0af6b8a7-8090-46cc-bd5f-d86c9f70282a/mini.png)](https://insight.sensiolabs.com/projects/0af6b8a7-8090-46cc-bd5f-d86c9f70282a) [![Build Status](https://travis-ci.org/TeknooSoftware/statesBundle.svg?branch=next)](https://travis-ci.org/TeknooSoftware/statesBundle)

States allows you to create PHP classes following the [State Pattern](http://en.wikipedia.org/wiki/State_pattern) in PHP. 
This can be a cleaner way for an object to change its behavior at runtime without resorting to large monolithic conditional statements and this improve maintainability.

This package is bundle to adapt the library [`States`](http://teknoo.software/states) to Symfony 2+.


Short Example
------------
    /**
     * File States/English.php
     */
    class English extends \Teknoo\States\State\AbstractState 
    {
        public function sayHello(): string
        {
            return 'Good morning, '.$this->name;
        }
    
        public function displayDate(\DateTime $now): string 
        {
            return $now->format('%m %d, %Y');
        }
    }
    
    /**
     * File States/French.php
     */
    class French extends \Teknoo\States\State\AbstractState 
    {
        public function sayHello(): string
        {
            return 'Bonjour, '.$this->name;
        }
    
        public function displayDate(\DateTime $now): string 
        {
            return $now->format('%d %m %Y');
        }
    }
    
    /**
     * File MyClass.php
     */
    class MyClass extends \Teknoo\Bundle\StatesBundle\Entity\IntegratedEntity
    {
        /**
         * @ORM\Column(type="string", length=250)
         * @var string
         */
        private $name;
        
        /**
         * @param string $name
         * @return self
         */
        public function setName(string $name): MyClass
        {
            $this->name = $name;
            
            return $this;
        }
    }
    
    $frenchMan = new MyClass();
    $frenchMan->switchState('French');
    $frenchMan->setName('Roger');
    
    $englishMan = new MyClass();
    $englishMan->switchState('Enflish');
    $englishMan->setName('Richard');
    
    $now = new \DateTime('2016-07-01');
    
    foreach ([$frenchMan, $englishMan] as $man) {
        echo $man->sayHello().PHP_EOL;
        echo 'Date: '.$man->displayDate($now);
    }
    
    //Display
    Bonjour Roger
    Date: 01 07 2016
    Good morning Richard
    Date: 07 01, 2016
    
Installation & Requirements
---------------------------

This library requires :

    * PHP 7+
    * Composer
    * States 2+
    * Symfony 2.7+
    
Instruction to install the States bundle with Symfony 2+ : [Install](docs/install.md).
    
This library support Doctrine, but Doctrine is not mandatory. (Stated classes can be use without Doctrine)    
    * Doctrine (Orm or Odm/Mongo)

Symfony Use
-----------

For main States's features, the bundle is transparent :

* States bundle's services are private and are not available. Only the loader is accessible via : `@teknoo.states.loader`
* Your Symfony's Doctrine entity (ORM) must use the trait `Teknoo\Bundle\StatesBundle\Entity\IntegratedTrait`.
    * Alternative, you can inherits `Teknoo\Bundle\StatesBundle\Entity\IntegratedEntity`.
* Your Symfony's Doctrine document (ODM) must use the trait `Teknoo\Bundle\StatesBundle\Document\IntegratedTrait`.
    * Alternative, you can inherits `Teknoo\Bundle\StatesBundle\Entity\IntegratedDocument`.
    
With the extension States Life cyclable :

* Observer instance to register a stated class and observe it : `@teknoo.states.lifecyclable.service.observer`
* Manager to register scenarii about stated class: `@teknoo.states.lifecyclable.service.manager`
* Prototype to create a new Yaml Scenario Builder `@teknoo.states.lifecyclable.prototype.scenario_yaml_builder`
* Prototype to create a new Scenario Builder `@teknoo.states.lifecyclable.prototype.scenario_builder`
* prototype to create a new Scenario : `@teknoo.states.lifecyclable.prototype.scenario`

Documentation to use States with Symfony 2+ : [Symfony](docs/symfony.md).

Quick startup
-------------
Quick How-to to learn how use this library : [Startup](https://github.com/TeknooSoftware/states/blob/master/docs/howto/quick-startup.md).

Example
-------
An example of using this library is available in the folder : [Demo](https://github.com/TeknooSoftware/states/blob/master/demo/demo_article.php).

API Documentation
-----------------
Generated documentation from the library with PhpDocumentor : [Open](https://cdn.rawgit.com/TeknooSoftware/states/master/docs/api/index.html).

Behavior Documentation
----------------------
Documentation to explain how this library works : [Behavior](https://github.com/TeknooSoftware/states/blob/master/docs/howto/behavior.md).

Credits
-------
Richard Déloge - <richarddeloge@gmail.com> - Lead developer.
Teknoo Software - <http://teknoo.software>

About Teknoo Software
---------------------
**Teknoo Software** is a PHP software editor, founded by Richard Déloge. 
Teknoo Software's DNA is simple : Provide to our partners and to the community a set of high quality services or software,
 sharing knowledge and skills.

License
-------
States is licensed under the MIT and GPL3+ Licenses - see the licenses folder for details

Contribute :)
-------------

You are welcome to contribute to this project. [Fork it on Github](CONTRIBUTING.md)
