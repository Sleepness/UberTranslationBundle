<?php

namespace Sleepness\UberTranslationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TranslationMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('translation', 'text');
    }

    public function getName()
    {
        return 'translation_form';
    }
} 
