<?php

namespace Sleepness\UberTranslationBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

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
