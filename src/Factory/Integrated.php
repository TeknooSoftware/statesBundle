<?php
/**
 * StatesBundle
 *
 * LICENSE
 *
 * This source file is subject to the MIT license and the version 3 of the GPL3
 * license that are bundled with this package in the folder licences
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@uni-alteri.com so we can send you a copy immediately.
 *
 * @subpackage  StatesBundle
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     1.0.0
 */

namespace UniAlteri\Bundle\StatesBundle\Factory;

use UniAlteri\States\Factory;
use UniAlteri\States\Factory\Exception;

/**
 * Class Integrated
 * Extends of \UniAlteri\States\Factory\Integrated to support Doctrine proxy :
 * Use the \UniAlteri\Bundle\StatesBundle\Factory\StartupFactory instead of \UniAlteri\States\Factory\StartupFactory
 *
 * @package     States
 * @subpackage  StatesBundle
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class Integrated extends Factory\Integrated
{
    /**
     * Method called by the Loader to initialize the stated class :
     * It registers the class name and its path, retrieves the DI Container,
     * register the factory in the DI Container, it retrieves the finder object and load the proxy
     * from the finder.
     * @param  string                           $statedClassName the name of the stated class
     * @param  string                           $path            of the stated class
     * @return boolean
     * @throws Exception\UnavailableLoader      if any finder are available for this stated class
     * @throws Exception\UnavailableDIContainer if there are no di container
     */
    public function initialize($statedClassName, $path)
    {
        //Call trait's method to initialize this stated class
        $this->traitInitialize($statedClassName, $path);
        //Build the factory identifier (the proxy class name)
        $parts = explode('\\', $statedClassName);
        $statedClassName .= '\\'.array_pop($parts);
        //Register this factory into the startup factory
        //(Use the \UniAlteri\Bundle\StatesBundle\Factory\StartupFactory instead
        //  of \UniAlteri\States\Factory\StartupFactory)
        StartupFactory::registerFactory($statedClassName, $this);
    }
}
