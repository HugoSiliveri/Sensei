<?php

namespace App\Sensei\Test\ServiceTest;

use App\Sensei\Model\DataObject\ResponsableUS;
use App\Sensei\Model\Repository\ResponsableUSRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\ResponsableUSService;
use PHPUnit\Framework\TestCase;
use TypeError;

class ResponsableUSServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->responsableUSRepositoryMock = $this->createMock(ResponsableUSRepository::class);
        $this->service = new ResponsableUSService($this->responsableUSRepositoryMock);
    }

    public function testRecupererParIdIntervenantAnnuelAvecIdIntervenantNull(){
        $this->expectException(TypeError::class);
        $this->responsableUSRepositoryMock->method("recupererParIdIntervenantAnnuel")->with(null, 2020)->willReturn(null);
        $this->service->recupererParIdIntervenantAnnuel(null, 2020);
    }

    public function testRecupererParIdIntervenantAnnuelAvecMillesimeNull(){
        $this->expectException(TypeError::class);
        $this->responsableUSRepositoryMock->method("recupererParIdIntervenantAnnuel")->with(3, null)->willReturn(null);
        $this->service->recupererParIdIntervenantAnnuel(3, null);
    }

    public function testRecupererParIdIntervenantAnnuelAvecIdIntervenantInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->responsableUSRepositoryMock->method("recupererParIdIntervenantAnnuel")->with(0, 2010)->willReturn(null);
        $this->service->recupererParIdIntervenantAnnuel(0, 2010);
    }

    public function testRecupererParIdIntervenantAnnuelAvecMillesimeInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->responsableUSRepositoryMock->method("recupererParIdIntervenantAnnuel")->with(19, 2080)->willReturn(null);
        $this->service->recupererParIdIntervenantAnnuel(19, 2080);
    }

    public function testRecupererParIdIntervenantAnnuelAvecParametresInexistants(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->responsableUSRepositoryMock->method("recupererParIdIntervenantAnnuel")->with(0, 2200)->willReturn(null);
        $this->service->recupererParIdIntervenantAnnuel(0, 2200);
    }

    public function testRecupererParIdIntervenantAnnuelExistantAvecUnResultat(){
        $tab = [];
        // En 2017, l'intervenant 10 était responsable d'une seule unité service
        $tab[] = new ResponsableUS(10, 17863);
        $this->responsableUSRepositoryMock->method("recupererParIdIntervenantAnnuel")->with(5239, 2017)->willReturn($tab);
        self::assertEquals($tab, $this->service->recupererParIdIntervenantAnnuel(5239, 2017));
    }

    public function testRecupererParIdIntervenantAnnuelExistantAvecPlusieursResultats(){
        $tab = [];
        // En 2017, l'intervenant 1 était responsable de deux unités de services
        $tab[] = new ResponsableUS(1, 17846);
        $tab[] = new ResponsableUS(1, 19336);
        $this->responsableUSRepositoryMock->method("recupererParIdIntervenantAnnuel")->with(1, 2017)->willReturn($tab);
        self::assertEquals($tab, $this->service->recupererParIdIntervenantAnnuel(1, 2017));
    }
}