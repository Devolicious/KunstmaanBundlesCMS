<?php

namespace Tests\Kunstmaan\MediaPagePartBundle\DependencyInjection;

use Kunstmaan\MediaPagePartBundle\DependencyInjection\KunstmaanMediaPagePartExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Class KunstmaanMediaPagePartExtensionTest
 */
class KunstmaanMediaPagePartExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @return ExtensionInterface[]
     */
    protected function getContainerExtensions()
    {
        return [new KunstmaanMediaPagePartExtension()];
    }


    public function testCorrectParametersHaveBeenSet()
    {
        $this->container->setParameter('empty_extension', true);
        $this->load();

        $this->assertContainerBuilderHasParameter('empty_extension', true);
    }
}
