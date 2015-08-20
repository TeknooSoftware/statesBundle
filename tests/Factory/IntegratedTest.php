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

use UniAlteri\Bundle\StatesBundle\Factory;
use UniAlteri\States\Factory\FactoryInterface;

class IntegratedTest extends \UniAlteri\Tests\States\Factory\IntegratedTest
{
    /**
     * Return the Factory Object Interface.
     *
     * @param bool $populateContainer to populate di container of this factory
     *
     * @return FactoryInterface
     */
    public function getFactoryObject($populateContainer = true)
    {
        $factory = new Factory\Integrated();
        if (true === $populateContainer) {
            $factory->setDIContainer($this->container);
        }

        return $factory;
    }

    /**
     * Test if the factory Integrated initialize the StartupFactory.
     */
    public function testInitialization()
    {
        Factory\StartupFactory::reset();
        $factory = $this->getFactoryObject(true);
        $factory->initialize('foo', 'bar');
        $this->assertEquals(
            array(
                'foo\\foo',
            ),
            Factory\StartupFactory::listRegisteredFactory()
        );
    }
}
