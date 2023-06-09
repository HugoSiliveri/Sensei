<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\UniteService;
use App\Sensei\Model\Repository\UniteServiceRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\UniteServiceService;
use Exception;
use PHPUnit\Framework\TestCase;
use TypeError;

class UniteServiceServiceTest extends TestCase
{
    public function testRecupererIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant()
    {
        $fakeUniteService = new UniteService(9209, "HAS357H", "Modélisation en chimie CPES", "UE", 8897, 2023, 2027, 0, 0, 30, 0, 0, 0, 0, 3, 1, 1, 3, 0);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(9209)->willReturn($fakeUniteService);
        self::assertEquals($fakeUniteService, $this->service->recupererParIdentifiant(9209));
    }

    public function testRechercherUniteServiceNull()
    {
        $this->expectException(TypeError::class);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRechercherUniteServiceIncomplete()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("La recherche est incomplète !");
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->rechercherUniteService("");
    }

    public function testRechercherUniteServiceInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est incorrect !");
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(232029)->willReturn(null);
        $this->service->rechercherUniteService("232029 Module Test");
    }

    public function testRechercherUniteServiceAvecUniquementNomEtPrenom()
    {
        $this->expectException(Exception::class);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        self::assertEquals(null, $this->service->rechercherUniteService("Module Test"));
    }

    public function testRechercherUniteServiceExistantAvecUniquementIdentifiant()
    {
        $fakeUniteService = new UniteService(9209, "HAS357H", "Modélisation en chimie CPES", "UE", 8897, 2023, 2027, 0, 0, 30, 0, 0, 0, 0,3, 1, 1, 3, 0);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(9209)->willReturn($fakeUniteService);
        self::assertEquals($fakeUniteService, $this->service->rechercherUniteService("9209"));
    }

    public function testRechercherUniteServiceExistantAvecChaine()
    {
        $fakeUniteService = new UniteService(9209, "HAS357H", "Modélisation en chimie CPES", "UE", 8897, 2023, 2027, 0, 0, 30, 0, 0, 0, 0,3, 1, 1, 3, 0);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(9209)->willReturn($fakeUniteService);
        self::assertEquals($fakeUniteService, $this->service->rechercherUniteService("9209 Modélisation en chimie CPES"));
    }

    public function testCreerUniteServiceAnneeCorrect(){
        $array = ["anneeOuverture" => 0,
            "anneeCloture" => 0];
        $this->uniteServiceRepositoryMock->method("ajouterSansIdUniteService")->with($array)->willReturn(null);
        self::assertNull($this->service->creerUniteService($array));
    }

    public function testCreerUniteServiceAnneePasCorrect(){
        $this->expectException(ServiceException::class);
        $array = ["anneeOuverture" => 10,
            "anneeCloture" => 1];
        $this->uniteServiceRepositoryMock->method("ajouterSansIdUniteService")->with($array)->willReturn(null);
        self::assertNull($this->service->creerUniteService($array));
    }

    public function testModifierUniteServiceInexistante(){
        $this->expectException(ServiceException::class);
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierUniteService(["idUniteService" => 0]);
    }

    public function testModifierUniteServiceExistante(){
        $fakeUniteService = new UniteService(9202, "Soutien LAS2", "Soutien LAS2", 1, 8897, 2022, 2026, 0, 0, 30, 0, 0, 0, 0, 3, 1, 1, 3, 0);
        $fakeUniteServiceTab = [
            "idUniteService" => 1,
            "idUSReferentiel" => "",
            "libUS" => "",
            "nature" => null,
            "ancetre" => 0,
            "anneeOuverture" => 0,
            "anneeCloture" => 0,
            "ECTS" => 0,
            "heuresCM" => 0,
            "heuresTD" => 0,
            "heuresTP" => 0,
            "heuresStage" => 0,
            "heuresTerrain" => 0,
            "heuresInnovationPedagogique" => 0,
            "semestre" => 0,
            "saison" => 0,
            "idPayeur" => 0,
            "validite" => 0,
            "deleted" => 0];
        $this->uniteServiceRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeUniteService);
        $this->uniteServiceMock->method("setIdUniteService")->with(1);
        $this->uniteServiceMock->method("setIdUSReferentiel")->with(0);
        $this->uniteServiceMock->method("setLibUS")->with("");
        $this->uniteServiceMock->method("setNature")->with("");
        $this->uniteServiceMock->method("setAncetre")->with(0);
        $this->uniteServiceMock->method("setAnneeOuverture")->with(0);
        $this->uniteServiceMock->method("setAnneeCloture")->with(0);
        $this->uniteServiceMock->method("setECTS")->with(0);
        $this->uniteServiceMock->method("setHeuresCM")->with(0);
        $this->uniteServiceMock->method("setHeuresTD")->with(0);
        $this->uniteServiceMock->method("setHeuresTP")->with(0);
        $this->uniteServiceMock->method("setHeuresStage")->with(0);
        $this->uniteServiceMock->method("setHeuresTerrain")->with(0);
        $this->uniteServiceMock->method("setHeuresInnovationPedagogique")->with(0);
        $this->uniteServiceMock->method("setSemestre")->with(0);
        $this->uniteServiceMock->method("setSaison")->with(0);
        $this->uniteServiceMock->method("setIdPayeur")->with(0);
        $this->uniteServiceMock->method("setValidite")->with(0);
        $this->uniteServiceMock->method("setDeleted")->with(0);
        self::assertNull($this->service->modifierUniteService($fakeUniteServiceTab));
    }

    public function testRecupererDernierElementTableNonVide()
    {
        $fakeUniteService = new UniteService(9225, "test2", "test2", 4, null, null, null, 0, 0, 0, 0, 0, 0, 0, 6, 1, 3, 3, 0);
        $this->uniteServiceRepositoryMock->method("recupererDernierElement")->willReturn($fakeUniteService);
        self::assertEquals($fakeUniteService, $this->service->recupererDernierUniteService());
    }

    public function testRecupererDernierElementTableVide(){
        $this->expectException(ServiceException::class);
        $fakeUniteService = new UniteService(9225, "test2", "test2", 4, null, null, null, 0,0,0,0,0,0,0, 6,1,3,3,0);
        $this->uniteServiceRepositoryMock->method("recupererDernierElement");
        self::assertEquals($fakeUniteService, $this->service->recupererDernierUniteService());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->uniteServiceRepositoryMock = $this->createMock(UniteServiceRepository::class);
        $this->uniteServiceMock = $this->createMock(UniteService::class);
        $this->service = new UniteServiceService($this->uniteServiceRepositoryMock);
    }
}