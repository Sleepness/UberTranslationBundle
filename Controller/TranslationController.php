<?php

namespace Sleepness\UberTranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TranslationController extends Controller
{
    public function indexAction()
    {
        $memcached = $this->get('uber.memcached');
        $messages = $memcached->getItem('en');

        return $this->render('SleepnessUberTranslationBundle:Translation:index.html.twig', array(
            'messages' => $messages,
        ));
    }
} 
