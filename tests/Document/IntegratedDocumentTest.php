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
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */

namespace Teknoo\Tests\Bundle\StatesBundle\Document;

use Teknoo\States\Proxy\ProxyInterface;
use Teknoo\Tests\Bundle\StatesBundle\Support;
use Teknoo\Tests\States\Proxy\IntegratedTest;
use Teknoo\Tests\Support\MockStartupFactory;

class IntegratedDocumentTest extends IntegratedTest
{
    /**
     * Build a proxy object, into $this->_proxy to test it.
     *
     * @return ProxyInterface
     */
    protected function buildProxy()
    {
        $this->proxy = new Support\IntegratedDocument();

        return $this->proxy;
    }

    public function testIsset()
    {
        $proxy = $this->buildProxy();
        $this->assertFalse(isset($proxy->foo));
    }

    /**
     * Test exception behavior of the proxy when __set is not implemented into in actives states.
     */
    public function testIssetNonImplemented()
    {
        //Isset is always implemented here
    }

    /**
     * Test if the class initialize its vars from the trait constructor.
     */
    public function testPostLoadDoctrine()
    {
        $proxyReflectionClass = new \ReflectionClass('\Teknoo\Tests\Bundle\StatesBundle\Support\IntegratedDocument');
        $proxy = $proxyReflectionClass->newInstanceWithoutConstructor();
        MockStartupFactory::$calledProxyObject = null;
        $this->assertNull(MockStartupFactory::$calledProxyObject);
        $proxy->postLoadDoctrine();
        $this->assertSame(array(), $proxy->listAvailableStates());
        $this->assertSame($proxy, MockStartupFactory::$calledProxyObject);

        return;
    }

    public function testInStateNotStringBundle()
    {
        $proxy = $this->buildProxy();

        try {
            $proxy->inState(new \stdClass());
        } catch (\Exception $e) {
            return;
        }

        $this->fail('Error, the method must throw an exception when the argument is not valid');
    }

    public function testInStateNotInitializedBundle()
    {
        $proxyReflectionClass = new \ReflectionClass('\Teknoo\Tests\Bundle\StatesBundle\Support\IntegratedDocument');
        $proxy = $proxyReflectionClass->newInstanceWithoutConstructor();
        $this->assertFalse($proxy->inState('foo'));
    }

    public function testInStateBundle()
    {
        $proxy = $this->getMock('\Teknoo\Tests\Bundle\StatesBundle\Support\IntegratedDocument', array('listEnabledStates'), array(), '', false);
        $proxy->expects($this->any())
            ->method('listEnabledStates')
            ->withAnyParameters()
            ->willReturn(array('Foo', 'Bar'));

        $this->assertFalse($proxy->inState('hello'));
        $this->assertTrue($proxy->inState('fOo'));
    }
}
