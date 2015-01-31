<?php

namespace Sleepness\UberTranslationBundle\Tests\Translation;

use Sleepness\UberTranslationBundle\Translation\MemcachedMessageCatalogue;


class MemcachedMessageCatalogueTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $messageCatalogue = new MemcachedMessageCatalogue();
        $translations = [
            'messages' => [
                'key.hello' => 'value.Hello',
                'key.foo' => 'value.Foo',
            ],
            'validators' => [
                 'key.not.blank' => 'value.NotBlank',
                 'key.max.length' => 'value.MaxLength',
            ],
        ];

        $messageCatalogue->add('en', $translations);
        $preparedTransl = $messageCatalogue->getAll();

        $this->assertEquals('messages', $preparedTransl[0]['domain']);
        $this->assertEquals('key.hello', $preparedTransl[0]['keyYml']);
        $this->assertEquals('value.Hello', $preparedTransl[0]['messages'][0]['messageText']);
        $this->assertEquals('en', $preparedTransl[0]['messages'][0]['locale']);
    }
}
