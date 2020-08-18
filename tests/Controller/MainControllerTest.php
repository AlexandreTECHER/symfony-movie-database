<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testMainPage()
    {
        // createClient() nous crée un objet qui se comporte comme un navigateur
        $client = static::createClient();
        // On demande à ce navigateur d'aller sur la route / du projet
        $crawler = $client->request('GET', '/');

        // Ces deux assertions sont pareilles
        // La première est possible depuis Symfony 4.3
        // Le seconde correspond à ce qu'on pouvait faire avant,
        //    elle est encore utilisable aujourd'hui et pratique si on veut tester une valeur précise
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertSelectorTextContains('h2', 'Liste des films');
    }
}
