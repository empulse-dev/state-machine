<?php

use Empulse\State\Machine\Validator\RegexValidator;
use Empulse\State\Map;
use Empulse\State\Machine\Validator\NotEmptyValidator;
use Empulse\State\Machine\Validator\FlagOnValidator;
use Empulse\State\Machine\Validator\FlagOffValidator;

$mapConfig = [
    Map::MAP_CODE => 'review-map',
    Map::MAP_STATES => [
        Map::STATUS_INITIAL => 'init',
        Map::STATUS_MIDDLE => [
            'validation',
            'approbed',
            'show',
            'hide' 
        ],
        // Map::STATUS_FINAL => [
        //     'deleted'
        // ]
    ],
    Map::MAP_TRANSITIONS => [
        'validation' => [
            Map::MAP_FROM => [
                'init',
            ],
            Map::MAP_TO => 'validation',
            Map::VALIDATORS => [
                [
                    Map::VALIDATOR => NotEmptyValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'sku',
                        'stars',
                        'comments' 
                    ]
                ]
            ],
        ],
        'approbed' => [
            Map::MAP_FROM => [
                'validation',
            ],
            Map::MAP_TO => 'approbed',
            Map::VALIDATORS => [
                [
                    Map::VALIDATOR => NotEmptyValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'approbed_by'
                    ]
                ]
            ],
            Map::MAP_FLAGS => [
                Map::MAP_FLAGS_ACTIVE => [
                    'active'
                ]
            ]
        ],
        'show' => [
            Map::MAP_FROM => [
                'approbed',
            ],
            Map::MAP_TO => 'show',
            Map::VALIDATORS => [
                [
                    Map::VALIDATOR => RegexValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'stars' => '/^[4-5]$/'
                    ]
                ]
            ],
        ],
        'hide' => [
            Map::MAP_FROM => [
                'approbed',
            ],
            Map::MAP_TO => 'hide',
            Map::VALIDATORS => [
                [
                    Map::VALIDATOR => RegexValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'stars' => '/^[1-3]$/'
                    ]
                ]
            ],
        ]
    ]
];