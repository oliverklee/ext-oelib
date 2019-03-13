<?php
defined('TYPO3_MODE') or die('Access denied.');

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'One is Enough Library');

$GLOBALS['TCA']['tx_oelib_domain_model_germanzipcode'] = array(
    'ctrl' => array(
        'default_sortby' => 'zip_code',
        'delete' => 'deleted',
        'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/ZipCode.gif',
        'is_static' => true,
        'label' => 'zip_code',
        'label_alt' => 'city_name',
        'label_alt_force' => true,
        'readOnly' => true,
        'rootLevel' => 1,
        'searchFields' => 'zip_code, city_name',
        'title' => 'LLL:EXT:oelib/Resources/Private/Language/locallang_db.xml:tx_oelib_domain_model_germanzipcode',
        'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/GermanZipCode.php',
    ),
);

$GLOBALS['TCA']['tx_oelib_test'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:oelib/Resources/Private/Language/locallang_db.xml:tx_oelib_test',
		'readOnly' => 1,
		'adminOnly' => 1,
		'rootLevel' => 1,
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => FALSE,
		'default_sortby' => 'ORDER BY uid',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/Test.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Test.gif',
	),
);

$GLOBALS['TCA']['tx_oelib_testchild'] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:oelib/Resources/Private/Language/locallang_db.xml:tx_oelib_test',
		'readOnly' => 1,
		'adminOnly' => 1,
		'rootLevel' => 1,
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => FALSE,
		'default_sortby' => 'ORDER BY uid',
		'delete' => 'deleted',
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'Configuration/TCA/TestChild.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'Resources/Public/Icons/Test.gif',
	),
);

$addToFeInterface = (t3lib_utility_VersionNumber::convertVersionNumberToInteger(TYPO3_version) < 6002000);
t3lib_extMgm::addTCAcolumns(
	'fe_users',
	array(
		'tx_oelib_is_dummy_record' => array(),
	),
	$addToFeInterface
);
