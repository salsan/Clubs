<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Salsan\Clubs;

final class QueryClubsTest extends TestCase
{
    private $paramters = array(
        'pro' => 'CT',
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

        // foreach ($info as $club) {
        //     $this->assertStringContainsStringIgnoringCase('ASD ACCADEMIA SCACCHISTICA DON PIETRO CARRERA', $club['name']);
        //     $this->assertStringContainsStringIgnoringCase('Catania', $club['province']);
        //     $this->assertStringContainsStringIgnoringCase('SICILIA', $club['region']);
        //     $this->assertStringContainsStringIgnoringCase('3517674477', $club['contact']['tel'] );
        //     $this->assertStringContainsStringIgnoringCase('accadpcarrera@email.it', $club['contact']['email']);
        //     $this->assertStringContainsStringIgnoringCase('LEONARDI DANIELE', $club['president']);
        //     $this->assertContains('SANTAGATI ALESSANDRO', $club['councilors']);
        //     $this->assertIsString($club['website']);
        //     $this->assertIsNumeric($club['address']['postal_code']);
        //     $this->assertIsString($club['address']['street']);
        //     $this->assertStringContainsStringIgnoringCase('Catania' , $club['address']['city']);
        //     $this->assertEquals(2, count($club['contact']));
        //     $this->assertGreaterThanOrEqual(3, count($club['councilors']));
        // }

        $this->assertEquals( 7 , count($info));
        // print_r($info);
    }

    /**
     * @depends testInit
     */
    public function testGetNumber($clubs): void
    {
        $this->assertEquals($clubs->getNumber(), '7');
    }
}
