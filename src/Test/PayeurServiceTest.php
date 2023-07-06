<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Payeur;
use App\Sensei\Model\Repository\PayeurRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\PayeurService;
use PHPUnit\Framework\TestCase;
use TypeError;

class PayeurServiceTest extends TestCase
{
    public function testRecupererIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->payeurRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->payeurRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant()
    {
        $fakePayeur = new Payeur(1, "FdS");
        $this->payeurRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakePayeur);
        self::assertEquals($fakePayeur, $this->service->recupererParIdentifiant(1));
    }

    public function testCreerPayeurVide()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("Aucune information fournie !");
        $this->payeurRepositoryMock->method("ajouterSansIdPayeur")->with([])->willReturn(null);
        $this->service->creerPayeur([]);
    }

    public function testCreerPayeurPasVide()
    {
        $array = [10, 9];
        $this->payeurRepositoryMock->method("ajouterSansIdPayeur")->with($array)->willReturn(null);
        self::assertNull($this->service->creerPayeur($array));
    }

    public function testSupprimerPayeur()
    {
        $this->payeurRepositoryMock->method("supprimer")->with(0);
        self::assertNull($this->service->supprimerPayeur(0));
    }

    public function testModifierPayeurInexistante()
    {
        $this->expectException(ServiceException::class);
        $this->payeurRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->modifierPayeur([
            "idPayeur" => 0,
            "libPayeur" => "UAIM"]);
    }

    public function testModifierPayeurExistante()
    {
        $fakePayeur = new Payeur(1, "administrateur.e");
        $fakePayeurTab = [
            "idPayeur" => 1,
            "libPayeur" => "FdS"];
        $this->payeurRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakePayeur);
        $this->payeurMock->method("setIdPayeur")->with(1);
        $this->payeurMock->method("setLibPayeur")->with("UM");
        self::assertNull($this->service->modifierPayeur($fakePayeurTab));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->payeurRepositoryMock = $this->createMock(PayeurRepository::class);
        $this->payeurMock = $this->createMock(Payeur::class);
        $this->service = new PayeurService($this->payeurRepositoryMock);
    }
}