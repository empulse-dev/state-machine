<?php

use Empulse\Exception\Map\LoopDetectedException;
use Empulse\Exception\MapException;
use Empulse\State\Machine\ItemInterface;
use Empulse\State\Machine\Trigger;
use Empulse\State\Machine\Validator\FlagOnValidator;
use PHPUnit\Framework\TestCase;

class Comparison implements ItemInterface{
    use \Empulse\State\Machine\Item;
    
    public static function getBitMap():array{
        return [
            'active'
        ];
    }

    public function getTraceableId():string{
        return $this->get('code');
    }
}

final class ComparisonTest extends TestCase
{
    public function testReviewShowCreation(): void
    {
        require dirname(__FILE__).'/Map/ComparisonMap.php';

        $comparison = new Comparison;

        $comparison->setData([
            'code' => 'test-code-'.__METHOD__,
            'uno' => 1,
            'dos' => 2,
            'tres' => 3
        ]);

        $trigger = new Trigger;
        
        $trigger->push([$comparison], $mapConfig);
        $this->assertEquals('done', $comparison->getState());        
    }
}
