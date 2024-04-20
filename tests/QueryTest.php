<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Salsan\Clubs;

final class QueryClubTest extends TestCase
{
    private $paramters = array(
        'clubId' => '15101',
        'year' => '2023',
    );

    public function testInit(): object
    {
        $clubs = new Clubs\Query($this->paramters);
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
            $this->assertStringContainsStringIgnoringCase('Catania', $club['province']);
            $this->assertStringContainsStringIgnoringCase('SICILIA', $club['region']);
            $this->assertStringContainsStringIgnoringCase('3517674477', $club['contact']['tel']);
            $this->assertStringContainsStringIgnoringCase('accadpcarrera@email.it', $club['contact']['email']);
            $this->assertStringContainsStringIgnoringCase('LEONARDI DANIELE', $club['president']);
            $this->assertContains('SANTAGATI ALESSANDRO', $club['councilors']);
            $this->assertStringContainsStringIgnoringCase('https://www.accademiacarrera.it', $club['website']);
            $this->assertStringContainsStringIgnoringCase($club['address']['postal_code'], '95100');
            $this->assertStringContainsStringIgnoringCase('VIA CASA NUTRIZIONE, 38', $club['address']['street']);
            $this->assertStringContainsStringIgnoringCase('Catania', $club['address']['city']);
            $this->assertEquals(2, count($club['contact']));
            $this->assertGreaterThanOrEqual(3, count($club['councilors']));
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
