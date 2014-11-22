<?php

namespace UniAlteri\Bundle\StatesBundle\Doctrine\Persistence\Mapping\Driver;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriver;
use Doctrine\Common\Persistence\Mapping\Driver\AnnotationDriver;

class StateDriver extends AnnotationDriver implements MappingDriver
{
    /**
     * {@inheritDoc}
     */
    public function getAllClassNames()
    {
        $loadedClassesList = \get_declared_classes();

        foreach ($loadedClassesList as $loadedClassName) {
            $interfacesList = class_implements($loadedClassName);
            $interfacesList = array_flip($interfacesList);
            if (isset($interfacesList['\UniAlteri\States\Proxy\ProxyInterface'])) {
                $classParts = explode('\\', $loadedClassName);
                array_pop($classParts);
                $classAliasName = implode('\\', $classParts);
                if (!class_exists($classAliasName, false)) {
                    class_alias($loadedClassName, $classAliasName);
                }
            }
        }
    }
}