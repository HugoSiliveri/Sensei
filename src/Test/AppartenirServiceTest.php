<?php

namespace App\Sensei\Test;


use App\Sensei\Model\DataObject\Appartenir;
use App\Sensei\Model\Repository\AppartenirRepository;
use App\Sensei\Service\AppartenirService;
use App\Sensei\Service\Exception\ServiceException;
use PHPUnit\Framework\TestCase;

class AppartenirServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->appartenirRepositoryMock = $this->createMock(AppartenirRepository::class);
        $this->service = new AppartenirService($this->appartenirRepositoryMock);
    }

    public function testCreerAppartenirVide(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune information fournie !");
        $this->appartenirRepositoryMock->method("ajouterSansIdAppartenir")->with([])->willReturn(null);
        $this->service->creerAppartenir([]);
    }

    public function testCreerAppartenirPasVide(){
        $array = [10, 9];
        $this->appartenirRepositoryMock->method("ajouterSansIdAppartenir")->with($array)->willReturn(null);
        self::assertNull($this->service->creerAppartenir($array));
    }

    public function testRecupererParIdUniteServiceInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune affiliation trouvée pour cette unité !");
        $this->appartenirRepositoryMock->method("recupererParIdUniteService")->with(0)->willReturn(null);
        $this->service->recupererParIdUniteService(0);
    }

    public function testRecupererParIdUniteServiceExistant(){
        $fakeAppartenir = new Appartenir(7863, 1, 2);
        $this->appartenirRepositoryMock->method("recupererParIdUniteService")->with(2)->willReturn([$fakeAppartenir]);
        self::assertEquals([$fakeAppartenir], $this->service->recupererParIdUniteService(2));
    }
}