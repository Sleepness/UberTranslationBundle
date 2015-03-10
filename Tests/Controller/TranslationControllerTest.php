<?php

namespace Sleepness\UberTranslationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Testing TranslationController actions
 *
 * @author Viktor Novikov <viktor.novikov95@gmail.com>
 * @author Alexandr Zhulev <alexandrzhulev@gmail.com>
 */
class TranslationControllerTest extends WebTestCase
{
    /**
     * Test indexAction() of TranslationsController
     */
    public function testIndexAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/translations');
        $response = $client->getResponse();
        $this->assertEquals(1, $crawler->filter('html:contains("Translations Dashboard")')->count());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(5, $crawler->filter('th')->count());
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'text/html; charset=UTF-8'
            )
        );
    }

    /**
     * Test editAction() of TranslationsController
     */
    public function testEditAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/translation/edit/en/messages/test.key');
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('form')->count());
        $this->assertEquals(1, $crawler->filter('div.modal')->count());
        $this->assertTrue($response->isSuccessful());
        $this->assertTrue(
            $response->headers->contains(
                'Content-Type',
                'text/html; charset=UTF-8'
            )
        );
    }

    /**
     * Test deleteAction() of TranslationsController
     */
    public function testDeleteAction()
    {
        $client = static::createClient();
        $client->request('GET', '/translation/delete/en/messages/test.key');
        $this->assertTrue($client->getResponse()->isRedirect()); // this will fail for now
    }
}
