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
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
namespace Teknoo\Tests\Bundle\StatesBundle\DependencyInjection;

use Teknoo\Bundle\StatesBundle\DependencyInjection\TeknooStatesExtension;

/**
 * Class TeknooStatesExtensionTest.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @covers Teknoo\Bundle\StatesBundle\DependencyInjection\TeknooStatesExtension
 */
class TeknooStatesExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return TeknooStatesExtension
     */
    public function buildExtension()
    {
        return new TeknooStatesExtension();
    }

    public function testLoadEmpty()
    {
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder', [], [], '', false);

        $containerMock->expects($this->atLeastOnce())->method('setParameter');
        $containerMock->expects($this->atLeastOnce())->method('setDefinition');

        $this->buildExtension()->load([], $containerMock);
    }

    public function testLoadFull()
    {
        $containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder', [], [], '', false);

        $containerMock->expects($this->any())
            ->method('setParameter')
            ->willReturnCallback(function ($name, $value) {
                switch ($name) {
                    case 'teknoo.states.bootstraping.factory.repository.class.name';
                        $this->assertTrue(
                            'fooBarRepository' === $value
                            || '%teknoo.states.service.factory.repository.class%' === $value
                        );
                        break;
                    case 'teknoo.states.bootstraping.loader.class.name':
                        $this->assertTrue(
                            'fooBarLoader' === $value
                            || '%teknoo.states.loader.class%' === $value
                        );
                        break;
                    case 'teknoo.states.bootstraping.finder.class.name':
                        $this->assertTrue(
                            'fooBarFinder' === $value
                            || '%teknoo.states.finder.class%' === $value
                        );
                        break;
                    case 'teknoo.states.bootstraping.autoloader.register.function':
                        $this->assertTrue(
                            'fooBarAutoload' === $value
                            || 'spl_autoload_register' === $value
                        );
                        break;
                }
            });
        $containerMock->expects($this->atLeastOnce())->method('setDefinition');

        $this->buildExtension()->load(
            [
                [
                    'factory_repository' => 'fooBarRepository',
                    'loader' => 'fooBarLoader',
                    'finder' => 'fooBarFinder',
                    'autoload_register' => 'fooBarAutoload',
                    'enable_lifecycable' => true
                ],
            ],
            $containerMock
        );
    }
}
