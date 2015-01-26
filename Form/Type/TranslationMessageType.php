<?php

namespace Sleepness\UberTranslationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class for translation message form edit
 */
class TranslationMessageType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('translation', 'text', array(
            'label' => 'Tranlsation',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sleepness\UberTranslationBundle',
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'translation_form';
    }
} 
