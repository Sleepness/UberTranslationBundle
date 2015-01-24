<?php

namespace Sleepness\UberTranslationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TranslationController extends Controller
{
    public function indexAction()
    {
        $mem = $this->get('uber.memcached');
        $locales = array('uk', 'en');
        $preparedTranslations = array();
        foreach ($locales as $key => $locale) {
            $translations = $mem->getItem($locale);
            foreach ($translations as $domain => $messages) {
                foreach ($messages as $key => $message) {
                    $preparedTranslations[] = array(
                        'domain' => $domain,
                        'key' => $key,
                        'message' => array(
                            'messageText' => $message,
                            'locale' => $locale,
                        ),
                    );
                }
            }
        }


        return $this->render('SleepnessUberTranslationBundle:Translation:index.html.twig', array(
            'messages' => $preparedTranslations,
        ));
    }
}
