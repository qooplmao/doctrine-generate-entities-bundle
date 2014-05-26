<?php

namespace QoopLmao\Doctrine\GeneratorBundle\Generator;

use Doctrine\ORM\Mapping\ClassMetadataInfo,
    Doctrine\Common\Util\Inflector,
    Doctrine\DBAL\Types\Type;

/**
 * Generic class used to generate PHP5 entity classes from ClassMetadataInfo instances
 *
 *     [php]
 *     $classes = $em->getClassMetadataFactory()->getAllMetadata();
 *
 *     $generator = new \Doctrine\ORM\Tools\EntityGenerator();
 *     $generator->setGenerateAnnotations(true);
 *     $generator->setGenerateStubMethods(true);
 *     $generator->setRegenerateEntityIfExists(false);
 *     $generator->setUpdateEntityIfExists(true);
 *     $generator->generate($classes, '/path/to/generate/entities');
 *
 *
 * @link    www.doctrine-project.org
 * @since   2.0
 * @author  Benjamin Eberlei <kontakt@beberlei.de>
 * @author  Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author  Jonathan Wage <jonwage@gmail.com>
 * @author  Roman Borschel <roman@code-factory.org>
 */
class InterfaceGenerator extends EntityGenerator
{
    /**
     * @var string
     */
    protected $classTemplate =
'<?php

<namespace>

use Doctrine\Common\Collections\ArrayCollection;

<entityClassName>
{
<entityBody>
}
';

    /**
     * @var string
     */
    protected $getMethodTemplate =
'/**
 * <description>
 *
 * @return <variableType>
 */
public function <methodName>();';

    /**
     * @var string
     */
    protected $setMethodTemplate =
'/**
 * <description>
 *
 * @param <variableType>$<variableName>
 * @return $this
 */
public function <methodName>(<methodTypeHint>$<variableName><variableDefault>);';

    /**
     * @var string
     */
    protected $hasMethodTemplate =
'/**
 * <description>
 *
 * @param <variableType>$<variableNameSingular>
 * @return boolean
 */
public function <methodName>(<methodTypeHint>$<variableNameSingular>);';

    /**
     * @var string
     */
    protected $addMethodTemplate =
'/**
 * <description>
 *
 * @param <variableType>$<variableNameSingular>
 * @return $this
 */
public function <methodName>(<methodTypeHint>$<variableNameSingular>);';

    /**
     * @var string
     */
    protected $removeMethodTemplate =
'/**
 * <description>
 *
 * @param <variableType>$<variableNameSingular>
 * @return $this
 */
public function <methodName>(<methodTypeHint>$<variableNameSingular>);';

    /**
     * @var string
     */
    protected $lifecycleCallbackMethodTemplate =
'/**
 * @<name>
 */
public function <methodName>();';

    /**
     * @var string
     */
    protected $constructorMethodTemplate =
'/**
 * Constructor
 */
public function __construct()
{
<spaces><collections>
}
';

    /**
     * Generated and write entity class to disk for the given ClassMetadataInfo instance
     *
     * @param ClassMetadataInfo $metadata
     * @param string $outputDirectory
     * @return void
     */
    public function writeEntityClass(ClassMetadataInfo $metadata, $outputDirectory)
    {
        $path = $outputDirectory . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $metadata->name) . 'Interface' . $this->extension;
        $dir = dirname($path);

        if ( ! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $this->isNew = !file_exists($path) || (file_exists($path) && $this->regenerateEntityIfExists);

        if ( ! $this->isNew) {
            $this->parseTokensInEntityFile(file_get_contents($path));
        } else {
            $this->staticReflection[$metadata->name] = array('properties' => array(), 'methods' => array());
        }

        if ($this->backupExisting && file_exists($path)) {
            $backupPath = dirname($path) . DIRECTORY_SEPARATOR . basename($path) . "~";
            if (!copy($path, $backupPath)) {
                throw new \RuntimeException("Attempt to backup overwritten entity file but copy operation failed.");
            }
        }

        // If entity doesn't exist or we're re-generating the entities entirely
        if ($this->isNew) {
            file_put_contents($path, $this->generateEntityClass($metadata));
            // If entity exists and we're allowed to update the entity class
        } else if ( ! $this->isNew && $this->updateEntityIfExists) {
            file_put_contents($path, $this->generateUpdatedEntityClass($metadata, $path));
        }
    }

    protected function generateEntityClassName(ClassMetadataInfo $metadata)
    {
        return 'interface ' . $this->getClassName($metadata) . 'Interface' .
        ($this->extendsClass() ? ' extends ' . $this->getClassToExtendName() : null);
    }
}
