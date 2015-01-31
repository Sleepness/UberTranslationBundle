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
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $messageCatalogue = new MemcachedMessageCatalogue();
        $mem = $this->get('uber.memcached');
        $locales = $this->container->getParameter('sleepness_uber_translation.supported_locales');

        $locale = $request->query->get('locale'); // get parameters for filtering
        $domain = $request->query->get('domain');
        $key = $request->query->get('key');
        $text = $request->query->get('text');

        if (null != $locale) { // check if exists some condidtions
            $messageCatalogue->add($locale, $mem->getItem($locale));
            $messages = $messageCatalogue->getAll();
        } else if (null != $key) {
            $messages = $mem->getAllByKey($key);
        } else if (null != $domain) {
            $messages = $mem->getAllByDomain($domain);
        } else if (null != $text) {
            $messages = $mem->getAllByText($text);
        } else {
            foreach ($locales as $key => $locale) {
                $translations = $mem->getItem($locale);
                $messageCatalogue->add($locale, $translations);
            }
            $messages = $messageCatalogue->getAll();
        }
        $paginator = $this->get('knp_paginator'); // paginating results
        $messages = $paginator->paginate($messages, $request->query->get('page', 1), 5);

        return $this->render('SleepnessUberTranslationBundle:Translation:index.html.twig', array(
            'locales' => $locales,
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
            $translations[$_domain][$_key] = $model->getTranslation();
            $mem->addItem($_locale, $translations);
        }

        return $this->render('SleepnessUberTranslationBundle:Translation:edit.html.twig', array(
            'key' => $_key,
            'message' => $message,
            'form' => $form->createView()
        ));
    }

    /**
     * Delete translation from memcache
     *
     * @param $_locale
     * @param $_domain
     * @param $_key
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($_locale, $_domain, $_key)
    {
        $mem = $this->get('uber.memcached');
        $translations = $mem->getItem($_locale);
        unset($translations[$_domain][$_key]);
        $mem->addItem($_locale, $translations);

        return $this->redirect($this->generateUrl('sleepness_translation_dashboard'));
    }
}
