<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Emploi;
use App\Sensei\Model\Repository\EmploiRepository;
use App\Sensei\Service\EmploiService;
use App\Sensei\Service\Exception\ServiceException;
use PHPUnit\Framework\TestCase;
use TypeError;

class EmploiServiceTest extends TestCase
{
    public function testRecupererIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->emploiRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->emploiRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant()
    {
        $fakeEmploi = new Emploi(3, "Enseignant");
        $this->emploiRepositoryMock->method("recupererParClePrimaire")->with(3)->willReturn($fakeEmploi);
        self::assertEquals($fakeEmploi, $this->service->recupererParIdentifiant(3));
    }

    public function testCreerEmploiVide(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune information fournie !");
        $this->emploiRepositoryMock->method("ajouterSansIdEmploi")->with([])->willReturn(null);
        $this->service->creerEmploi([]);
    }

    public function testCreerEmploiPasVide(){
        $array = [10, 9];
        $this->emploiRepositoryMock->method("ajouterSansIdEmploi")->with($array)->willReturn(null);
        self::assertNull($this->service->creerEmploi($array));
    }

    public function testSupprimerEmploi(){
        $this->emploiRepositoryMock->method("supprimer")->with(0);
        self::assertNull($this->service->supprimerEmploi(0));
    }

    public function testModifierEmploiInexistante(){
        $this->expectException(ServiceException::class);
        $this->emploiRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierEmploi([
            "idEmploi" => 0,
            "libEmploi" => "stagiaire"]);
    }

    public function testModifierEmploiExistante(){
        $fakeEmploi = new Emploi(1, "administrateur.e");
        $fakeEmploiTab = [
            "idEmploi" => 1,
            "libEmploi" => "dirigeant"];
        $this->emploiRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeEmploi);
        $this->emploiMock->method("setIdEmploi")->with(1);
        $this->emploiMock->method("setLibEmploi")->with("dirigeant");
        self::assertNull($this->service->modifierEmploi($fakeEmploiTab));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->emploiRepositoryMock = $this->createMock(EmploiRepository::class);
        $this->emploiMock = $this->createMock(Emploi::class);
        $this->service = new EmploiService($this->emploiRepositoryMock);
    }
}