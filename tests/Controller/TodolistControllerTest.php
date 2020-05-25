<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodolistControllerTest extends WebTestCase
{
    public function testGetTodolist()
    {
        $client = static::createClient();
        $client->request('GET', 'http://localhost/todolist/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testPostTodoList()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'todolist/new',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{   
                "id":"2",
                "email":"tcorio@myges.fr",
                "age":"22",
                "password":"test1234"
            }'
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider urls
     */
    public function testHeader($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"' 
        );
    }

    /**
     * @dataProvider urls
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function urls()
    {
        return [
            ['http://localhost/todolist/'],
            ['http://localhost/user/'],
            ['http://localhost/item/']
            
        ];
    }

    public function testPostUser()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            'user/new',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            '{  
                "name":"Titi",
                "firstname":"Toto",
                "email":"tcorio@myges.fr",
                "age":"22",
                "password":"test1234"
            }'
        );
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
}