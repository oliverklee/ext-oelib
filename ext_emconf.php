<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'One is Enough Library',
    'description' => 'Useful stuff for TYPO3 extension development: helper functions for unit testing, templating and automatic configuration checks.',
    'version' => '6.0.3',
    'category' => 'services',
    'constraints' => [
        'depends' => [
            'php' => '7.4.0-8.3.99',
            'typo3' => '11.5.40-12.4.99',
            'extbase' => '11.5.40-12.4.99',
            'fluid' => '11.5.40-12.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
            'static_info_tables' => '6.9.0-12.99.99',
        ],
    ],
    'state' => 'stable',
    'author' => 'Oliver Klee',
    'author_email' => 'typo3-coding@oliverklee.de',
    'author_company' => 'oliverklee.de',
    'autoload' => [
        'psr-4' => [
            'OliverKlee\\Oelib\\' => 'Classes/',
        ],
    ],
    'autoload-dev' => [
        'psr-4' => [
            'OliverKlee\\Oelib\\Tests\\' => 'Tests/',
        ],
    ],
];
