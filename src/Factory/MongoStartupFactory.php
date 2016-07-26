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
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
namespace Teknoo\Bundle\StatesBundle\Factory;

use Doctrine\ODM\MongoDB\Proxy\Proxy;
use Teknoo\States\Factory\Exception\UnavailableFactory;
use Teknoo\States\Proxy\ProxyInterface;

/**
 * Class StartupFactory
 * Extends of \Teknoo\States\Factory\StandardStartupFactory to support Doctrine proxy.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @api
 */
class MongoStartupFactory extends StartupFactory
{
    /**
     * {@inheritdoc}
     */
    public static function forwardStartup(ProxyInterface $proxyObject, string $stateName = null): \Teknoo\States\Factory\FactoryInterface
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
