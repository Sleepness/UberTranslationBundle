<?php

namespace Sleepness\UberTranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TranslationController extends Controller
{
    public function indexAction()
    {
        $memcached = $this->get('uber.memcached');

        $messagesEN = $memcached->getItem('en');
        $messagesUk = $memcached->getItem('uk');
        $messages = array_merge_recursive($messagesEN, $messagesUk);

        return $this->render('SleepnessUberTranslationBundle:Translation:index.html.twig', array(
            'messages' => $messages,
        ));
    }
}
