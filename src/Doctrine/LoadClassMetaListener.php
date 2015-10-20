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

namespace UniAlteri\Bundle\StatesBundle\Doctrine;

use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use UniAlteri\States\Loader\LoaderInterface;

/**
 * Class LoadClassMetaListener
 * Class to implement a doctrine listener on classmetadata event to check if the current stated class
 * manipulate by doctrine is fully loaded.
 *
 * @copyright   Copyright (c) 2009-2016 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class LoadClassMetaListener
{
    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @param LoaderInterface $loader
     * @return $this
     */
    public function registerLoader(LoaderInterface $loader)
    {
        $this->loader = $loader;

        return $this;
    }

    /**
     * @return LoaderInterface
     */
    public function getLoader(): LoaderInterface
    {
        return $this->loader;
    }

    /**
     * Notified by doctine of the event, retrieve the stated class name and check load with the
     * States library's loader.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        if ($this->loader instanceof LoaderInterface) {
            $classMeta = $eventArgs->getClassMetadata();
            $canonicalClassName = $classMeta->getName();
            $this->loader->loadClass($canonicalClassName);
        }
    }
}
