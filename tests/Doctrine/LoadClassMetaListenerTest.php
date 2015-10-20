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
 * @copyright   Copyright (c) 2009-2016 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace UniAlteri\Tests\Bundle\StatesBundle\Doctrine;

use UniAlteri\Bundle\StatesBundle\Doctrine\LoadClassMetaListener;

/**
 * Class LoadClassMetaListenerTest.
 *
 * @copyright   Copyright (c) 2009-2016 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @covers UniAlteri\Bundle\StatesBundle\Doctrine\LoadClassMetaListener
 */
class LoadClassMetaListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return LoadClassMetaListener
     */
    public function buildListener()
    {
        return new LoadClassMetaListener();
    }

    public function testRegisterGetLoader()
    {
        $loader = $this->getMock('UniAlteri\States\Loader\LoaderInterface', [], [], '', false);
        $listener = $this->buildListener();

        $this->assertInstanceOf(
            'UniAlteri\Bundle\StatesBundle\Doctrine\LoadClassMetaListener',
            $listener->registerLoader($loader)
        );

        $this->assertEquals($loader, $listener->getLoader());
    }

    public function testLoadClassMetadata()
    {
        $classMeta = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata', [], [], '', false);
        $classMeta->expects($this->any())->method('getName')->willReturn('my\Stated\Class');

        $eventArgs = $this->getMock('Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs', [], [], '', false);
        $eventArgs->expects($this->any())->method('getClassMetadata')->willReturn($classMeta);

        $loader = $this->getMock('UniAlteri\States\Loader\LoaderInterface', [], [], '', false);
        $loader->expects($this->once())->method('loadClass')->with('my\Stated\Class');

        $listener = $this->buildListener()->registerLoader($loader);
        $listener->loadClassMetadata($eventArgs);
    }
}
