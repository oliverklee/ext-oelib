<?php
defined('TYPO3_MODE') or die('Access denied.');

$GLOBALS['TCA']['tx_oelib_domain_model_germanzipcode'] = array(
    'ctrl' => $GLOBALS['TCA']['tx_oelib_domain_model_germanzipcode']['ctrl'],
    'interface' => array(
        'showRecordFieldList' => 'zip_code, city_name, longitude, latitude',
    ),
    'columns' => array(
        'zip_code' => array(
            'label' => 'LLL:EXT:oelib/Resources/Private/Language/locallang_db.xml:tx_oelib_domain_model_germanzipcode.zip_code',
            'config' => array(
                'type' => 'input',
                'readOnly' => true,
                'size' => 10,
            ),
        ),
        'city_name' => array(
            'label' => 'LLL:EXT:oelib/Resources/Private/Language/locallang_db.xml:tx_oelib_domain_model_germanzipcode.city_name',
            'config' => array(
                'type' => 'input',
                'readOnly' => true,
                'size' => 50,
            ),
        ),
        'longitude' => array(
            'label' => 'LLL:EXT:oelib/Resources/Private/Language/locallang_db.xml:tx_oelib_domain_model_germanzipcode.longitude',
            'config' => array(
                'type' => 'input',
                'readOnly' => true,
                'size' => 10,
            ),
        ),
        'latitude' => array(
            'label' => 'LLL:EXT:oelib/Resources/Private/Language/locallang_db.xml:tx_oelib_domain_model_germanzipcode.latitude',
            'config' => array(
                'type' => 'input',
                'readOnly' => true,
                'size' => 10,
            ),
        ),
    ),
    'types' => array(
        '0' => array('showitem' => 'zip_code, city_name, longitude, latitude'),
    ),
    'palettes' => array(
        '1' => array(),
    ),
);
