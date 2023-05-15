<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\UniteServiceAnnee;
use App\Sensei\Model\Repository\UniteServiceAnneeRepository;
use App\Sensei\Service\Exception\ServiceException;
use App\Sensei\Service\UniteServiceAnneeService;
use PHPUnit\Framework\TestCase;
use TypeError;

class UniteServiceAnneeServiceTest extends TestCase
{
    public function testRecupererIdentifiantNull()
    {
        $this->expectException(TypeError::class);
        $this->uniteServiceAnneeRepositoryMock->method("recupererParClePrimaire")->with(null)->willReturn(null);
        $this->service->recupererParIdentifiant(null);
    }

    public function testRecupererIdentifiantInexistant()
    {
        $this->expectException(ServiceException::class);
        $this->expectExceptionMessage("L'identifiant est inconnu !");
        $this->uniteServiceAnneeRepositoryMock->method("recupererParClePrimaire")->with(0)->willReturn(null);
        $this->service->recupererParIdentifiant(0);
    }

    public function testRecupererIdentifiantExistant()
    {
        $fakeUniteServiceAnnee = new UniteServiceAnnee(9, 1, 16, null, 2004, 0, 0, 0, 5, 0, 0, 0, 0, 0, 0, 3, 0);
        $this->uniteServiceAnneeRepositoryMock->method("recupererParClePrimaire")->with(3)->willReturn($fakeUniteServiceAnnee);
        self::assertEquals($fakeUniteServiceAnnee, $this->service->recupererParIdentifiant(3));
    }

    public function testRecupererUniteServiceNull()
    {
        $this->expectException(TypeError::class);
        $this->uniteServiceAnneeRepositoryMock->method("recupererParUniteService")->with(null)->willReturn(null);
        $this->service->recupererParUniteService(null);
    }

    public function testRecupererUniteServiceInexistant()
    {
        $this->uniteServiceAnneeRepositoryMock->method("recupererParUniteService")->with(0)->willReturn([]);
        self::assertEquals([], $this->service->recupererParUniteService(0));
    }

    public function testRecupererUniteServiceExistant()
    {
        $tab = [];
        $tab[] = new UniteServiceAnnee(224, 1, 2, null, 2006, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 0);
        $this->uniteServiceAnneeRepositoryMock->method("recupererParUniteService")->with(3)->willReturn($tab);
        self::assertEquals($tab, $this->service->recupererParUniteService(3));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->uniteServiceAnneeRepositoryMock = $this->createMock(UniteServiceAnneeRepository::class);
        $this->service = new UniteServiceAnneeService($this->uniteServiceAnneeRepositoryMock);
    }
}