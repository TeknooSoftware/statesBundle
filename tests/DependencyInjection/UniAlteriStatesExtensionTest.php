<?php

namespace UniAlteri\Tests\Bundle\StatesBundle\DependencyInjection;

use UniAlteri\Bundle\StatesBundle\DependencyInjection\UniAlteriStatesExtension;

class UniAlteriStatesExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return UniAlteriStatesExtension
     */
    public function buildExtension()
    {
        return new UniAlteriStatesExtension();
    }

    public function testLoad()
    {
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder', [], [], '', false);

        $containerMock->expects($this->atLeastOnce())->method('setParameter');
        $containerMock->expects($this->atLeastOnce())->method('setDefinition');

        $this->buildExtension()->load([], $containerMock);
    }
}