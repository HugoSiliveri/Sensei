<?php

namespace App\Sensei\Test;

use App\Sensei\Model\DataObject\Coloration;
use App\Sensei\Model\Repository\ColorationRepository;
use App\Sensei\Service\ColorationService;
use PHPUnit\Framework\TestCase;

class ColorationServiceTest extends TestCase
{
    public function testRecupererParIdUniteServiceAnneeInexistant()
    {
        $this->colorationRepositoryMock->method("recupererParIdUniteServiceAnnee")->with(0)->willReturn([]);
        self::assertEquals([], $this->service->recupererParIdUniteServiceAnnee(0));
    }

    public function testRecupererParIdUniteServiceAnneeExistant()
    {
        $fakeColoration = new Coloration(1, 1059);
        $this->colorationRepositoryMock->method("recupererParIdUniteServiceAnnee")->with(1059)->willReturn([$fakeColoration]);
        self::assertEquals([$fakeColoration], $this->service->recupererParIdUniteServiceAnnee(1059));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->colorationRepositoryMock = $this->createMock(ColorationRepository::class);
        $this->service = new ColorationService($this->colorationRepositoryMock);
    }
}