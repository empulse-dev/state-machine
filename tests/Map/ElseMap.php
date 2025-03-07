<?php

use Empulse\State\Machine\Validator\CompareValidator;
use Empulse\State\Map;
use Empulse\State\Machine\Validator\NotEmptyValidator;
use Empulse\State\Machine\Validator\FlagOnValidator;
use Empulse\State\Machine\Validator\FlagOffValidator;

$mapConfig = [
    Map::MAP_CODE => 'else-map',
    Map::MAP_STATES => [
        Map::STATUS_INITIAL => 'init',
        Map::STATUS_MIDDLE => [
            'rejected'
        ],
        Map::STATUS_FINAL => [
            'accepted',
        ]
    ],
    Map::MAP_TRANSITIONS => [
        'left' => [
            Map::MAP_FROM => [
                'init',
            ],
            Map::MAP_TO => 'accepted',
            Map::VALIDATORS => [
                [
                    Map::VALIDATOR => CompareValidator::class,
                    Map::VALIDATOR_CONFIG => [
                        'op1' => 'offer',
                        'type' => 'gte',
                        'op2' => 'limit'
                    ]
                ],
            ],
            Map::MAP_ELSE => [
                Map::MAP_TO => 'rejected'
            ]
        ],
    ]
];