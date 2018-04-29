<?php
defined('TYPO3_MODE') or die('Access denied.');

return [
    'ctrl' => [
        'hideTable' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
    ],
    'columns' => [
        'hidden' => [
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date',
            ],
        ],
        'endtime' => [
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date',
            ],
        ],
        'title' => [
            'config' => [
                'type' => 'input',
                'eval' => 'required',
            ],
        ],
    ],
];
