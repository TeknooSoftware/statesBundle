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
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 */

namespace Teknoo\Tests\Bundle\StatesBundle;

use Teknoo\Bundle\StatesBundle\Factory;
use Teknoo\States\Factory\Exception;
use Teknoo\Tests\Bundle\StatesBundle\Support\MongoMockProxy;
use Teknoo\Tests\Support;

/**
 * Class MongoStartupFactoryTest.
 *
 * @copyright   Copyright (c) 2009-2016 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2016 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.software/states Project website
 *
 * @license     http://teknoo.software/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @covers Teknoo\Bundle\StatesBundle\Factory\MongoStartupFactory
 */
class MongoStartupFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepare test, reinitialize the StartupFactory.
     */
    protected function setUp()
    {
        Factory\MongoStartupFactory::reset();
        parent::setUp();
    }

    /**
     * The startup factory must throw an exception when the proxy cannot be initialized.
     */
    public function testForwardStartupProxyNotInitialized()
    {
        try {
            Factory\MongoStartupFactory::forwardStartup(new Support\MockProxy(null));
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
        Factory\MongoStartupFactory::registerFactory('Teknoo\Tests\Support\MockProxy', $factory);
        $proxy = new Support\MockProxy(null);
        Factory\MongoStartupFactory::forwardStartup($proxy);
        $this->assertSame($factory->getStartupProxy(), $proxy);
    }

    /**
     * Test normal behavior of forward startup.
     */
    public function testForwardStartupFromProxy()
    {
        $factory = new Support\MockFactory('My\Stated\Class', new Support\MockFinder('My\Stated\Class', 'path/to/class'), new \ArrayObject());
        Factory\MongoStartupFactory::registerFactory('Teknoo\Tests\Support\MockProxy', $factory);
        $proxy = new MongoMockProxy(null);
        Factory\MongoStartupFactory::forwardStartup($proxy);
        $this->assertSame($factory->getStartupProxy(), $proxy);
    }

    /**
     * Test Factory\MongoStartupFactory::listRegisteredFactory if its return all initialized factory.
     */
    public function testListRegisteredFactory()
    {
        $factory = new Support\MockFactory('My\Stated\Class', new Support\MockFinder('My\Stated\Class', 'path/to/class'), new \ArrayObject());
        Factory\MongoStartupFactory::registerFactory('Teknoo\Tests\Support\MockProxy1', $factory);
        Factory\MongoStartupFactory::reset();
        Factory\MongoStartupFactory::registerFactory('Teknoo\Tests\Support\MockProxy2', $factory);
        Factory\MongoStartupFactory::registerFactory('Teknoo\Tests\Support\MockProxy3', $factory);
        $this->assertEquals(
            array(
                'Teknoo\Tests\Support\MockProxy2',
                'Teknoo\Tests\Support\MockProxy3',
            ),
            Factory\MongoStartupFactory::listRegisteredFactory()
        );
    }

    /**
     * Test Factory\MongoStartupFactory::listRegisteredFactory if its return all initialized factory.
     */
    public function testListRegisteredFactoryEmpty()
    {
        Factory\MongoStartupFactory::reset();
        $this->assertEquals(
            array(),
            Factory\MongoStartupFactory::listRegisteredFactory()
        );
    }
}
