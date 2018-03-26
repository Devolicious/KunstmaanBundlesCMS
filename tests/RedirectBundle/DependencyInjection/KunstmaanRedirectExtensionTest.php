<?php

namespace Tests\Kunstmaan\RedirectBundle\DependencyInjection;

use Kunstmaan\RedirectBundle\DependencyInjection\KunstmaanRedirectExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Tests\Kunstmaan\AbstractPrependableExtensionTestCase;

/**
 * Class KunstmaanRedirectExtensionTest
 * @package Tests\Kunstmaan\RedirectBundle\DependencyInjection
 */
class KunstmaanRedirectExtensionTest extends AbstractPrependableExtensionTestCase
{
    /**
     * @return ExtensionInterface[]
     */
    protected function getContainerExtensions()
    {
        return [new KunstmaanRedirectExtension()];
    }


    public function testCorrectParametersHaveBeenSet()
    {
        $this->container->setParameter('empty_extension', true);
        $this->load();

        $this->assertContainerBuilderHasParameter('empty_extension', true );
    }
}