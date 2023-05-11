<?php

namespace App\Sensei\Test\ServiceTest;

use App\Sensei\Model\DataObject\Emploi;
use App\Sensei\Model\Repository\EmploiRepository;
use App\Sensei\Service\EmploiService;
use App\Sensei\Service\Exception\ServiceException;
use PHPUnit\Framework\TestCase;
use TypeError;

class EmploiServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->emploiRepositoryMock = $this->createMock(EmploiRepository::class);
        $this->service = new EmploiService($this->emploiRepositoryMock);
    }

    public function testRecupererIdentifiantNull(){
        $this->expectException(TypeError::class);
        $this->emploiRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant(){
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->emploiRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant(){
        $fakeEmploi = new Emploi(7, "Vacataire");
        $this->emploiRepositoryMock->method("recupererParClePrimaire")->with(7)->willReturn($fakeEmploi);
        self::assertEquals($fakeEmploi, $this->service->recupererParIdentifiant(7));
    }
}