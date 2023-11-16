<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use Salsan\Clubs\Fsi;

final class ListingTest extends TestCase
{
    public function testInit(): object
    {
        $listing = new Fsi\Listing();
        $this->assertIsObject($listing);

        return $listing;
    }

    /**
     * @depends testInit
     */
    public function testGetNumber($listing): void
    {
        $this->assertGreaterThanOrEqual(1, count($listing->clubs()));
    }

    /**
     * @depends testInit
     */

    public function testGetNameFromId($listing): void
    {
        $clubName = $listing->getNameFromId(15101);
        $this->assertStringContainsStringIgnoringCase('ASD ACCADEMIA SCACCHISTICA DON PIETRO CARRERA', $clubName);
    }

    /**
     * @depends testInit
     */

    public function testGetNameFromFirstId($listing): void
    {
        $clubName = $listing->getNameFromId(1001);
        $this->assertStringContainsStringIgnoringCase('CIRCOLO SCACCHISTICO AQUILANO', $clubName);
    }
}
