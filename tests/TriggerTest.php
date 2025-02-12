<?php

use Empulse\Exception\Map\LoopDetectedException;
use Empulse\Exception\MapException;
use Empulse\State\Machine\ItemInterface;
use Empulse\State\Machine\Trigger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Item implements ItemInterface{
    use \Empulse\State\Machine\Item;
    
    public static function getBitMap():array{
        return [
            'active',
            'published'
        ];
    }

    public function getTraceableId():string{
        return 'test-id';
    }
}

final class TriggerTest extends TestCase
{
    public function testNoMap(): void
    {
        $mapConfig = [];
        $this->expectException(MapException::class);

        $poi = new Item;

        $trigger = new Trigger();
        $trigger->addNewQueueItem(
            $mapConfig, $poi
        );
    }

    public function testMap(): void
    {
        require dirname(__FILE__).'/Map/ProcessingMap.php';

        $poi = new Item;

        $trigger = new Trigger;
        $trigger->addNewQueueItem(
            $mapConfig, [$poi]
        );

        $this->assertEquals(1, count($trigger->getQueuedItems()));
    }

    public function testOneTransition(): void
    {
        require dirname(__FILE__).'/Map/ProcessingMap.php';

        $item = new Item;

        $trigger = new Trigger;
        $trigger->push([$item], $mapConfig);

        $this->assertEquals('processing', $item->getState());
    }

    public function testToFinalState(): void
    {
        require dirname(__FILE__).'/Map/DeliveredMap.php';

        $item = new Item;

        $trigger = new Trigger;
        $trigger->push([$item], $mapConfig);

        $this->assertEquals('delivered', $item->getState());
    }

    public function testInfiniteLoopTransition(): void
    {

        $this->expectException(LoopDetectedException::class);

        require dirname(__FILE__).'/Map/LoopMap.php';

        $item = new Item;

        $trigger = new Trigger;
        $trigger->push([$item], $mapConfig);
    }

    public function testStopAfterApply(): void
    {

        require dirname(__FILE__).'/Map/StopAfterApplyMap.php';

        $item = new Item;

        $trigger = new Trigger;
        $trigger->push([$item], $mapConfig);

        $this->assertEquals('processing', $item->getState());
    }
}
