<?php

namespace Kunstmaan\LeadGenerationBundle\Tests\Entity\Rule;

use Kunstmaan\LeadGenerationBundle\Entity\Rule\AfterXScrollPercentRule;
use Kunstmaan\LeadGenerationBundle\Form\Rule\AfterXScrollPercentRuleAdminType;
use PHPUnit_Framework_TestCase;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-09-04 at 16:54:04.
 */
class AfterXScrollPercentRuleTest extends PHPUnit_Framework_TestCase
{
    public function testGettersAndSetters()
    {
        $rule = new AfterXScrollPercentRule();
        $rule->setPercentage(10);

        $this->assertEquals(10, $rule->getPercentage());
        $this->assertEquals('AfterXScrollPercentRule', $rule->getJsObjectClass());
        $this->assertEquals('/bundles/kunstmaanleadgeneration/js/rule/AfterXScrollPercentRule.js', $rule->getJsFilePath());
        $this->assertEquals(AfterXScrollPercentRuleAdminType::class, $rule->getAdminType());
        $this->assertTrue(is_array($rule->getJsProperties()));
    }
}
