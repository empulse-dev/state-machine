<?php

use Empulse\Exception\Map\LoopDetectedException;
use Empulse\Exception\MapException;
use Empulse\State\Machine\ItemInterface;
use Empulse\State\Machine\Trigger;
use Empulse\State\Machine\Validator\FlagOnValidator;
use PHPUnit\Framework\TestCase;

class Product implements ItemInterface{
    use \Empulse\State\Machine\Item;
    
    public static function getBitMap():array{
        return [
            'active',
            'published'
        ];
    }

    public function getTraceableId():string{
        return $this->get('code');
    }
}

final class ProductPublicationTest extends TestCase
{
    public function testFullTransitionProductScenario(): void
    {
        require dirname(__FILE__).'/Map/ProductMap.php';

        $item = new Product;

        $item->setData([
            'code' => 'test-code-'.__METHOD__,
            'name' => 'Producto',
        ]);

        $trigger = new Trigger;
        
        $trigger->push([$item], $mapConfig);
        $this->assertEquals('created', $item->getState());
        
        $item->setFlag('active');

        //ENRICH/////////////////////////////////
        $item->setData([
            'price' => 12.34
        ]);

        $trigger->push([$item], $mapConfig);
        $this->assertEquals('enrich', $item->getState());

        //PUBLISH/////////////////////////////////
        $item
            ->setData([
                'image' => 'some/url.jpg'
            ]);

        $trigger->push([$item], $mapConfig);
        $this->assertEquals('publish', $item->getState());

        //DISABLE/////////////////////////////////
        $item->removeFlag('active');

        $trigger->push([$item], $mapConfig);
        $this->assertEquals('disable', $item->getState());

        //RE ENABLE/////////////////////////////////
        $item->setFlag('active');

        $trigger->push([$item], $mapConfig);
        $this->assertEquals('publish', $item->getState());
    }

    public function testDirectToPublishProductScenario(): void
    {
        require dirname(__FILE__).'/Map/ProductMap.php';

        $item = new Product;

        $item->setData([
            'code' => 'test-code-'.__METHOD__,
            'name' => 'Producto',
            'price' => 12.34,
            'image' => 'some/url.jpg'
        ]);

        $item->setFlag('active');

        $trigger = new Trigger;
        
        $trigger->push([$item], $mapConfig);
        $this->assertEquals('publish', $item->getState());
    }

    public function testStayOnCreatedProductScenario(): void
    {
        require dirname(__FILE__).'/Map/ProductMap.php';

        $item = new Product;

        $code = 'test-code-'.__METHOD__;

        $item->setData([
            'code' => $code,
            'name' => 'Producto',
            'price' => 12.34,
            'image' => 'some/url.jpg'
        ]);

        $trigger = new Trigger;
        
        $trigger->push([$item], $mapConfig);
        $this->assertEquals('created', $item->getState());

        // var_dump($trigger->getTrackerCollection());

        $execution = $trigger->getTrackerCollection();
        $step = array_pop($execution);
        
        $firstValidation = $step->getTrackerCollection()[0];

        $this->assertEquals(false, $firstValidation->getResult());
        $this->assertEquals(FlagOnValidator::class, $firstValidation->getValidator());
    }
}
