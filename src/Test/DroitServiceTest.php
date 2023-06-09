<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Droit;
use App\Sensei\Model\Repository\DroitRepository;
use App\Sensei\Service\DroitService;
use App\Sensei\Service\Exception\ServiceException;
use PHPUnit\Framework\TestCase;
use TypeError;

class DroitServiceTest extends TestCase
{
    public function testRecupererIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->droitRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->droitRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant()
    {
        $fakeDroit = new Droit(3, "Enseignant");
        $this->droitRepositoryMock->method("recupererParClePrimaire")->with(3)->willReturn($fakeDroit);
        self::assertEquals($fakeDroit, $this->service->recupererParIdentifiant(3));
    }

    public function testCreerDroitVide(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune information fournie !");
        $this->droitRepositoryMock->method("ajouterSansIdDroit")->with([])->willReturn(null);
        $this->service->creerDroit([]);
    }

    public function testCreerDroitPasVide(){
        $array = [10, 9];
        $this->droitRepositoryMock->method("ajouterSansIdDroit")->with($array)->willReturn(null);
        self::assertNull($this->service->creerDroit($array));
    }

    public function testSupprimerDroit(){
        $this->droitRepositoryMock->method("supprimer")->with(0);
        self::assertNull($this->service->supprimerDroit(0));
    }

    public function testModifierDroitInexistante(){
        $this->expectException(ServiceException::class);
        $this->droitRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierDroit([
            "idDroit" => 0,
            "typeDroit" => "stagiaire"]);
    }

    public function testModifierDroitExistante(){
        $fakeDroit = new Droit(1, "administrateur.e");
        $fakeDroitTab = [
            "idDroit" => 1,
            "typeDroit" => "administrateur.e"];
        $this->droitRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeDroit);
        $this->droitMock->method("setIdDroit")->with(1);
        $this->droitMock->method("setTypeDroit")->with("stagiaire");
        self::assertNull($this->service->modifierDroit($fakeDroitTab));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->droitRepositoryMock = $this->createMock(DroitRepository::class);
        $this->droitMock = $this->createMock(Droit::class);
        $this->service = new DroitService($this->droitRepositoryMock);
    }
}