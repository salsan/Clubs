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

        $this->assertEquals(7, count($info));
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
