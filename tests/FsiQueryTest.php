<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Salsan\Clubs\Fsi;

final class FsiQueryTest extends TestCase
{
    private $paramters = array(
        'clubId' => '15101',
        'year' => '2023',
    );

    public function testInit(): object
    {
        $clubs = new Fsi\Query($this->paramters);
        $this->assertIsObject($clubs);

        return $clubs;
    }

    /**
     * @depends testInit
     */
    public function testGetInfo($clubs): void
    {
        $info = $clubs->getInfo();

        foreach ($info as $club) {
            $this->assertStringContainsStringIgnoringCase('ASD ACCADEMIA SCACCHISTICA DON PIETRO CARRERA', $club['name']);
            $this->assertStringContainsStringIgnoringCase('CT', $club['province']);
            $this->assertStringContainsStringIgnoringCase('SICILIA', $club['region']);
            $this->assertIsString($club['president']);
            $this->assertIsString($club['website']);
            $this->assertIsNumeric($club['address']['postal_code']);
            $this->assertIsString($club['address']['street']);
            $this->assertStringContainsStringIgnoringCase('Catania' , $club['address']['city']);
            $this->assertEquals(2, count($club['contact']));
            $this->assertGreaterThanOrEqual(1, count($club['councilors']));
        }
    }

    /**
     * @depends testInit
     */
    public function testGetNumber($clubs): void
    {
        $this->assertEquals($clubs->getNumber(), '1');
    }
}
