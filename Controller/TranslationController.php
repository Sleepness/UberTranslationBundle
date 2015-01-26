<?php

namespace Sleepness\UberTranslationBundle\Controller;

use Sleepness\UberTranslationBundle\Form\Model\TranslationModel;
use Sleepness\UberTranslationBundle\Form\Type\TranslationMessageType;
use Sleepness\UberTranslationBundle\Translation\MemcachedMessageCatalogue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller responsive for managing translations
 */
class TranslationController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * @param Request $request
     * @param $_locale
     * @param $_domain
     * @param $_key
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $_locale, $_domain, $_key)
    {
        $mem = $this->get('uber.memcached');
        $translations = $mem->getItem($_locale);
        $message = $translations[$_domain][$_key];
        $model = new TranslationModel();
        $model->setTranslation($message);
        $form = $this->createForm(new TranslationMessageType(), $model);
        $form->handleRequest($request);
        if ($form->isValid()) {
            // do something
        }

        return $this->render('SleepnessUberTranslationBundle:Translation:edit.html.twig', array(
            'message' => $message,
            'form' => $form->createView()
        ));
    }
}
