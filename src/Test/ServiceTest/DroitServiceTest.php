<?php

namespace App\Sensei\Test\ServiceTest;

use App\Sensei\Model\DataObject\Droit;
use App\Sensei\Model\Repository\DroitRepository;
use App\Sensei\Service\DroitService;
use App\Sensei\Service\Exception\ServiceException;
use PHPUnit\Framework\TestCase;
use TypeError;

class DroitServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->droitRepositoryMock = $this->createMock(DroitRepository::class);
        $this->service = new DroitService($this->droitRepositoryMock);
    }

    public function testRecupererIdentifiantNull(){
        $this->expectException(TypeError::class);
        $this->droitRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->droitRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant(){
        $fakeDroit = new Droit(3, "Enseignant");
        $this->droitRepositoryMock->method("recupererParClePrimaire")->with(3)->willReturn($fakeDroit);
        self::assertEquals($fakeDroit, $this->service->recupererParIdentifiant(3));
    }
}