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

namespace UniAlteri\Tests\Bundle\StatesBundle\DependencyInjection;

use UniAlteri\Bundle\StatesBundle\DependencyInjection\UniAlteriStatesExtension;

/**
 * Class UniAlteriStatesExtensionTest.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @license     http://teknoo.it/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @covers UniAlteri\Bundle\StatesBundle\DependencyInjection\UniAlteriStatesExtension
 */
class UniAlteriStatesExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return UniAlteriStatesExtension
     */
    public function buildExtension()
    {
        return new UniAlteriStatesExtension();
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
                    case 'unialteri.states.bootstraping.factory.repository.class.name';
                        $this->assertTrue(
                            'fooBarRepository' === $value
                            || '%unialteri.states.service.factory.repository.class%' === $value
                        );
                        break;
                    case 'unialteri.states.bootstraping.loader.class.name':
                        $this->assertTrue(
                            'fooBarLoader' === $value
                            || '%unialteri.states.loader.class%' === $value
                        );
                        break;
                    case 'unialteri.states.bootstraping.finder.class.name':
                        $this->assertTrue(
                            'fooBarFinder' === $value
                            || '%unialteri.states.finder.class%' === $value
                        );
                        break;
                    case 'unialteri.states.bootstraping.autoloader.register.function':
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
                ],
            ],
            $containerMock
        );
    }
}
