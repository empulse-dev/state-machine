<?php

use Empulse\State\Map;
use Empulse\Tests\Validator\AlwaysMoveValidator;
use Empulse\Tests\Validator\StopValidator;

$mapConfig = [
    Map::MAP_CODE => 'sale-map',
    Map::MAP_STATES => [
        Map::STATUS_INITIAL => 'pending',
        Map::STATUS_MIDDLE => [
            'processing',
            'partial-transit',
            'in-transit',
            'partial-delivered'
        ],
        Map::STATUS_FINAL => [
            'delivered',
            'canceled',
            'returned',
            'refunded'
        ]
    ],
    Map::MAP_TRANSITIONS => [
        'pay' => [
            Map::MAP_FROM => [
                'pending',
            ],
            Map::MAP_TO => 'processing',
            Map::PROPERTIES => [
                'group' => null,
                'sortable_by_group' => null,
                Map::STOP_AFTER_APPLY
            ],
            Map::VALIDATORS => [
                AlwaysMoveValidator::class,
                Map::VALID_FIRST => [
                    StopValidator::class,
                    AlwaysMoveValidator::class
                ]
            ],
        ],
        'sending' => [
            Map::MAP_FROM => [
                'processing', 'returned'
            ],
            Map::MAP_TO => 'in-transit',
            Map::PROPERTIES => [
                'group' => null,
                'sortable_by_group' => null,
            ],
            Map::VALIDATORS => [
                AlwaysMoveValidator::class,
            ],
        ],
        'delivery' => [
            Map::MAP_FROM => [
                'in-transit',
            ],
            Map::MAP_TO => 'delivered',
            Map::PROPERTIES => [
                'group' => null,
                'sortable_by_group' => null,
            ],
            Map::VALIDATORS => [
                AlwaysMoveValidator::class,
            ],
        ],
        'return' => [
            Map::MAP_FROM => [
                'delivered',
            ],
            Map::MAP_TO => 'returned',
            Map::PROPERTIES => [
                'group' => null,
                'sortable_by_group' => null,
            ],
            Map::VALIDATORS => [
                AlwaysMoveValidator::class,
            ],
        ]

    ]
];