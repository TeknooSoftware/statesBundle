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
 * @copyright   Copyright (c) 2009-2016 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace UniAlteri\Bundle\StatesBundle\Entity;

use UniAlteri\States\Proxy\Exception\IllegalFactory;
use UniAlteri\States\Proxy\Exception\UnavailableFactory;
use Doctrine\ORM\Mapping as ORM;
use UniAlteri\States\Proxy\IntegratedInterface;
use UniAlteri\States\Proxy\ProxyInterface;

/**
 * Class IntegratedEntity.
 * Default Stated class implementation with a doctrine entity class.
 *
 * @copyright   Copyright (c) 2009-2016 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 */
abstract class IntegratedEntity implements ProxyInterface, IntegratedInterface
{
    use IntegratedTrait;

    /**
     * Class name of the factory to use in set up to initialize this object in this construction.
     *
     * @var string
     */
    protected static $startupFactoryClassName = '\UniAlteri\Bundle\StatesBundle\Factory\StartupFactory';

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
