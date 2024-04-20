<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Salsan\Clubs\Fsi;

final class FsiPageTest extends TestCase
{
    private $paramters = array(
        'clubId' => '4058',
        'year' => '2004',
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
            $this->assertStringContainsStringIgnoringCase('A.C.S. SCACCHI HERA', $club['name']);
            $this->assertStringContainsStringIgnoringCase('Salerno', $club['province']);
            $this->assertStringContainsStringIgnoringCase('CAMPANIA', $club['region']);
            $this->assertIsString($club['president']);
            $this->assertIsString($club['website']);
            // $this->assertIsNumeric($club['address']['postal_code']);
            $this->assertIsString($club['address']['street']);
            $this->assertStringContainsStringIgnoringCase('PAESTUM' , $club['address']['city']);
            $this->assertEquals(2, count($club['contact']));
            $this->assertGreaterThanOrEqual(0, count($club['councilors']));
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
