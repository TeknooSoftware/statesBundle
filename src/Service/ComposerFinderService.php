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
 * @copyright   Copyright (c) 2009-2015 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace UniAlteri\Bundle\StatesBundle\Service;

use Composer\Autoload\ClassLoader;
use Symfony\Component\Debug\DebugClassLoader;

/**
 * Class ComposerFinderService
 * Service to find from the spl autoloader stack the composer instance.
 * 
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2015 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */
class ComposerFinderService
{
    /**
     * @var ClassLoader
     */
    protected $composerInstance;

    /**
     * To retrieve the composer loader instance from the __autoload stack with PHP's spl function.
     *
     * @return ClassLoader|DebugClassLoader
     */
    protected function findComposerInstance()
    {
        $autoloadCallbackList = \spl_autoload_functions();

        if (!empty($autoloadCallbackList)) {
            foreach ($autoloadCallbackList as $autoloadCallback) {
                if (is_array($autoloadCallback) && isset($autoloadCallback[0])) {
                    if ($autoloadCallback[0] instanceof ClassLoader) {
                        return $autoloadCallback[0];
                    }

                    if ($autoloadCallback[0] instanceof DebugClassLoader) {
                        $classLoader = $autoloadCallback[0]->getClassLoader();
                        if (is_array($classLoader) && $classLoader[0] instanceof ClassLoader) {
                            return $classLoader[0];
                        }
                    }
                }
            }
        }

        throw new \RuntimeException('Error, the Composer loader component is not available');
    }

    /**
     * To find the composer from \spl_autoload stacks.
     *
     * @return ClassLoader|DebugClassLoader
     */
    public function getComposerInstance()
    {
        if (!$this->composerInstance instanceof ClassLoader) {
            $this->composerInstance = $this->findComposerInstance();
        }

        return $this->composerInstance;
    }
}
