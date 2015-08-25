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
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @license     http://teknoo.it/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace UniAlteri\Tests\Bundle\StatesBundle;

use Symfony\Component\DependencyInjection\Container;
use UniAlteri\Bundle\StatesBundle\UniAlteriStatesBundle;
use UniAlteri\States;
use UniAlteri\States\Loader;

class UniAlteriStatesBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    protected $_container = null;

    protected function setUp()
    {
        if (!$this->_container instanceof Container) {
            $this->_container = new Container();
        }
        parent::setUp();
    }

    protected function tearDown()
    {
        if ($this->_container->has('unialteri.states.loader')) {
            spl_autoload_unregister(
                array($this->_container->get('unialteri.states.loader'), 'loadClass')
            );

            $this->_container->set('unialteri.states.loader', null);
        }

        parent::tearDown();
    }

    public function testLoaderInitialisation()
    {
        //Initialize container
        $bundle = new UniAlteriStatesBundle();
        $bundle->setContainer($this->_container);

        $eventManagerMock = $this->getMock('Doctrine\Common\EventManager', [], [], '', false);
        $eventManagerMock->expects($this->once())
            ->method('addEventListener')
            ->willReturnCallback(function ($name, $args) {
                $this->assertEquals('loadClassMetadata', $name);
                $this->assertInstanceOf('UniAlteri\Bundle\StatesBundle\Doctrine\LoadClassMetaListener', $args);
            });

        $this->_container->set('doctrine.event_listener', $eventManagerMock);

        $bundle->boot();

        $this->assertTrue($this->_container->has('unialteri.states.loader'));
        $loader = $this->_container->get('unialteri.states.loader');

        //Check if the loader implements the good interface
        $this->assertInstanceOf('\\UniAlteri\\States\\Loader\\LoaderInterface', $loader);

        $this->assertInstanceOf('\\Closure', $loader->getFinderFactory());

        $finder = $loader->getFinderFactory()->__invoke('class', 'path');
        $this->assertInstanceOf('\\UniAlteri\\States\\Loader\\FinderInterface', $finder);
        if ($finder instanceof Loader\FinderInterface) {
            $this->assertEquals('class', $finder->getStatedClassName());
        }
    }
}
