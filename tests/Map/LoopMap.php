<?php

use Empulse\State\Map;
use Empulse\State\Machine\Validator\ManualMoveValidator;

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
            ],
            Map::VALIDATORS => [
                [
                    Map::VALIDATOR => ManualMoveValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        true
                    ]
                ],
                Map::VALID_FIRST => [
                    [
                        Map::VALIDATOR => ManualMoveValidator::class,
                        Map::VALIDATOR_CONFIG => [
                            false
                        ]
                    ],
                    [
                        Map::VALIDATOR => ManualMoveValidator::class,
                        Map::VALIDATOR_CONFIG => [
                            true
                        ]
                    ]
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
                [
                    Map::VALIDATOR => ManualMoveValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        true
                    ]
                ]
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
                [
                    Map::VALIDATOR => ManualMoveValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        true
                    ]
                ]
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
                [
                    Map::VALIDATOR => ManualMoveValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        true
                    ]
                ]
            ],
        ]
    ]
];