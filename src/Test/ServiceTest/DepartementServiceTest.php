<?php

namespace App\Sensei\Test\ServiceTest;

use App\Sensei\Model\DataObject\Departement;
use App\Sensei\Model\Repository\DepartementRepository;
use App\Sensei\Service\DepartementService;
use App\Sensei\Service\Exception\ServiceException;
use PHPUnit\Framework\TestCase;
use TypeError;

class DepartementServiceTest extends TestCase
{
    private $service;
    private $departementRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->departementRepositoryMock = $this->createMock(DepartementRepository::class);
        $this->service = new DepartementService($this->departementRepositoryMock);
    }

    public function testRecupererIdentifiantNull(){
        $this->expectException(TypeError::class);
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant(){
        $fakeDepartement = new Departement(1, "MATH", "MA", 25, 1, 2);
        $this->departementRepositoryMock->method("recupererParClePrimaire")->with(1)->willReturn($fakeDepartement);
        self::assertEquals($fakeDepartement, $this->service->recupererParIdentifiant(1));
    }
}