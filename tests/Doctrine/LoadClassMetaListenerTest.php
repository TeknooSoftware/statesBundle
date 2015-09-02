<?php

namespace UniAlteri\Tests\Bundle\StatesBundle\Doctrine;

use UniAlteri\Bundle\StatesBundle\Doctrine\LoadClassMetaListener;

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