<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Nature;
use App\Sensei\Model\Repository\NatureRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\NatureService;
use PHPUnit\Framework\TestCase;
use TypeError;

class NatureServiceTest extends TestCase
{
    public function testRecupererIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->natureRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->natureRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant()
    {
        $fakeNature = new Nature(3, "ND");
        $this->natureRepositoryMock->method("recupererParClePrimaire")->with(3)->willReturn($fakeNature);
        self::assertEquals($fakeNature, $this->service->recupererParIdentifiant(3));
    }

    public function testCreerNatureVide()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune information fournie !");
        $this->natureRepositoryMock->method("ajouterSansIdNature")->with([])->willReturn(null);
        $this->service->creerNature([]);
    }

    public function testCreerNaturePasVide()
    {
        $array = [10, 9];
        $this->natureRepositoryMock->method("ajouterSansIdNature")->with($array)->willReturn(null);
        self::assertNull($this->service->creerNature($array));
    }

    public function testSupprimerNature()
    {
        $this->natureRepositoryMock->method("supprimer")->with(0);
        self::assertNull($this->service->supprimerNature(0));
    }

    public function testModifierNatureInexistante()
    {
        $this->expectException(ServiceException::class);
        $this->natureRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierNature([
            "idNature" => 0,
            "libNature" => "UN"]);
    }

    public function testModifierNatureExistante()
    {
        $fakeNature = new Nature(1, "administrateur.e");
        $fakeNatureTab = [
            "idNature" => 1,
            "libNature" => "UE"];
        $this->natureRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeNature);
        $this->natureMock->method("setIdNature")->with(1);
        $this->natureMock->method("setLibNature")->with("UN");
        self::assertNull($this->service->modifierNature($fakeNatureTab));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->natureRepositoryMock = $this->createMock(NatureRepository::class);
        $this->natureMock = $this->createMock(Nature::class);
        $this->service = new NatureService($this->natureRepositoryMock);
    }
}