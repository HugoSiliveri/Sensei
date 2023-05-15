<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Intervenant;
use App\Sensei\Model\Repository\IntervenantRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\IntervenantService;
use PHPUnit\Framework\TestCase;
use TypeError;

class IntervenantServiceTest extends TestCase
{
    public function testRecupererParIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererParIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        self::assertEquals([], $this->service->recupererParIdentifiant(0));
    }

    public function testRecupererParIdentifiantExistant()
    {
        $fakeIntervenant = new Intervenant(6184, "Siliveri", "Hugo", 4, 1, "hugo.siliveri@etu.umontpellier.fr", null, "e20210002781", 0);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(1702)->willReturn($fakeIntervenant);
        self::assertEquals($fakeIntervenant, $this->service->recupererParIdentifiant(1702));
    }

    public function testRechercherIntervenantNull()
    {
        $this->expectException(TypeError::class);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRechercherIntervenantIncomplete()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("La recherche est incomplète !");
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->rechercherIntervenant("");
    }

    public function testRechercherIntervenantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est incorrect !");
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(1903201)->willReturn(null);
        $this->service->rechercherIntervenant("1903201 Toto Titi");
    }

    public function testRechercherIntervenantAvecUniquementNomEtPrenom()
    {
        $this->expectException(\Exception::class);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        self::assertEquals(null, $this->service->rechercherIntervenant("Toto Titi"));
    }

    public function testRechercherIntervenantExistantAvecUniquementIdentifiant()
    {
        $fakeIntervenant = new Intervenant(4903, "Andre", "Jean Baptiste", 2, 3, null, null, null, 0);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(4903)->willReturn($fakeIntervenant);
        self::assertEquals($fakeIntervenant, $this->service->rechercherIntervenant("4903"));
    }

    public function testRechercherIntervenantExistantAvecChaine()
    {
        $fakeIntervenant = new Intervenant(4903, "Andre", "Jean Baptiste", 2, 3, null, null, null, 0);
        $this->intervenantRepositoryMock->method("recupererParClePrimaire")->with(4903)->willReturn($fakeIntervenant);
        self::assertEquals($fakeIntervenant, $this->service->rechercherIntervenant("4903 Andre Jean Baptiste"));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->intervenantRepositoryMock = $this->createMock(IntervenantRepository::class);
        $this->service = new IntervenantService($this->intervenantRepositoryMock);
    }

    //Nécessite Laravel pour simuler $_GET

//    public function testRecupererRequeteIntervenantNull(){
//        $this->expectException(TypeError::class);
//        $this->intervenantRepositoryMock->method("recupererPourAutoCompletion")->with(null)->willReturn([]);
//        self::assertEquals([], $this->service->recupererRequeteIntervenant());
//    }
//
//    public function testRecupererRequeteIntervenantInexistant(){
//        $tab = ["intervenant" => "Hug Ba"];
//        $this->intervenantRepositoryMock->method("recupererPourAutoCompletion")->with($tab)->willReturn([]);
//        self::assertEquals([], $this->service->recupererRequeteIntervenant());
//    }
//
//    public function testRecupererRequeteIntervenantExistant(){
//        $tab = ["intervenant" => "Jean B"];
//        $intervenants = [];
//        $intervenants[] = new Intervenant(4903, "Andre", "Jean Baptiste", 2, 3, null, null, null, 0);
//        $intervenants[] = new Intervenant(3792, "FICHE", "Jean Bernard", 2, 3, "jean-bernard.fiche@cbs.cnrs.fr", null, null, 0);
//        $intervenants[] = new Intervenant(2190, "Morel", "Jean Benoit", 5, 3, null, "jbmorel@cirad.fr", null, 0);
//        $this->intervenantRepositoryMock->method("recupererPourAutoCompletion")->with($tab)->willReturn($intervenants);
//        self::assertEquals($intervenants, $this->service->recupererRequeteIntervenant());
//    }
}