<?php

namespace App\Sensei\Test\ServiceTest;

use App\Sensei\Model\DataObject\ServiceAnnuel;
use App\Sensei\Model\Repository\ServiceAnnuelRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\ServiceAnnuelService;
use PHPUnit\Framework\TestCase;
use TypeError;

class ServiceAnnuelServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->serviceAnnuelRepositoryMock = $this->createMock(ServiceAnnuelRepository::class);
        $this->service = new ServiceAnnuelService($this->serviceAnnuelRepositoryMock);
    }

    public function testRecupererParIntervenantNull(){
        $this->expectException(TypeError::class);
        $this->serviceAnnuelRepositoryMock->method("recupererParIntervenant")->with(null)->willReturn([]);
        $this->service->recupererParIntervenant(null);
    }

    public function testRecupererParIntervenantInexistant(){
        $this->serviceAnnuelRepositoryMock->method("recupererParIntervenant")->with(0)->willReturn([]);
        self::assertEquals([], $this->service->recupererParIntervenant(0));
    }

    public function testRecupererIdentifiantExistantAvecUnResultat(){
        $tab = [];
        // Au total, l'intervenant 13 à réalisé qu'un seul service annuel
        $tab[] = new ServiceAnnuel(80, 1, 13, 2004, 10, 0, 0, 0, 0);
        $this->serviceAnnuelRepositoryMock->method("recupererParIntervenant")->with(13)->willReturn($tab);
        self::assertEquals($tab, $this->service->recupererParIntervenant(13));
    }

    public function testRecupererIdentifiantExistantAvecPlusieursResultats(){
        $tab = [];
        // Au total, l'intervenant 23 à réalisé deux services annuels
        $tab[] = new ServiceAnnuel(73, 1, 23, 2004, 4, 96, 0, 0, 0);
        $tab[] = new ServiceAnnuel(288, 2, 23, 2006, 30, 0, 0, 0, 0);
        $this->serviceAnnuelRepositoryMock->method("recupererParIntervenant")->with(23)->willReturn($tab);
        self::assertEquals($tab, $this->service->recupererParIntervenant(23));
    }
}