<?php

namespace Sleepness\UberTranslationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Test TranslationController action
 *
 * @author Alexandr Zhulev
 */
class TranslationControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/translations');
        $response = $client->getResponse();

        // some crawler usage

        $this->assertEquals(200, $response->getStatusCode());
    }
}
