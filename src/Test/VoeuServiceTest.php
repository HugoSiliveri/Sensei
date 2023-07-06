<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Voeu;
use App\Sensei\Model\Repository\VoeuRepository;
use App\Sensei\Service\VoeuService;
use PHPUnit\Framework\TestCase;
use TypeError;

class VoeuServiceTest extends TestCase
{
    public function testRecupererParIdUSANull()
    {
        $this->expectException(TypeError::class);
        $this->voeuRepositoryMock->method("recupererParIdUSA")->with(null)->willReturn([]);
        $this->service->recupererParIdUSA(null);
    }

    public function testRecupererParIdUSAInexistant()
    {
        $this->voeuRepositoryMock->method("recupererParIdUSA")->with(0)->willReturn([]);
        self::assertEquals([], $this->service->recupererParIdUSA(0));
    }

    public function testRecupererParIdUSAExistant()
    {
        $tab = [];
        $tab[] = new Voeu(21, 10, 1702, 18);
        $tab[] = new Voeu(22, 10, 1702, 19);
        $tab[] = new Voeu(24, 10, 1702, 21);
        $this->voeuRepositoryMock->method("recupererParIdUSA")->with(1702)->willReturn($tab);
        self::assertEquals($tab, $this->service->recupererParIdUSA(1702));
    }

    public function testRecupererParIntervenantNull()
    {
        $this->expectException(TypeError::class);
        $this->voeuRepositoryMock->method("recupererVueParIntervenant")->with(null)->willReturn([]);
        self::assertEquals([], $this->service->recupererVueParIntervenant(null));
    }

    public function testRecupererParIntervenantInexistant()
    {
        $this->voeuRepositoryMock->method("recupererVueParIntervenant")->with(0)->willReturn([]);
        self::assertEquals([], $this->service->recupererVueParIntervenant(0));
    }

    public function testRecupererParIntervenantExistant()
    {
        $tab = [];
        $tab[] = new Voeu(206, 74, 3537, 96);
        $tab[] = new Voeu(207, 74, 3551, 97);
        $this->voeuRepositoryMock->method("recupererVueParIntervenant")->with(74)->willReturn($tab);
        self::assertEquals($tab, $this->service->recupererVueParIntervenant(74));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->voeuRepositoryMock = $this->createMock(VoeuRepository::class);
        $this->service = new VoeuService($this->voeuRepositoryMock);
    }
}