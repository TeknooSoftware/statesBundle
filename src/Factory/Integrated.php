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

namespace UniAlteri\Bundle\StatesBundle\Factory;

use UniAlteri\States\Factory\Integrated as StatesIntegrated;

/**
 * Class Integrated
 * Extends of \UniAlteri\States\Factory\Integrated to support Doctrine proxy :
 * Use the \UniAlteri\Bundle\StatesBundle\Factory\StartupFactory instead of \UniAlteri\States\Factory\StartupFactory.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @license     http://teknoo.it/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class Integrated extends StatesIntegrated
{
    /**
     * {@inheritdoc}
     */
    protected function initialize(\string $statedClassName): \UniAlteri\States\Factory\FactoryInterface
    {
        //Call trait's method to initialize this stated class
        $this->traitInitialize($statedClassName);

        //Build the factory identifier (the proxy class name)
        $parts = explode('\\', $statedClassName);
        $statedClassName .= '\\'.array_pop($parts);

        //Register this factory into the startup factory
        StartupFactory::registerFactory($statedClassName, $this);

        return $this;
    }
}
