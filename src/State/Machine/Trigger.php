<?php

namespace Empulse\State\Machine;
use Empulse\Exception\MapException;
use Empulse\State\Machine;
use Empulse\State\Machine\Item;
use Empulse\State\Map;

class Trigger {

    protected $queue = [];

    protected $items = [];

    protected $flux = [];
    
    /**
     * Create Queue Item
     *
     * @param string         $fluxCode
     * @param array|callable $items
     *
     * @return \Empulse\State\Machine\Queue\Item
     */
    public static function createQueueItem(string $fluxCode, $items)
    {
        return new \Empulse\State\Machine\Queue\Item($fluxCode, $items);
    }

    /**
     * Workflow asset
     *
     * @param WorkflowAssetInterface $workflowAsset
     */
    public function setWorkflowAsset($workflowAsset)
    {
        $this->workflowAsset = $workflowAsset;
    }

    /**
     * Push items
     *
     * @param array|callable    $items
     * @param array             $flux
     *
     * @throws \Exception
     */
    public function push($items, $flux)
    {
        $this->addNewQueueItem($flux, $items);
        $this->processQueue();
    }

    /**
     * Add to queue
     *
     * @param Item $queueItem
     */
    public function addToQueue($queueItem)
    {
       $this->queue[] = $queueItem;
    }

    public function getQueuedItems(){
        return $this->queue;
    }

    /**
     * @todo validate if the map contains:
     * 1 initial state
     * 1 or more final states
     * all states are reachable
     * loops are allowed
     * 
     * @return bool
     * @throws \Empulse\Exception\MapException
     */
    protected function _isAValidMap($map): bool{
        if(empty($map)){
            throw new MapException('Empty Mapping', 1001);
        }

        return true;
    }

    /**
     * Create Queue Item & add to queue
     *
     * @param array         $flux
     * @param \Empulse\State\Machine\EntityInterface|callable $items
     */
    public function addNewQueueItem($flux, $items)
    {
        
        $this->_isAValidMap($flux);
        $code = $flux[Map::MAP_CODE];

        if(!isset($this->flux[$code])){
            $this->_isAValidMap($flux);
            $this->flux[$code] = $flux;
        } 
        
        $this->addToQueue(
            self::createQueueItem($code, $items)
        );
    }

    /**
     * Process queue
     *
     * @throws \Exception
     */
    public function processQueue()
    {
        while (count($this->queue) > 0) {
            $queueItem = array_pop($this->queue);
            if ($queueItem instanceof \Empulse\State\Machine\Queue\Item) {
                $this->pushWorkflowItems(
                    $queueItem->getItems(), 
                    $queueItem->getFlux()
                );
            } elseif ($queueItem instanceof QueueWorkflowInterface) {
                $queueItem->push();
            } else {
                throw new \Exception(sprintf('unknown queue item %s', get_class($queueItem)));
            }
        }
    }

    /**
     * Items in transition
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Clear Transition Items
     *
     * @return $this
     */
    public function clearTransitionItems()
    {
        $this->items = [];

        return $this;
    }

    /**
     * Push workflow items
     *
     * @param array|callable $items
     * @param string         $flux
     *
     * @return array
     * @throws \Exception
     */
    protected function pushWorkflowItems($items, $fluxCode)
    {
        try {
            $items = is_callable($items) ? $items() : $items;

            // $pushEvent = new PushEvent($items, $flux, $this);
            // $this->beforePush($pushEvent);

            $stateMachine = new Machine();
            
            foreach ($items as $item) {
                if (!in_array($item, $this->items, true)) {
                    $this->items[] = $item;
                }

                $stateMachine->initialize($item, $this->flux[$fluxCode]);
                
                // $workflow = $this->prepare($flux, $item);
                // $pushItemEvent = new PushItemEvent($item, $workflow);
                // $this->beforePushItem($pushItemEvent);
                // $workflow->setObject($item);
                
                $stateMachine->push();

                // try {
                    
                // } catch (WorkflowPushException $e) {
                //     throw $e;
                // } catch (\Exception $e) {
                    // $this->workflowLogger->error(sprintf(
                    //     '[PushException] Flux: %s, POI: %s, User: %s, Message: %s, File: %s, Line: %s', get_class($workflow),
                    //     $workflow->getObject()->getPurchaseOrderItemId(),
                    //     $this->getLoggedUser(),
                    //     $e->getMessage(),
                    //     $e->getFile(),
                    //     $e->getLine()
                    // ));
                // }

                // $this->afterPushItem($pushItemEvent);

            }
            // $this->afterPush($pushEvent);
            // if (!$this->dryRun) {

            // }
        } catch (WorkflowPushException $e) {
            
            throw $e;
        } catch (\Exception $e) {
            
            throw $e;
        }

        return $items;
    }

    /**
     * Before push item.
     *
     * @param PushEvent $pushEvent
     */
    protected function beforePush(PushEvent $pushEvent)
    {
        $this->dispatcher->dispatch(PushEvent::BEFORE_PUSH, $pushEvent);
    }

    /**
     * Before push item.
     *
     * @param PushItemEvent $pushItemEvent
     */
    protected function beforePushItem(PushItemEvent $pushItemEvent)
    {
        $this->dispatcher->dispatch(PushItemEvent::BEFORE_PUSH, $pushItemEvent);
    }

    /**
     * After push item.
     *
     * @param PushItemEvent $pushItemEvent
     */
    protected function afterPushItem(PushItemEvent $pushItemEvent)
    {
        $this->dispatcher->dispatch(PushItemEvent::AFTER_PUSH, $pushItemEvent);
    }

    /**
     * After push item.
     *
     * @param PushEvent $pushEvent
     */
    protected function afterPush(PushEvent $pushEvent)
    {
        // Clear asset request after first workflow
        $this->clearAssetRequest();

        $this->dispatcher->dispatch(PushEvent::AFTER_PUSH, $pushEvent);
    }

    /**
     * Replace type in expressioon
     *
     * @param string      $flux
     * @param string|null $type
     *
     * @return string
     */
    protected function getWorkflowFluxType($flux, $type)
    {
        return preg_replace('/{type}/', $type, $flux);
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Clear asset from request
     */
    protected function clearAssetRequest()
    {
        if ($this->workflowAsset) {
            $this->workflowAsset->clearAssetRequest();
        }
    }

    /**
     * @param bool $dryRun
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = $dryRun;
    }
}
