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
namespace Teknoo\Bundle\StatesBundle\Factory;

use Teknoo\States\Factory\Integrated as StatesIntegrated;

/**
 * Class Integrated
 * Extends of \Teknoo\States\Factory\Integrated to support Doctrine proxy :
 * Use the \Teknoo\Bundle\StatesBundle\Factory\StartupFactory instead of \Teknoo\States\Factory\StartupFactory.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
class Integrated extends StatesIntegrated
{
    /**
     * {@inheritdoc}
     */
    protected function initialize(string $statedClassName): \Teknoo\States\Factory\FactoryInterface
    {
        //Call trait's method to initialize this stated class
        $this->traitInitialize($statedClassName);

        //Build the factory identifier (the proxy class name)
        $parts = \explode('\\', $statedClassName);
        $statedClassName .= '\\'.\array_pop($parts);

        //Register this factory into the startup factory
        StartupFactory::registerFactory($statedClassName, $this);

        return $this;
    }
}
