<?php

use Empulse\Exception\MapException;
use Empulse\State\Machine\Item;
use Empulse\State\Machine\Trigger;
use PHPUnit\Framework\TestCase;

final class TriggerTest extends TestCase
{
    public function testNoMap(): void
    {
        $mapConfig = [];
        $this->expectException(MapException::class);

        $poi = new Item([]);

        $trigger = new Trigger;
        $trigger->addNewQueueItem(
            $mapConfig, $poi
        );
    }

    public function testMap(): void
    {
        require dirname(__FILE__).'/Map/SaleMap.php';

        $poi = new Item([]);

        $trigger = new Trigger;
        $trigger->addNewQueueItem(
            $mapConfig, [$poi]
        );

        $this->assertEquals(1, count($trigger->getQueuedItems()));
    }

    public function testSetInitialState(): void
    {
        require dirname(__FILE__).'/Map/SaleMap.php';

        $item = new Item([]);

        $trigger = new Trigger;
        $trigger->push([$item], $mapConfig);

        $this->assertEquals('processing', $item->getItemState());
    }
}
