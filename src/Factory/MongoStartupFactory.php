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
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace UniAlteri\Bundle\StatesBundle\Factory;

use UniAlteri\States\Proxy;
use UniAlteri\States\Factory;
use UniAlteri\States\Factory\Exception;

/**
 * Class StartupFactory
 * Extends of \UniAlteri\States\Factory\StandardStartupFactory to support Doctrine proxy.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @api
 */
class MongoStartupFactory extends StartupFactory
{
    /**
     * To find the factory to use for the new proxy object to initialize it with its container and states.
     * This method is called by the constructor of the stated object.
     *
     * @param Proxy\ProxyInterface $proxyObject
     * @param string               $stateName
     *
     * @return bool
     *
     * @throws Exception\InvalidArgument    when $factoryIdentifier is not an object
     * @throws Exception\UnavailableFactory when the required factory was not found
     */
    public static function forwardStartup($proxyObject, $stateName = null)
    {
        if (!$proxyObject instanceof Proxy\ProxyInterface) {
            throw new Exception\InvalidArgument('Error the proxy does not implement the Proxy\ProxyInterface');
        }

        //If the entity object if a doctrine proxy, retrieve the proxy class name from its parent
        $factoryIdentifier = null;
        if ($proxyObject instanceof \Doctrine\ODM\MongoDB\Proxy\Proxy) {
            $factoryIdentifier = get_parent_class($proxyObject);
        } else {
            //Normal behavior
            $factoryIdentifier = get_class($proxyObject);
        }

        if (!static::$factoryRegistry instanceof \ArrayObject || !isset(static::$factoryRegistry[$factoryIdentifier])) {
            //Stated class has been partially loaded by doctrine, finish to load it
            self::reloadStatedClass($factoryIdentifier);
        }

        if (!static::$factoryRegistry instanceof \ArrayObject || !isset(static::$factoryRegistry[$factoryIdentifier])) {
            //we can not found definitely the factory for this stated class
            throw new Exception\UnavailableFactory(
                sprintf('Error, the factory "%s" is not available', $factoryIdentifier)
            );
        }

        return static::$factoryRegistry[$factoryIdentifier]->startup($proxyObject, $stateName);
    }
}