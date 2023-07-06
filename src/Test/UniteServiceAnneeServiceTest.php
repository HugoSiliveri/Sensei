<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\UniteServiceAnnee;
use App\Sensei\Model\Repository\UniteServiceAnneeRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\UniteServiceAnneeService;
use PHPUnit\Framework\TestCase;
use TypeError;

class UniteServiceAnneeServiceTest extends TestCase
{
    public function testRecupererIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->uniteServiceAnneeRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->uniteServiceAnneeRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant()
    {
        $fakeUniteServiceAnnee = new UniteServiceAnnee(9, 1, 16, null, 2004, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 0, 0, 3, 0);
        $this->uniteServiceAnneeRepositoryMock->method("recupererParClePrimaire")->with(3)->willReturn($fakeUniteServiceAnnee);
        self::assertEquals($fakeUniteServiceAnnee, $this->service->recupererParIdentifiant(3));
    }

    public function testRecupererUniteServiceNull()
    {
        $this->expectException(TypeError::class);
        $this->uniteServiceAnneeRepositoryMock->method("recupererParUniteService")->with(null)->willReturn([]);
        $this->service->recupererParUniteService(null);
    }

    public function testRecupererUniteServiceInexistant()
    {
        $this->uniteServiceAnneeRepositoryMock->method("recupererParUniteService")->with(0)->willReturn([]);
        self::assertEquals([], $this->service->recupererParUniteService(0));
    }

    public function testRecupererUniteServiceExistant()
    {
        $tab = [];
        $tab[] = new UniteServiceAnnee(224, 1, 2, null, 2006, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 0);
        $this->uniteServiceAnneeRepositoryMock->method("recupererParUniteService")->with(3)->willReturn($tab);
        self::assertEquals($tab, $this->service->recupererParUniteService(3));
    }

    public function testCreerUniteServiceAnneeVide()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune information fournie !");
        $this->uniteServiceAnneeRepositoryMock->method("ajouterSansIdUniteServiceAnnee")->with([])->willReturn(null);
        $this->service->creerUniteServiceAnnee([]);
    }

    public function testCreerUniteServiceAnneePasVide()
    {
        $array = [10, 9];
        $this->uniteServiceAnneeRepositoryMock->method("ajouterSansIdUniteServiceAnnee")->with($array)->willReturn(null);
        self::assertNull($this->service->creerUniteServiceAnnee($array));
    }

    public function testModifierUniteServiceAnneeInexistante()
    {
        $this->expectException(ServiceException::class);
        $this->uniteServiceAnneeRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierUniteServiceAnnee([
            "idUniteServiceAnnee" => 0,
            "libUSA" => "matière"]);
    }

    public function testModifierUniteServiceAnneeExistante()
    {
        $fakeUniteServiceAnnee = new UniteServiceAnnee(224, 1, 2, null, 2006, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 0);
        $fakeUniteServiceAnneeTab = [
            "idUniteServiceAnnee" => 1,
            "idDepartement" => 0,
            "idUniteService" => 0,
            "libUSA" => null,
            "millesime" => 0,
            "heuresCM" => 0,
            "nbGroupesCM" => 0,
            "heuresTD" => 0,
            "nbGroupesTD" => 0,
            "heuresTP" => 0,
            "nbGroupesTP" => 0,
            "heuresStage" => 0,
            "nbGroupesStage" => 0,
            "heuresTerrain" => 0,
            "nbGroupesTerrain" => 0,
            "heuresInnovationPedagogique" => 0,
            "nbGroupesInnovationPedagogique" => 0,
            "validite" => 0,
            "deleted" => 0];
        $this->uniteServiceAnneeRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeUniteServiceAnnee);
        $this->uniteServiceAnneeMock->method("setIdUniteServiceAnnee")->with(1);
        $this->uniteServiceAnneeMock->method("setIdDepartement")->with(0);
        $this->uniteServiceAnneeMock->method("setIdUniteService")->with(0);
        $this->uniteServiceAnneeMock->method("setLibUSA")->with("");
        $this->uniteServiceAnneeMock->method("setMillesime")->with(0);
        $this->uniteServiceAnneeMock->method("setHeuresCM")->with(0);
        $this->uniteServiceAnneeMock->method("setNbGroupesCM")->with(0);
        $this->uniteServiceAnneeMock->method("setHeuresTD")->with(0);
        $this->uniteServiceAnneeMock->method("setNbGroupesTD")->with(0);
        $this->uniteServiceAnneeMock->method("setHeuresTP")->with(0);
        $this->uniteServiceAnneeMock->method("setNbGroupesTP")->with(0);
        $this->uniteServiceAnneeMock->method("setHeuresStage")->with(0);
        $this->uniteServiceAnneeMock->method("setNbGroupesStage")->with(0);
        $this->uniteServiceAnneeMock->method("setHeuresTerrain")->with(0);
        $this->uniteServiceAnneeMock->method("setNbGroupesTerrain")->with(0);
        $this->uniteServiceAnneeMock->method("setHeuresInnovationPedagogique")->with(0);
        $this->uniteServiceAnneeMock->method("setNbGroupesInnovationPedagogique")->with(0);
        $this->uniteServiceAnneeMock->method("setValidite")->with(0);
        $this->uniteServiceAnneeMock->method("setDeleted")->with(0);
        self::assertNull($this->service->modifierUniteServiceAnnee($fakeUniteServiceAnneeTab));
    }

    public function testRecupererUnitesServicesPourUneAnneePourUnDepartementInexistant()
    {
        $this->uniteServiceAnneeRepositoryMock->method("recupererUnitesServicesPourUneAnneePourUnDepartement")->with(0, 1)->willReturn([]);
        self::assertEquals([], $this->service->recupererUnitesServicesPourUneAnneePourUnDepartement(0, 1));
    }

    public function testRecupererUnitesServicesAnneeUniquementColorationInexistant()
    {
        $this->uniteServiceAnneeRepositoryMock->method("recupererUniteServiceAnneeUniquementColoration")->with(0, 1)->willReturn([]);
        self::assertEquals([], $this->service->recupererUnitesServicesAnneeUniquementColoration(0, 1));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->uniteServiceAnneeRepositoryMock = $this->createMock(UniteServiceAnneeRepository::class);
        $this->uniteServiceAnneeMock = $this->createMock(UniteServiceAnnee::class);
        $this->service = new UniteServiceAnneeService($this->uniteServiceAnneeRepositoryMock);
    }
}