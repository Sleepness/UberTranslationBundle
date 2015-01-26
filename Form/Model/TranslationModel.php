<?php

namespace Sleepness\UberTranslationBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Model class for wrapping translation message, into
 */
class TranslationModel
{
    /**
     * @Assert\NotBlank()
     */
    private $translation;

    /**
     * @param $translation
     * @return $this
     */
    public function setTranslation($translation)
    {
        $this->translation = $translation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTranslation()
    {
        return $this->translation;
    }
} 
