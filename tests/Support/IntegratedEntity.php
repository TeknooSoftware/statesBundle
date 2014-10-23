<?php
/**
 * States
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
 * @version     0.9.9
 */

namespace UniAlteri\Tests\Bundle\StatesBundle\Support;

use \UniAlteri\Bundle\StatesBundle\Entity\IntegratedEntity as AbstractIntegratedEntity;

/**
 * Class IntegratedEntity
 * To build an specific instance of the class IntegratedEntity to test this default class.
 * By default, the class Proxy\Integrated uses '\UniAlteri\States\Factory\StandardStartupFactory' as startup factory.
 * But, in the test, we will use '\UniAlteri\Tests\Support\MockStartupFactory' to unit testing only the proxy.
 *
 * This extends support implements also all supported standard interface to tests implementation provided by the trait Proxy.
 * To avoid errors in the usage of this lib, these interfaces are not defined with released proxies.
 * You must implement these interface, according to your needs, in your derived proxies like in this class.
 *
 * @package     States
 * @subpackage  StatesBundle
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @version     0.9.9
 */
class IntegratedEntity extends AbstractIntegratedEntity implements
    \Serializable,
    \ArrayAccess,
    \SeekableIterator,
    \Countable
{
    /**
     * Class name of the factory to use during set up to initialize this object.
     * It is a virtual factory, it does nothing except logs actions
     * @var string
     */
    protected static $_startupFactoryClassName = '\UniAlteri\Tests\Support\MockStartupFactory';

    /**
     * Method to update static::$_startupFactoryClassName to run some unit tests
     * @param string $className
     */
    public static function defineStartupFactoryClassName($className)
    {
        static::$_startupFactoryClassName = $className;
    }
}