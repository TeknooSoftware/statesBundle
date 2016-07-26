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
namespace Teknoo\Tests\Bundle\StatesBundle;

use Teknoo\Bundle\StatesBundle\Factory;
use Teknoo\States\Loader\FinderInterface;

/**
 * Class IntegratedTest.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @covers Teknoo\Bundle\StatesBundle\Factory\Integrated
 */
class IntegratedTest extends \Teknoo\Tests\States\Factory\IntegratedTest
{
    /**
     * @param FinderInterface $finder
     *
     * @return Factory\Integrated
     */
    public function getFactoryObject(FinderInterface $finder)
    {
        $factory = new Factory\Integrated(
            $finder->getStatedClassName(),
            $finder,
            $this->repository
        );

        return $factory;
    }
}
