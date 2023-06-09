<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Composante;
use App\Sensei\Model\Repository\ComposanteRepository;
use App\Sensei\Service\ComposanteService;
use App\Sensei\Service\Exception\ServiceException;
use PHPUnit\Framework\TestCase;

class ComposanteServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->departementRepositoryMock = $this->createMock(ComposanteRepository::class);
        $this->departementMock = $this->createMock(Composante::class);
        $this->service = new ComposanteService($this->departementRepositoryMock);
    }

    public function testRecupererParIdentifiantInexistant(){
        $this->expectException(ServiceException::class);
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererParIdentifiantExistant(){
        $fakeComposante = new Composante(1, "Faculté des Sciences", 2023, 2020);
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeComposante);
        self::assertEquals($fakeComposante, $this->service->recupererParIdentifiant(1));
    }

    public function testCreerComposanteVide(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune information fournie !");
        $this->departementRepositoryMock->method("ajouterSansIdComposante")->with([])->willReturn(null);
        $this->service->creerComposante([]);
    }

    public function testCreerComposantePasVide(){
        $array = ["test", 2010, 0230];
        $this->departementRepositoryMock->method("ajouterSansIdComposante")->with($array)->willReturn(null);
        self::assertNull($this->service->creerComposante($array));
    }

    public function testSupprimerComposante(){
        $this->departementRepositoryMock->method("supprimer")->with(0);
        self::assertNull($this->service->supprimerComposante(0));
    }

    public function testModifierComposanteInexistante(){
        $this->expectException(ServiceException::class);
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierComposante([
            "idComposante" => 0,
            "libComposante" => "kdesz",
            "anneeDeTravail" => 2020,
            "anneeEnCours" => 2020]);
    }

    public function testModifierComposanteExistante(){
        $fakeComposante = new Composante(1, "Faculté des Sciences", 2023, 2020);
        $fakeComposanteTab = [
            "idComposante" => 1,
            "libComposante" => "kdesz",
            "anneeDeTravail" => 2020,
            "anneeDeValidation" => 2020];
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeComposante);
        $this->departementMock->method("setIdComposante")->with(1);
        $this->departementMock->method("setLibComposante")->with("kdesz");
        $this->departementMock->method("setAnneeDeTravail")->with(2020);
        $this->departementMock->method("setAnneeDeValidation")->with(2020);
        self::assertNull($this->service->modifierComposante($fakeComposanteTab));
    }
}