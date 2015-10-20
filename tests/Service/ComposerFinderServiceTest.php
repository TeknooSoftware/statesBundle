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

namespace UniAlteri\Tests\Bundle\StatesBundle\Service;

use UniAlteri\Bundle\StatesBundle\Service\ComposerFinderService;

/**
 * Class ComposerFinderServiceTest.
 *
 * @copyright   Copyright (c) 2009-2015 Uni Alteri (http://uni-alteri.com)
 * @copyright   Copyright (c) 2009-2015 Richard Déloge (r.deloge@uni-alteri.com)
 *
 * @link        http://teknoo.it/states Project website
 *
 * @license     http://teknoo.it/license/mit         MIT License
 * @author      Richard Déloge <r.deloge@uni-alteri.com>
 *
 * @covers UniAlteri\Bundle\StatesBundle\Service\ComposerFinderService
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
}
