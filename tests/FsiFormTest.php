<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Salsan\Clubs\Fsi;


final class FsiFormTest extends TestCase
{
    public function testInit(): object
    {
        $form = new Fsi\Form();
        $this->assertIsObject($form);

        return $form;
    }

    /**
     * @depends testInit
     */

    public function testGetRegions($form): void
    {
        $regions = $form->getRegions();
        $this->assertIsArray($regions);
        $this->assertGreaterThanOrEqual(1, count($regions));
    }

    /**
     * @depends testInit
     */

    public function testGetProvinces($form): void
    {
        $provinces = $form->getProvinces();
        $this->assertIsArray($provinces);
        $this->assertGreaterThanOrEqual(1, count($provinces));
    }

    /**
     * @depends testInit
     */

    public function testGetOrder($form): void
    {
        $order = $form->getOrder();
        $this->assertIsArray($order);
        $this->assertGreaterThanOrEqual(1, count($order));
    }

    /**
     * @depends testInit
     */

    public function testgetDirection($form): void
    {
        $direction = $form->getDirection();
        $this->assertIsArray($direction);
        $this->assertGreaterThanOrEqual(1, count($direction));
    }

    /**
     * @depends testInit
     */

    public function testgetYears($form): void
    {
        $years = $form->getYears();
        $this->assertIsArray($years);
        $this->assertGreaterThanOrEqual(1, count($years));
    }
}
