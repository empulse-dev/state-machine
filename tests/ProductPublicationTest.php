<?php

use Empulse\Exception\Map\LoopDetectedException;
use Empulse\Exception\MapException;
use Empulse\State\Machine\ItemInterface;
use Empulse\State\Machine\Trigger;
use PHPUnit\Framework\TestCase;

class Product implements ItemInterface{
    use \Empulse\State\Machine\Item;
    
    public static function getBitMap():array{
        return [
            'active',
            'published'
        ];
    }
}

final class ProductPublicationTest extends TestCase
{
    public function testFlagAndSetDataOnProductScenario(): void
    {
        require dirname(__FILE__).'/Map/ProductMap.php';

        $item = new Product;

        $item->setData([
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
            ->setFlag('active')
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

        $item->setData([
            'name' => 'Producto',
            'price' => 12.34,
            'image' => 'some/url.jpg'
        ]);

        $trigger = new Trigger;
        
        $trigger->push([$item], $mapConfig);
        $this->assertEquals('created', $item->getState());
    }
}
