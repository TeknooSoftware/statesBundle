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
 * to richarddeloge@gmail.com so we can send you a copy immediately.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
namespace Teknoo\Bundle\StatesBundle\Document;

use Teknoo\States\Proxy\ProxyTrait;
use Teknoo\States\Proxy\IntegratedTrait as ProxyIntegratedTrait;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Trait IntegratedTrait
 * Trait adapt integrated proxies to doctrine.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 */
trait IntegratedTrait
{
    use ProxyTrait,
        ProxyIntegratedTrait;

    /**
     * Doctrine does not call the construction and create a new instance without it....
     * This callback reinitialize proxy.
     *
     * @MongoDB\PostLoad()
     */
    public function postLoadDoctrine()
    {
        //Call the method of the trait to initialize local attributes of the proxy
        $this->initializeProxy();
        //Call the startup factory to initialize this proxy
        $this->initializeObjectWithFactory();
        //Update states
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
