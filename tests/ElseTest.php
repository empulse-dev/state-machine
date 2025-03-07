<?php

use Empulse\Exception\Map\LoopDetectedException;
use Empulse\Exception\MapException;
use Empulse\State\Machine\ItemInterface;
use Empulse\State\Machine\Trigger;
use Empulse\State\Machine\Validator\FlagOnValidator;
use PHPUnit\Framework\TestCase;

class Offer implements ItemInterface{
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

final class ElseTest extends TestCase
{
    public function testRight(): void
    {
        require dirname(__FILE__).'/Map/ElseMap.php';

        $offer = new Offer;

        $offer->setData([
            'code' => 'test-code-'.__METHOD__,
            'offer' => 110,
            'limit' => 100
        ]);

        $trigger = new Trigger;
        
        $trigger->push([$offer], $mapConfig);
        $this->assertEquals('accepted', $offer->getState());        
    }

    public function testLeft(): void
    {
        require dirname(__FILE__).'/Map/ElseMap.php';

        $offer = new Offer;

        $offer->setData([
            'code' => 'test-code-'.__METHOD__,
            'offer' => 90,
            'limit' => 100
        ]);

        $trigger = new Trigger;
        
        $trigger->push([$offer], $mapConfig);
        $this->assertEquals('rejected', $offer->getState());        
    }
}
