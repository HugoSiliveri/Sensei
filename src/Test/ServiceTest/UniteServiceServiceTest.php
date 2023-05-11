<?php

namespace App\Sensei\Test\ServiceTest;

use App\Sensei\Model\DataObject\UniteService;
use App\Sensei\Model\Repository\UniteServiceRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\UniteServiceService;
use PHPUnit\Framework\TestCase;
use TypeError;

class UniteServiceServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->uniteServiceRepositoryMock = $this->createMock(UniteServiceRepository::class);
        $this->service = new UniteServiceService($this->uniteServiceRepositoryMock);
    }

    public function testRecupererIdentifiantNull(){
        $this->expectException(TypeError::class);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant(){
        $fakeUniteService = new UniteService(9209, "HAS357H", "Modélisation en chimie CPES", "UE", 8897, 2023, 2027, 0, 0, 30, 0, 0, 0, 3, 1, "FDS FAC DES SCIENCES", 3, 0);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(9209)->willReturn($fakeUniteService);
        self::assertEquals($fakeUniteService, $this->service->recupererParIdentifiant(9209));
    }

    public function testRechercherUniteServiceNull(){
        $this->expectException(TypeError::class);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRechercherUniteServiceIncomplete(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("La recherche est incomplète !");
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->rechercherUniteService("");
    }

    public function testRechercherUniteServiceInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est incorrect !");
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(232029)->willReturn(null);
        $this->service->rechercherUniteService("232029 Module Test");
    }

    public function testRechercherUniteServiceAvecUniquementNomEtPrenom(){
        $this->expectException(\Exception::class);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        self::assertEquals(null, $this->service->rechercherUniteService( "Module Test"));
    }

    public function testRechercherUniteServiceExistantAvecUniquementIdentifiant(){
        $fakeUniteService = new UniteService(9209, "HAS357H", "Modélisation en chimie CPES", "UE", 8897, 2023, 2027, 0, 0, 30, 0, 0, 0, 3, 1, "FDS FAC DES SCIENCES", 3, 0);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(9209)->willReturn($fakeUniteService);
        self::assertEquals($fakeUniteService, $this->service->rechercherUniteService("9209"));
    }

    public function testRechercherUniteServiceExistantAvecChaine(){
        $fakeUniteService = new UniteService(9209, "HAS357H", "Modélisation en chimie CPES", "UE", 8897, 2023, 2027, 0, 0, 30, 0, 0, 0, 3, 1, "FDS FAC DES SCIENCES", 3, 0);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(9209)->willReturn($fakeUniteService);
        self::assertEquals($fakeUniteService, $this->service->rechercherUniteService("9209 Modélisation en chimie CPES"));
    }
}