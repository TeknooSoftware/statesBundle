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

namespace Teknoo\Tests\Bundle\StatesBundle;

use Teknoo\Bundle\StatesBundle\Factory;
use Teknoo\States\Factory\Exception;
use Teknoo\Tests\Bundle\StatesBundle\Support\DoctrineMockProxy;
use Teknoo\Tests\Support;

/**
 * Class StartupFactoryTest.
 *
 *
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (richarddeloge@gmail.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <richarddeloge@gmail.com
 *
 * @covers Teknoo\Bundle\StatesBundle\Factory\StartupFactory
 */
class StartupFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepare test, reinitialize the StartupFactory.
     */
    protected function setUp()
    {
        Factory\StartupFactory::reset();
        parent::setUp();
    }

    /**
     * The startup factory must throw an exception when the proxy cannot be initialized.
     */
    public function testForwardStartupProxyNotInitialized()
    {
        try {
            Factory\StartupFactory::forwardStartup(new Support\MockProxy(null));
        } catch (Exception\UnavailableFactory $e) {
            return;
        } catch (\Exception $e) {
        }

        $this->fail('Error, the startup factory must throw an exception when the proxy cannot be initialized');
    }

    /**
     * Test normal behavior of forward startup.
     */
    public function testForwardStartup()
    {
        $factory = new Support\MockFactory('My\Stated\Class', new Support\MockFinder('My\Stated\Class', 'path/to/class'), new \ArrayObject());
        Factory\StartupFactory::registerFactory('Teknoo\Tests\Support\MockProxy', $factory);
        $proxy = new Support\MockProxy(null);
        Factory\StartupFactory::forwardStartup($proxy);
        $this->assertSame($factory->getStartupProxy(), $proxy);
    }

    /**
     * Test normal behavior of forward startup.
     */
    public function testForwardStartupFromProxy()
    {
        $factory = new Support\MockFactory('My\Stated\Class', new Support\MockFinder('My\Stated\Class', 'path/to/class'), new \ArrayObject());
        Factory\StartupFactory::registerFactory('Teknoo\Tests\Support\MockProxy', $factory);
        $proxy = new DoctrineMockProxy(null);
        Factory\StartupFactory::forwardStartup($proxy);
        $this->assertSame($factory->getStartupProxy(), $proxy);
    }

    /**
     * Test Factory\StartupFactory::listRegisteredFactory if its return all initialized factory.
     */
    public function testListRegisteredFactory()
    {
        $factory = new Support\MockFactory('My\Stated\Class', new Support\MockFinder('My\Stated\Class', 'path/to/class'), new \ArrayObject());
        Factory\StartupFactory::registerFactory('Teknoo\Tests\Support\MockProxy1', $factory);
        Factory\StartupFactory::reset();
        Factory\StartupFactory::registerFactory('Teknoo\Tests\Support\MockProxy2', $factory);
        Factory\StartupFactory::registerFactory('Teknoo\Tests\Support\MockProxy3', $factory);
        $this->assertEquals(
            array(
                'Teknoo\Tests\Support\MockProxy2',
                'Teknoo\Tests\Support\MockProxy3',
            ),
            Factory\StartupFactory::listRegisteredFactory()
        );
    }

    /**
     * Test Factory\StartupFactory::listRegisteredFactory if its return all initialized factory.
     */
    public function testListRegisteredFactoryEmpty()
    {
        Factory\StartupFactory::reset();
        $this->assertEquals(
            array(),
            Factory\StartupFactory::listRegisteredFactory()
        );
    }
}
