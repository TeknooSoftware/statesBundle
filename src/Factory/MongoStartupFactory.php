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

use Doctrine\ODM\MongoDB\Proxy\Proxy;
use UniAlteri\States\Factory\Exception\UnavailableFactory;
use UniAlteri\States\Proxy\ProxyInterface;

/**
 * Class StartupFactory
 * Extends of \UniAlteri\States\Factory\StandardStartupFactory to support Doctrine proxy.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @license     http://teknoo.it/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @api
 */
class MongoStartupFactory extends StartupFactory
{
    /**
     * {@inheritdoc}
     */
    public static function forwardStartup(ProxyInterface $proxyObject, \string $stateName = null): \UniAlteri\States\Factory\FactoryInterface
    {
        //If the entity object if a doctrine proxy, retrieve the proxy class name from its parent
        $factoryIdentifier = null;
        if ($proxyObject instanceof Proxy) {
            $factoryIdentifier = get_parent_class($proxyObject);
        } else {
            //Normal behavior
            $factoryIdentifier = get_class($proxyObject);
        }

        if (!static::$factoryRegistry instanceof \ArrayAccess || !isset(static::$factoryRegistry[$factoryIdentifier])) {
            throw new UnavailableFactory(
                sprintf('Error, the factory "%s" is not available', $factoryIdentifier)
            );
        }

        return static::$factoryRegistry[$factoryIdentifier]->startup($proxyObject, $stateName);
    }
}
