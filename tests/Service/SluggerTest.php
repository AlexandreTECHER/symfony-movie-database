<?php

namespace App\Tests\Service;

// use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SluggerTest extends WebTestCase
{
    public function testSlugify()
    {
        // Pour obtenir un objet Slugger, comme il a de lînjection de dépendance, on doit le récupérer de cette façon
        // Il faut instancier le Kernel de Symfony dans notre classe de test
        self::bootKernel();
        // On récupére ensuite le Service Container depuis le Kernel
        // et on lui demande de nous récupérer le Slugger
        $slugger = self::$container->get('App\Service\Slugger');

        $testingValue = $slugger->slugify('RRRrrrr !');
        $this->assertIsString($testingValue);
        $this->assertEquals($testingValue, 'rrrrrrr');

        $testingValue = $slugger->slugify('Bienvenue chez les ch\'tis');
        $this->assertIsString($testingValue);
        $this->assertEquals($testingValue, 'bienvenue-chez-les-ch-tis');

        $testingValue = $slugger->slugify('2001 A Space Odyssey');
        $this->assertIsString($testingValue);
        $this->assertEquals($testingValue, '2001-a-space-odyssey');
    }
}
