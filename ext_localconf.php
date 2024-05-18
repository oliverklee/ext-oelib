<?php

use OliverKlee\Oelib\Testing\TestingFrameworkCleanup;

defined('TYPO3') or die('Access denied.');

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['oelib']['testingFrameworkCleanUp'][] = TestingFrameworkCleanup::class;
