<?php
defined('TYPO3_MODE') or die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals']['tx_oelib_Double3Validator'] = 'EXT:oelib/Classes/Double3Validator.php';

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['oelib']['testingFrameworkCleanUp'][]
    = 'EXT:oelib/Classes/TestingFrameworkCleanup.php:' . \Tx_Oelib_TestingFrameworkCleanup::class;
