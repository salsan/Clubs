<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;
use Salsan\Clubs\Ffe;

final class FfeListingTest extends TestCase
{
    public function testInit(): object
    {
        $listing = new Ffe\Listing();
        $this->assertIsObject($listing);

        return $listing;
    }

    /**
     * @depends testInit
     */
    public function testGetPageNumber($listing): void
    {
        $this->assertEquals(23, $listing->getPageNumber());
    }

    /**
     * @depends testInit
     */
    public function testGetListClubs($listing): void
    {
        $clubs = $listing->clubs();
        $this->assertIsArray($clubs);
        $this->assertGreaterThanOrEqual(800, count($clubs));
    }

    /**
     * @depends testInit
     */
    public function testGetNumber($listing): void
    {
        $this->assertGreaterThanOrEqual(1, count($listing->clubs()));
    }
}
