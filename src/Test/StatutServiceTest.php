<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Statut;
use App\Sensei\Model\Repository\StatutRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\StatutService;
use PHPUnit\Framework\TestCase;
use TypeError;

class StatutServiceTest extends TestCase
{
    public function testRecupererIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->statutRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->statutRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant()
    {
        $fakeStatut = new Statut(3, "gestionnaire", null);
        $this->statutRepositoryMock->method("recupererParClePrimaire")->with(3)->willReturn($fakeStatut);
        self::assertEquals($fakeStatut, $this->service->recupererParIdentifiant(3));
    }

    public function testCreerStatutVide(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune information fournie !");
        $this->statutRepositoryMock->method("ajouterSansIdStatut")->with([])->willReturn(null);
        $this->service->creerStatut([]);
    }

    public function testCreerStatutPasVide(){
        $array = [10, 9];
        $this->statutRepositoryMock->method("ajouterSansIdStatut")->with($array)->willReturn(null);
        self::assertNull($this->service->creerStatut($array));
    }

    public function testSupprimerStatut(){
        $this->statutRepositoryMock->method("supprimer")->with(0);
        self::assertNull($this->service->supprimerStatut(0));
    }

    public function testModifierStatutInexistante(){
        $this->expectException(ServiceException::class);
        $this->statutRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierStatut([
            "idStatut" => 0,
            "libStatut" => "hors France",
            "nbHeures" => 390]);
    }

    public function testModifierStatutExistante(){
        $fakeStatut = new Statut(1, "permanent", null);
        $fakeStatutTab = [
            "idStatut" => 1,
            "libStatut" => "permanent",
            "nbHeures" => null];
        $this->statutRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeStatut);
        $this->statutMock->method("setIdStatut")->with(1);
        $this->statutMock->method("setLibStatut")->with("UN");
        self::assertNull($this->service->modifierStatut($fakeStatutTab));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->statutRepositoryMock = $this->createMock(StatutRepository::class);
        $this->statutMock = $this->createMock(Statut::class);
        $this->service = new StatutService($this->statutRepositoryMock);
    }
}