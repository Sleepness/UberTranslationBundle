<?php

namespace Sleepness\UberTranslationBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TranslationControllerTest extends WebTestCase
{
    public function testIndexAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/uber/translations');
        $response = $client->getResponse();

        // some crawler usage

        $this->assertEquals(200, $response->getStatusCode());
    }
}
