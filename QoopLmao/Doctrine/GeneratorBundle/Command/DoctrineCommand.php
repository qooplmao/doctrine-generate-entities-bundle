<?php

namespace QoopLmao\Doctrine\GeneratorBundle\Command;

use QoopLmao\Doctrine\GeneratorBundle\Generator\InterfaceGenerator;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use QoopLmao\Doctrine\GeneratorBundle\Generator\EntityGenerator;

/**
 * Base class for Doctrine console commands to extend from.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
abstract class DoctrineCommand extends ContainerAwareCommand
{
    /**
     * get a doctrine entity generator
     *
     * @return EntityGenerator
     */
    protected function getEntityGenerator()
    {
        $entityGenerator = new EntityGenerator();
        $entityGenerator->setGenerateAnnotations(false);
        $entityGenerator->setGenerateStubMethods(true);
        $entityGenerator->setRegenerateEntityIfExists(false);
        $entityGenerator->setUpdateEntityIfExists(true);
        $entityGenerator->setNumSpaces(4);
        $entityGenerator->setAnnotationPrefix('ORM\\');

        return $entityGenerator;
    }
    /**
     * get a doctrine interface generator
     *
     * @return InterfaceGenerator
     */
    protected function getInterfaceGenerator()
    {
        $entityGenerator = new InterfaceGenerator();
        $entityGenerator->setGenerateAnnotations(false);
        $entityGenerator->setGenerateStubMethods(true);
        $entityGenerator->setRegenerateEntityIfExists(false);
        $entityGenerator->setUpdateEntityIfExists(true);
        $entityGenerator->setNumSpaces(4);
        $entityGenerator->setAnnotationPrefix('ORM\\');

        return $entityGenerator;
    }

    /**
     * Get a doctrine entity manager by symfony name.
     *
     * @param string $name
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager($name)
    {
        return $this->getContainer()->get('doctrine')->getManager($name);
    }

    /**
     * Get a doctrine dbal connection by symfony name.
     *
     * @param string $name
     *
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDoctrineConnection($name)
    {
        return $this->getContainer()->get('doctrine')->getConnection($name);
    }
}
