<?php

namespace App\Tests\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MovieControllerTest extends WebTestCase
{
    /**
     * @dataProvider provideUrls
     */
    public function testAnonymous($url)
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $url);

        // On fait une requête sans connexion,
        // on est donc anonyme et on vérifie que Symfony fait bien une redirection
        // Il redirige normalement vers la route /login pour inviter à se connecter
        $this->assertResponseRedirects('/login');
    }

    /**
     * @dataProvider provideUrls
     */
    public function testAsAdmin($url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'lucie@o.o',
            'PHP_AUTH_PW'   => 'tagazou',
        ]);
        $crawler = $client->request('GET', $url);

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider provideUrls
     */
    public function testAsUser($url)
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'ben@o.o',
            'PHP_AUTH_PW'   => 'tagazou',
        ]);
        $crawler = $client->request('GET', $url);

        $this->assertResponseStatusCodeSame(403);
    }

    public function provideUrls()
    {
        // On rajoute des / à la fin des routes car Symfony les fait lui-même
        // Sans les /, la réponse est une 301 (redirection)
        // Pour la dernière route, EasyAdmin redirige toujours
        // vers une route avec des paramètres en GET
        // Donc on les ajoute ici pour éviter de se voir répondre une 301 également
        return [
            ['/admin/movie/'],
            ['/admin/job/'],
            ['/admin/department/'],
            ['/admin/?action=list&entity=Movie'],
        ];
    }
}
