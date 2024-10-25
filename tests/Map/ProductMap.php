<?php

use Empulse\State\Map;
use Empulse\State\Machine\Validator\AlwaysMoveValidator;
use Empulse\State\Machine\Validator\StopValidator;
use Empulse\State\Machine\Validator\NotEmptyValidator;
use Empulse\State\Machine\Validator\FlagOnValidator;
use Empulse\State\Machine\Validator\FlagOffValidator;

$mapConfig = [
    Map::MAP_CODE => 'product-map',
    Map::MAP_STATES => [
        Map::STATUS_INITIAL => 'created',
        Map::STATUS_MIDDLE => [
            'enrich',
            'publish',
            'disable'
        ],
        Map::STATUS_FINAL => [
            'disable'
        ]
    ],
    Map::MAP_TRANSITIONS => [
        'created' => [
            Map::MAP_FROM => [
                'created',
            ],
            Map::MAP_TO => 'enrich',
            Map::VALIDATORS => [
                [
                    Map::VALIDATOR => FlagOnValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'active'
                    ]
                ],
                /*[
                    Map::VALIDATOR => NotEmptyValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'name',
                        'price' 
                    ]
                ]*/
            ],
        ],
        'enrich' => [
            Map::MAP_FROM => [
                'enrich',
            ],
            Map::MAP_TO => 'publish',
            Map::VALIDATORS => [
                [
                    Map::VALIDATOR => FlagOnValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'active'
                    ]
                ],
                [
                    Map::VALIDATOR => NotEmptyValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'image' 
                    ]
                ]
            ],
        ],
        'disable' => [
            Map::MAP_FROM => [
                'publish',
            ],
            Map::MAP_TO => 'disable',
            Map::VALIDATORS => [
                [
                    Map::VALIDATOR => FlagOffValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'active'
                    ]
                ]
            ],
        ]
    ]
];