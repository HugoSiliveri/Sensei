<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Etat;
use App\Sensei\Model\Repository\EtatRepository;
use App\Sensei\Service\EtatService;
use App\Sensei\Service\Exception\ServiceException;
use PHPUnit\Framework\TestCase;
use TypeError;

class EtatServiceTest extends TestCase
{
    public function testRecupererIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->etatRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->etatRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant()
    {
        $fakeEtat = new Etat(3, "Enseignant");
        $this->etatRepositoryMock->method("recupererParClePrimaire")->with(3)->willReturn($fakeEtat);
        self::assertEquals($fakeEtat, $this->service->recupererParIdentifiant(3));
    }

    public function testModifierEtatInexistante(){
        $this->expectException(ServiceException::class);
        $this->etatRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierEtat([
            "idEtat" => 0,
            "libEtat" => "CREATION"]);
    }

    public function testModifierEtatExistante(){
        $fakeEtat = new Etat(1, "administrateur.e");
        $fakeEtatTab = [
            "idEtat" => 1,
            "libEtat" => "NORMAL"];
        $this->etatRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeEtat);
        $this->etatMock->method("setIdEtat")->with(1);
        $this->etatMock->method("setLibEtat")->with("ACTUEL");
        self::assertNull($this->service->modifierEtat($fakeEtatTab));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->etatRepositoryMock = $this->createMock(EtatRepository::class);
        $this->etatMock = $this->createMock(Etat::class);
        $this->service = new EtatService($this->etatRepositoryMock);
    }
}