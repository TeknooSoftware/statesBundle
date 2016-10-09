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
namespace Teknoo\Tests\Bundle\StatesBundle\Document;

use Teknoo\States\Proxy\ProxyInterface;
use Teknoo\Tests\Bundle\StatesBundle\Support;
use Teknoo\Tests\States\Proxy\StandardTest;

/**
 * Class StandardDocumentTest.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @covers \Teknoo\Bundle\StatesBundle\Document\StandardDocument
 * @covers \Teknoo\Bundle\StatesBundle\Document\StandardTrait
 */
class StandardDocumentTest extends StandardTest
{
    /**
     * Build a proxy object, into $this->_proxy to test it.
     *
     * @return ProxyInterface
     */
    protected function buildProxy()
    {
        $this->proxy = new Support\StandardDocument();

        return $this->proxy;
    }

    /**
     * Test if the class initialize its vars from the trait constructor.
     */
    public function testPostLoadDoctrine()
    {
        $proxyReflectionClass = new \ReflectionClass('\Teknoo\Tests\Bundle\StatesBundle\Support\StandardDocument');
        $proxy = $proxyReflectionClass->newInstanceWithoutConstructor();
        $proxy->postLoadDoctrine();
        $this->assertSame(array(), $proxy->listAvailableStates());

        return;
    }
}
