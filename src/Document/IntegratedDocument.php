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

namespace UniAlteri\Bundle\StatesBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use UniAlteri\States\Proxy\Exception\IllegalFactory;
use UniAlteri\States\Proxy\Exception\UnavailableFactory;
use UniAlteri\States\Proxy\IntegratedInterface;
use UniAlteri\States\Proxy\ProxyInterface;

/**
 * Class IntegratedDocument.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://agence.net.ua)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/states/license/mit         MIT License
 * @license     http://teknoo.it/states/license/gpl-3.0     GPL v3 License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @MongoDB\MappedSuperclass
 * @MongoDB\HasLifecycleCallbacks
 */
abstract class IntegratedDocument implements ProxyInterface, IntegratedInterface
{
    use IntegratedTrait;

    /**
     * Class name of the factory to use in set up to initialize this object in this construction.
     *
     * @var string
     */
    protected static $startupFactoryClassName = '\UniAlteri\Bundle\StatesBundle\Factory\MongoStartupFactory';

    /**
     * Default constructor used to initialize the stated object with its factory.
     *
     * @throws IllegalFactory
     * @throws UnavailableFactory
     */
    public function __construct()
    {
        $this->postLoadDoctrine();
    }
}
