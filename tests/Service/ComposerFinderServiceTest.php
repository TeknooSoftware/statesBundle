<?php

namespace UniAlteri\Tests\Bundle\StatesBundle\Service;

use UniAlteri\Bundle\StatesBundle\Service\ComposerFinderService;

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