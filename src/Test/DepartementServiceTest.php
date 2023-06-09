<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Departement;
use App\Sensei\Model\DataObject\Intervenant;
use App\Sensei\Model\DataObject\ServiceAnnuel;
use App\Sensei\Model\Repository\DepartementRepository;
use App\Sensei\Model\Repository\IntervenantRepository;
use App\Sensei\Model\Repository\ServiceAnnuelRepository;
use App\Sensei\Service\DepartementService;
use App\Sensei\Service\Exception\ServiceException;
use PHPUnit\Framework\TestCase;
use TypeError;

class DepartementServiceTest extends TestCase
{
    private $service;
    private $departementRepositoryMock;

    public function testRecupererIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant()
    {
        $fakeDepartement = new Departement(1, "MATH", "MA", 25, 1, 2);
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeDepartement);
        self::assertEquals($fakeDepartement, $this->service->recupererParIdentifiant(1));
    }

    public function testRecupererParLibelleVide()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Le département n'est spécifié !");
        $this->departementRepositoryMock->method("recupererParLibelle")->with("")->willReturn([]);
        $this->service->recupererParLibelle("");
    }


    public function testRecupererParLibelleInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucun/plusieurs département(s) n'a/ont été trouvé !");
        $this->departementRepositoryMock->method("recupererParLibelle")->with(" test ")->willReturn([]);
        $this->service->recupererParLibelle(" test ");
    }

    public function testRecupererParLibelleExistant()
    {
        $fakeDepartement = new Departement(1, "MATH", "MA", "25", 1, 2);
        $this->departementRepositoryMock->method("recupererParLibelle")->with("MATH")->willReturn([$fakeDepartement]);
        self::assertEquals($fakeDepartement, $this->service->recupererParLibelle("MATH"));
    }

    public function testCreerDepartementVide(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune information fournie !");
        $this->departementRepositoryMock->method("ajouterSansIdDepartement")->with([])->willReturn(null);
        $this->service->creerDepartement([]);
    }

    public function testCreerDepartementPasVide(){
        $array = [10, 9];
        $this->departementRepositoryMock->method("ajouterSansIdDepartement")->with($array)->willReturn(null);
        self::assertNull($this->service->creerDepartement($array));
    }

    public function testSupprimerDepartement(){
        $this->departementRepositoryMock->method("supprimer")->with(0);
        self::assertNull($this->service->supprimerDepartement(0));
    }

    public function testModifierDepartementInexistante(){
        $this->expectException(ServiceException::class);
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierDepartement([
            "idDepartement" => 0,
            "libDepartement" => "kdesz",
            "codeLettre" => "DL",
            "reportMax" => 210,
            "idComposante" => 45,
            "idEtat" => 39]);
    }

    public function testModifierDepartementExistante(){
        $fakeDepartement = new Departement(1, "MATH", "MA", 25, 1, 2);
        $fakeDepartementTab = [
            "idDepartement" => 1,
            "libDepartement" => "kdesz",
            "codeLettre" => "lfe",
            "reportMax" => 2020,
            "idComposante" => 1,
            "idEtat" => 2];
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeDepartement);
        $this->departementMock->method("setIdDepartement")->with(1);
        $this->departementMock->method("setLibDepartement")->with("kdesz");
        $this->departementMock->method("setCodeLettre")->with("lfe");
        $this->departementMock->method("setReportMax")->with(2020);
        $this->departementMock->method("setIdComposante")->with(1);
        $this->departementMock->method("setIdEtat")->with(2);
        self::assertNull($this->service->modifierDepartement($fakeDepartementTab));
    }

    public function testChangerEtatInferieurAUn(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'état choisi n'existe pas !");
        $this->departementRepositoryMock->method("changerEtat")->with(1, 0)->willReturn(null);
        $this->service->changerEtat(1,0);
    }

    public function testChangerEtatSuperieurATrois(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'état choisi n'existe pas !");
        $this->departementRepositoryMock->method("changerEtat")->with(1, 4)->willReturn(null);
        $this->service->changerEtat(1,4);
    }

    public function testChangerEtatExistant(){
        $this->departementRepositoryMock->method("changerEtat")->with(1, 2)->willReturn(null);
        self::assertNull($this->service->changerEtat(1,2));
    }

    public function testVerifierDroitsPourGestionDepartementDifferent(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Vous n'appartenez pas au département que vous souhaitez modifier !");
        $fakeServiceAnnuel = new ServiceAnnuel(30802, 1, 1, 2023, 6, 192, 164.5, -1.5,0);
        $this->serviceAnnuelRepositoryMock->method("recupererParIntervenantAnnuelPlusRecent")->with(1)->willReturn($fakeServiceAnnuel);
        $this->service->verifierDroitsPourGestion(1,4);
    }

    public function testVerifierDroitsPourGestionDroitManquant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Vous n'avez pas les permissions pour réaliser la modification !");
        $fakeServiceAnnuel = new ServiceAnnuel(30802, 1, 1, 2023, 6, 192, 164.5, -1.5,0);
        $this->serviceAnnuelRepositoryMock->method("recupererParIntervenantAnnuelPlusRecent")->with(1)->willReturn($fakeServiceAnnuel);
        $fakeIntervenant = new Intervenant(1, "Akrout", "Hugo", 1, 3,"hugo.akrout@umontpellier.fr", null, "p00000008902", 0);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeIntervenant);
        $this->service->verifierDroitsPourGestion(1,1);
    }

    public function testVerifierDroitsPourGestionPossible(){
        $fakeServiceAnnuel = new ServiceAnnuel(30823, 1, 3637, 2023, 3, 192, 0, 0,0);
        $this->serviceAnnuelRepositoryMock->method("recupererParIntervenantAnnuelPlusRecent")->with(3637)->willReturn($fakeServiceAnnuel);
        $fakeIntervenant = new Intervenant(3637, "de Saporta", "Benoite", 1, 1,"benoite.de-saporta@umontpellier.fr", null, "p00000007991", 0);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(3637)->willReturn($fakeIntervenant);
        self::assertNull($this->service->verifierDroitsPourGestion(3637,1));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->departementRepositoryMock = $this->createMock(DepartementRepository::class);
        $this->departementMock = $this->createMock(Departement::class);
        $this->serviceAnnuelRepositoryMock = $this->createMock(ServiceAnnuelRepository::class);
        $this->intervenantRepositoryMock = $this->createMock(IntervenantRepository::class);
        $this->service = new DepartementService($this->departementRepositoryMock, $this->serviceAnnuelRepositoryMock, $this->intervenantRepositoryMock);
    }
}