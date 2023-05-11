<?php

namespace App\Sensei\Test\ServiceTest;

use App\Sensei\Model\DataObject\Statut;
use App\Sensei\Model\Repository\StatutRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\StatutService;
use PHPUnit\Framework\TestCase;
use TypeError;

class StatutServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->statutRepositoryMock = $this->createMock(StatutRepository::class);
        $this->service = new StatutService($this->statutRepositoryMock);
    }

    public function testRecupererIdentifiantNull(){
        $this->expectException(TypeError::class);
        $this->statutRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->statutRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant(){
        $fakeStatut = new Statut(3, "gestionnaire", null);
        $this->statutRepositoryMock->method("recupererParClePrimaire")->with(3)->willReturn($fakeStatut);
        self::assertEquals($fakeStatut, $this->service->recupererParIdentifiant(3));
    }
}