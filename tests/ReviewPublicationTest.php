<?php

use Empulse\Exception\Map\LoopDetectedException;
use Empulse\Exception\MapException;
use Empulse\State\Machine\ItemInterface;
use Empulse\State\Machine\Trigger;
use Empulse\State\Machine\Validator\FlagOnValidator;
use PHPUnit\Framework\TestCase;

class Review implements ItemInterface{
    use \Empulse\State\Machine\Item;
    
    public static function getBitMap():array{
        return [
            'active',
            'published',
            'deleted'
        ];
    }

    public function getTraceableId():string{
        return $this->get('code');
    }
}

final class ReviewPublicationTest extends TestCase
{
    public function testReviewShowCreation(): void
    {
        require dirname(__FILE__).'/Map/ReviewMap.php';

        $review = new Review;

        $review->setData([
            'code' => 'test-code-'.__METHOD__,
            'sku' => '11_1',
            'comments' => 'me encanta',
            'stars' => 5
        ]);

        $trigger = new Trigger;
        
        $trigger->push([$review], $mapConfig);
        $this->assertEquals('validation', $review->getState());
        
        //APPROBED/////////////////////////////////
        $review->setData([
            'approbed_by' => 'gio@empulse.dev'
        ]);

        $trigger->push([$review], $mapConfig);
        $this->assertEquals('show', $review->getState());
        $this->assertEquals(true, (bool)$review->getFlag('active'));
        $this->assertEquals(false, (bool)$review->getFlag('disable'));
        
    }

    public function testReviewHideCreation(): void
    {
        require dirname(__FILE__).'/Map/ReviewMap.php';

        $review = new Review;

        $review->setData([
            'code' => 'test-code-'.__METHOD__,
            'sku' => '11_1',
            'comments' => 'No me encanta',
            'stars' => 1
        ]);

        $trigger = new Trigger;
        
        $trigger->push([$review], $mapConfig);
        $this->assertEquals('validation', $review->getState());
        
        //APPROBED/////////////////////////////////
        $review->setData([
            'approbed_by' => 'gio@empulse.dev'
        ]);

        $trigger->push([$review], $mapConfig);
        $this->assertEquals('hide', $review->getState());
        $this->assertEquals(true, (bool)$review->getFlag('active'));
        $this->assertEquals(false, (bool)$review->getFlag('disable'));
    }
}
