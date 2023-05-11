<?php

namespace App\Sensei\Test\ServiceTest;

use App\Sensei\Model\DataObject\Intervention;
use App\Sensei\Model\Repository\InterventionRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\InterventionService;
use PHPUnit\Framework\TestCase;
use TypeError;

class InterventionServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->interventionRepositoryMock = $this->createMock(InterventionRepository::class);
        $this->service = new InterventionService($this->interventionRepositoryMock);
    }

    public function testRecupererIdentifiantNull(){
        $this->expectException(TypeError::class);
        $this->interventionRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->interventionRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant(){
        $fakeIntervention = new Intervention(23, "Stage", 1, 4.2);
        $this->interventionRepositoryMock->method("recupererParClePrimaire")->with(23)->willReturn($fakeIntervention);
        self::assertEquals($fakeIntervention, $this->service->recupererParIdentifiant(23));
    }
}