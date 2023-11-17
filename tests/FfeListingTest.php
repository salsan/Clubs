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
     *  @depends testInit
     * 
     * 
     */
    public function testGetNumberRows($listing): void
    {
        $this->assertEquals(42, $listing->get_number_rows());
    }


    /**
     * @depends testInit
     */
    public function testGetPageNumber($listing): void
    {
        $this->assertEquals(23, $listing->get_page_number());
    }

    /**
     * @depends testInit
     */
    public function testGetListClubs($listing): void
    {
        $this->assertIsArray($listing->clubs());
    }
}
