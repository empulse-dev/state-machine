<?php

use Empulse\State\Machine\Validator\CompareValidator;
use Empulse\State\Map;
use Empulse\State\Machine\Validator\NotEmptyValidator;
use Empulse\State\Machine\Validator\FlagOnValidator;
use Empulse\State\Machine\Validator\FlagOffValidator;

$mapConfig = [
    Map::MAP_CODE => 'comparison-map',
    Map::MAP_STATES => [
        Map::STATUS_INITIAL => 'init',
        Map::STATUS_FINAL => [
            'done'
        ]
    ],
    Map::MAP_TRANSITIONS => [
        'to_eq' => [
            Map::MAP_FROM => [
                'init',
            ],
            Map::MAP_TO => 'done',
            Map::VALIDATORS => [
                [
                    Map::VALIDATOR => CompareValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'op1' => 'dos',
                        'type' => 'eq',
                        'op2' => 'dos'
                    ]
                ],
                [
                    Map::VALIDATOR => CompareValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'op1' => 'tres',
                        'type' => 'neq',
                        'op2' => 'dos'
                    ]
                ],
                [
                    Map::VALIDATOR => CompareValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'op1' => 'tres',
                        'type' => 'gt',
                        'op2' => 'dos'
                        
                    ]
                ],
                [
                    Map::VALIDATOR => CompareValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'op1' => 'dos',
                        'type' => 'gte',
                        'op2' => 'dos'
                    ],
                ],
                [
                    Map::VALIDATOR => CompareValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'op1' => 'tres',
                        'type' => 'gte',
                        'op2' => 'dos'
                    ],
                ],
                [
                    Map::VALIDATOR => CompareValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'op1' => 'uno',
                        'type' => 'lt',
                        'op2' => 'tres',
                        
                    ]
                ],
                [
                    Map::VALIDATOR => CompareValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'op1' => 'uno',
                        'type' => 'lte',
                        'op2' => 'uno',
                        
                    ]
                ],
                [
                    Map::VALIDATOR => CompareValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'op1' => 'uno',
                        'type' => 'lte',
                        'op2' => 'dos',
                        
                    ]
                ],
            ],
        ],
        
    ]
];