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

use UniAlteri\States\Proxy\ProxyTrait;
use UniAlteri\States\Proxy\IntegratedTrait as ProxyIntegratedTrait;

/**
 * Trait IntegratedTrait
 * Trait adapt integrated proxies to doctrine.
 *
 * @copyright   Copyright (c) 2009-2016 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
trait IntegratedTrait
{
    use ProxyTrait,
        ProxyIntegratedTrait;

    /**
     * Doctrine does not call the construction and create a new instance without it....
     * This callback reinitialize proxy.
     *
     * @ORM\PostLoad()
     */
    public function postLoadDoctrine()
    {
        //Call the method of the trait to initialize local attributes of the proxy
        $this->initializeProxy();
        //Call the startup factory to initialize this proxy
        $this->initializeObjectWithFactory();
        //Select good state
        $this->updateState();
    }

    /**
     * Callback to extends in your entity to apply states according to your entity's value.
     *
     * @return $this
     */
    public function updateState()
    {
        return $this;
    }
}
