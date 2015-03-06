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
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
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
        $messageCatalogue = $this->get('memcached.messages.frontend');
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
     * @param $localeKey
     * @param $_domain
     * @param $_key
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $localeKey, $_domain, $_key)
    {
        $mem = $this->get('uber.memcached');
        $translations = $mem->getItem($localeKey);
        $message = $translations[$_domain][$_key];
        $model = new TranslationModel();
        $model->setTranslation($message);
        $form = $this->createForm(new TranslationMessageType(), $model);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $translations[$_domain][$_key] = $model->getTranslation();
            $mem->addItem($localeKey, $translations);
            $this->get('session')->getFlashBag()->add('translation_edited', 'edit_success');

            return $this->redirect($this->generateUrl('sleepness_translation_dashboard'));
        }

        return $this->render('SleepnessUberTranslationBundle:Translation:edit.html.twig', array(
            'key'     => $_key,
            'locale'  => $localeKey,
            'domain'  => $_domain,
            'message' => $message,
            'form'    => $form->createView()
        ));
    }

    /**
     * Delete translation from memcache
     *
     * @param $localeKey
     * @param $_domain
     * @param $_key
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($localeKey, $_domain, $_key)
    {
        $mem = $this->get('uber.memcached');
        $translations = $mem->getItem($localeKey);
        unset($translations[$_domain][$_key]);
        $mem->addItem($localeKey, $translations);
        $this->get('session')->getFlashBag()->add('translation_deleted', 'delete_success');

        return $this->redirect($this->generateUrl('sleepness_translation_dashboard'));
    }
}
