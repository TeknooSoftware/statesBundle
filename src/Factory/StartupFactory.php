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

namespace UniAlteri\States\Factory;

use UniAlteri\States\Proxy;
use UniAlteri\States\Factory;

/**
 * Class StartupFactory
 * Extends of \UniAlteri\States\Factory\StandardStartupFactory to support Doctrine proxy
 *
 * @package     States
 * @subpackage  StatesBundle
 * @copyright   Copyright (c) 2009-2014 Uni Alteri (http://agence.net.ua)
 * @link        http://teknoo.it/states Project website
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 * @api
 */
class StartupFactory extends Factory\StandardStartupFactory
{
    /**
     * Registry of factory to use to initialize proxy object
     * @var FactoryInterface[]|\ArrayObject
     */
    protected static $_factoryRegistry = null;

    /**
     * To find the factory to use for the new proxy object to initialize it with its container and states.
     * This method is called by the constructor of the stated object
     * @param  Proxy\ProxyInterface         $proxyObject
     * @param  string                       $stateName
     * @return boolean
     * @throws Exception\InvalidArgument    when $factoryIdentifier is not an object
     * @throws Exception\UnavailableFactory when the required factory was not found
     */
    public static function forwardStartup($proxyObject, $stateName = null)
    {
        if (!$proxyObject instanceof Proxy\ProxyInterface) {
            throw new Exception\InvalidArgument('Error the proxy does not implement the Proxy\ProxyInterface');
        }

        $factoryIdentifier = null;
        if ($proxyObject instanceof \Doctrine\ORM\Proxy\Proxy) {
            $factoryIdentifier = get_parent_class($proxyObject);
        } else {
            $factoryIdentifier = get_class($proxyObject);
        }

        if (!static::$_factoryRegistry instanceof \ArrayObject || !isset(static::$_factoryRegistry[$factoryIdentifier])) {
            throw new Exception\UnavailableFactory('Error, the factory "'.$factoryIdentifier.'" is not available');
        }

        return static::$_factoryRegistry[$factoryIdentifier]->startup($proxyObject, $stateName);
    }
}
