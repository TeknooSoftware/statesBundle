<?php

namespace UniAlteri\Tests\Bundle\StatesBundle\DependencyInjection;

use UniAlteri\Bundle\StatesBundle\DependencyInjection\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return Configuration
     */
    public function buildConfiguration()
    {
        return new Configuration();
    }

    public function testGetConfigTreeBuilder()
    {
        $configuration = $this->buildConfiguration();
        $treeBuilder = $configuration->getConfigTreeBuilder();

        $this->assertInstanceOf('Symfony\Component\Config\Definition\Builder\TreeBuilder', $treeBuilder);
    }
}