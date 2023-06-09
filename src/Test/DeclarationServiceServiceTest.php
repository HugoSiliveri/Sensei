<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\DeclarationService;
use App\Sensei\Model\Repository\DeclarationServiceRepository;
use App\Sensei\Service\DeclarationServiceService;
use PHPUnit\Framework\TestCase;

class DeclarationServiceServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->declarationServiceRepositoryMock = $this->createMock(DeclarationServiceRepository::class);
        $this->service = new DeclarationServiceService($this->declarationServiceRepositoryMock);
    }

    public function testRecupererParIdIntervenantInexistant(){
        $this->declarationServiceRepositoryMock->method("recupererParIdIntervenant")->with(0)->willReturn([]);
        self::assertEquals([], $this->service->recupererParIdIntervenant(0));
    }

    public function testRecupererParIdIntervenantExistant(){
        $fakeDeclarationService = new DeclarationService(1, 105, 89, "OK", 506);
        $this->declarationServiceRepositoryMock->method("recupererParIdIntervenant")->with(105)->willReturn([$fakeDeclarationService]);
        self::assertEquals([$fakeDeclarationService], $this->service->recupererParIdIntervenant(105));
    }

    public function testRecupererVueParIdIntervenantInexistant(){
        $this->declarationServiceRepositoryMock->method("recupererVueParIdIntervenant")->with(0)->willReturn([]);
        self::assertEquals([], $this->service->recupererVueParIdIntervenant(0));
    }

    public function testRecupererVueParIdIntervenantExistant(){
        $fakeArray = [2015, "HMBS302", "Biocapteurs et laboratoires sur puces", "CM", 1, 2 , null, 4144];
        $this->declarationServiceRepositoryMock->method("recupererParIdIntervenant")->with(4144)->willReturn($fakeArray);
        self::assertEquals($fakeArray, $this->service->recupererParIdIntervenant(4144));
    }

    public function testRecupererParIdUSAInexistant(){
        $this->declarationServiceRepositoryMock->method("recupererParIdUSA")->with(0)->willReturn([]);
        self::assertEquals([], $this->service->recupererParIdUSA(0));
    }

    public function testRecupererParIdUSAExistant(){
        $fakeArray = [];
        $fakeArray[] = new DeclarationService(194, 54, 3, "OK", 1688);
        $fakeArray[] = new DeclarationService(623, 54, 3, "VOEU", 1688);
        $this->declarationServiceRepositoryMock->method("recupererParIdUSA")->with(4144)->willReturn($fakeArray);
        self::assertEquals($fakeArray, $this->service->recupererParIdUSA(4144));
    }
}