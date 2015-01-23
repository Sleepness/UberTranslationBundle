<?php

namespace Sleepness\UberTranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TranslationController extends Controller
{
    public function indexAction()
    {
        return $this->render('SleepnessUberTranslationBundle:Translation:index.html.twig');
    }
} 
