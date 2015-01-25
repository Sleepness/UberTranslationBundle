<?php

namespace Sleepness\UberTranslationBundle\Controller;

use Sleepness\UberTranslationBundle\Translation\MemcachedMessageCatalogue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TranslationController extends Controller
{
    public function indexAction()
    {
        $messageCatalogue = new MemcachedMessageCatalogue();
        $mem = $this->get('uber.memcached');
        $locales = array('uk', 'en');
        foreach ($locales as $key => $locale) {
            $translations = $mem->getItem($locale);
            $messageCatalogue->add($locale, $translations);
        }
        $messages = $messageCatalogue->getAll();

        return $this->render('SleepnessUberTranslationBundle:Translation:index.html.twig', array(
            'messages' => $messages,
        ));
    }
}
