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

namespace Teknoo\Tests\Bundle\StatesBundle;

use Teknoo\Bundle\StatesBundle\Factory;
use Teknoo\States\Factory\FactoryInterface;

class IntegratedTest extends \Teknoo\Tests\States\Factory\IntegratedTest
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
