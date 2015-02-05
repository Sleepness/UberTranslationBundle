<?php

namespace Sleepness\UberTranslationBundle\Controller;

use Sleepness\UberTranslationBundle\Form\Model\TranslationModel;
use Sleepness\UberTranslationBundle\Form\Type\TranslationMessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller responsive for managing translations
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev
 */
class TranslationController extends Controller
{
    /**
     * Output all translations
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $messageCatalogue = $this->get('memcached.message.catalogue');
        $locale = $request->query->get('locale'); // get parameters for filtering
        $domain = $request->query->get('domain');
        $key = $request->query->get('key');
        $text = $request->query->get('text');
        if (null != $locale) { // check if exists some conditions
            $messages = $messageCatalogue->buildByLocale($locale);
        } elseif (null != $key) {
            $messages = $messageCatalogue->buildByKey($key);
        } elseif (null != $domain) {
            $messages = $messageCatalogue->buildByDomain($domain);
        } elseif (null != $text) {
            $messages = $messageCatalogue->buildByText($text);
        } else {
            $messages = $messageCatalogue->getAll();
        }
        $paginator = $this->get('knp_paginator'); // paginating results
        $messages = $paginator->paginate($messages, $request->query->get('page', 1), 5);
        $locales = $this->container->getParameter('sleepness_uber_translation.supported_locales');

        return $this->render('SleepnessUberTranslationBundle:Translation:index.html.twig', array(
            'locales'  => $locales,
            'messages' => $messages,
        ));
    }

    /**
     * Edit translation
     *
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
            $this->get('session')->getFlashBag()->add('translation_edited', 'Translation edited successfully');

            return $this->redirect($this->generateUrl('sleepness_translation_dashboard'));
        }

        return $this->render('SleepnessUberTranslationBundle:Translation:edit.html.twig', array(
            'key'     => $_key,
            'locale'  => $_locale,
            'domain'  => $_domain,
            'message' => $message,
            'form'    => $form->createView()
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
        $this->get('session')->getFlashBag()->add('translation_deleted', 'Translation deleted successfully');

        return $this->redirect($this->generateUrl('sleepness_translation_dashboard'));
    }
}
