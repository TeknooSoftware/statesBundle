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

namespace UniAlteri\Tests\Bundle\StatesBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;
use UniAlteri\Bundle\StatesBundle\UniAlteriStatesBundle;

/**
 * Class UniAlteriStatesBundleTest.
 *
 * @copyright   Copyright (c) 2009-2016 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @covers UniAlteri\Bundle\StatesBundle\UniAlteriStatesBundle
 */
class UniAlteriStatesBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ContainerInterface
     */
    protected $containerMock;

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ContainerInterface
     */
    protected function getContainerMock()
    {
        if (!$this->containerMock instanceof ContainerInterface) {
            $this->containerMock = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface', [], [], '', false);
        }

        return $this->containerMock;
    }

    public function buildService()
    {
        $bundle = new UniAlteriStatesBundle();
        $bundle->setContainer($this->getContainerMock());

        return $bundle;
    }

    public function testBoot()
    {
        $this->getContainerMock()
            ->expects($this->once())
            ->method('get')
            ->with('unialteri.states.loader')
            ->willReturn($this->getMock('UniAlteri\States\Loader\LoaderInterface', [], [], '', false));

        $this->buildService()->boot();
    }
}
