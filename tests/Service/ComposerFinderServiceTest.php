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

namespace Teknoo\Tests\Bundle\StatesBundle\Service;

use Composer\Autoload\ClassLoader;
use Teknoo\Bundle\StatesBundle\Service\ComposerFinderService;

/**
 * Class ComposerFinderServiceTest.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com>
 *
 * @covers Teknoo\Bundle\StatesBundle\Service\ComposerFinderService
 */
class ComposerFinderServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return ComposerFinderService
     */
    public function buildFinder()
    {
        return new ComposerFinderService();
    }

    public function testGetComposerInstance()
    {
        $this->assertInstanceOf(
            'Composer\Autoload\ClassLoader',
            $this->buildFinder()->getComposerInstance()
        );
    }

    public function testGetComposerInstanceWithDebugClassLoader()
    {
        if (!class_exists('Symfony\Component\Debug\DebugClassLoader')) {
            $this->markTestSkipped('DebugClassLoader is not available');
            return $this;
        }

        //Fake autoload method to simulate an not empty autoload stack
        spl_autoload_register(function ($className) {return false;});

        //Remove autoloader
        $autoloadCallbackList = \spl_autoload_functions();

        $composerAutoloaderCallback = null;
        $debugClassLoader = $this->getMock('Symfony\Component\Debug\DebugClassLoader', [], [], '', false);
        if (!empty($autoloadCallbackList)) {
            foreach ($autoloadCallbackList as $autoloadCallback) {
                if (is_array($autoloadCallback) && isset($autoloadCallback[0])
                    && ($autoloadCallback[0] instanceof ClassLoader)
                ) {
                    $classLoader = $autoloadCallback[0];
                    $composerAutoloaderCallback = $autoloadCallback;
                    spl_autoload_unregister($autoloadCallback);

                    $debugClassLoader->expects($this->any())->method('getClassLoader')->willReturn($autoloadCallback);
                    $debugClassLoader->expects($this->any())->method('loadClass')->willReturnCallback(function ($className) use ($classLoader) {
                        $classLoader->loadClass($className);
                    });
                    spl_autoload_register([$debugClassLoader, 'loadClass']);
                }
            }
        }

        $this->assertInstanceOf(
            'Composer\Autoload\ClassLoader',
            $this->buildFinder()->getComposerInstance()
        );

        if (is_callable($composerAutoloaderCallback)) {
            spl_autoload_register($composerAutoloaderCallback, true, true);
            spl_autoload_unregister([$debugClassLoader, 'loadClass']);
        }
    }

    public function testGetComposerInstanceWithoutComposer()
    {
        //Fake autoload method to simulate an not empty autoload stack
        spl_autoload_register(function ($className) {return false;});

        //Remove autoloader
        $autoloadCallbackList = \spl_autoload_functions();

        $composerAutoloaderCallback = null;
        if (!empty($autoloadCallbackList)) {
            foreach ($autoloadCallbackList as $autoloadCallback) {
                if (is_array($autoloadCallback) && isset($autoloadCallback[0])
                    && ($autoloadCallback[0] instanceof ClassLoader)
                ) {
                    $composerAutoloaderCallback = $autoloadCallback;
                    spl_autoload_unregister($autoloadCallback);
                }
            }
        }

        try {
            $this->buildFinder()->getComposerInstance();
        } catch (\RuntimeException $e) {
            if (is_callable($composerAutoloaderCallback)) {
                spl_autoload_register($composerAutoloaderCallback, true, true);
            }

            return;
        } catch (\Exception $e) { /* ... */
        }

        $this->fail('Error, the method getComposerInstance() must throw an exception when the Composer Loader is not available');

        if (is_callable($composerAutoloaderCallback)) {
            spl_autoload_register($composerAutoloaderCallback, true, true);
        }
    }
}
