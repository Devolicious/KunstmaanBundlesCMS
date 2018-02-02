<?php

namespace Tests\Kunstmaan\FormBundle\Entity;

use Kunstmaan\FormBundle\Entity\PageParts\ChoicePagePart;
use Kunstmaan\FormBundle\Form\ChoicePagePartAdminType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class ChoiceFormSubmissionTypeTest
 */
class ChoicePagePartAdminTypeTest extends TypeTestCase
{
    public function testFormType()
    {
        $formData = [
            'choices' => 'hello',
        ];

        $form = $this->factory->create(ChoicePagePartAdminType::class);

        $object = new ChoicePagePart();
        $object->setChoices('hello');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());

        $this->assertEquals($object, $form->getData());
    }
}
