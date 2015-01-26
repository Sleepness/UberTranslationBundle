<?php

namespace Sleepness\UberTranslationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('translation', 'text', array(
            'label' => 'Tranlsation',
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sleepness\UberTranslationBundle',
        ));
    }

    public function getName()
    {
        return 'translation_form';
    }
} 
