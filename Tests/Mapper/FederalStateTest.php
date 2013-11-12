<?php
/***************************************************************
* Copyright notice
*
* (c) 2012 Oliver Klee <typo3-coding@oliverklee.de>
* All rights reserved
*
* This script is part of the TYPO3 project. The TYPO3 project is
* free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This script is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Testcase for the tx_oelib_Mapper_FederalState class.
 *
 * @package TYPO3
 * @subpackage tx_oelib
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class tx_oelib_Mapper_FederalStateTest extends tx_phpunit_testcase {
	/**
	 * @var tx_oelib_Mapper_FederalState
	 */
	private $fixture = NULL;

	public function setUp() {
		$this->fixture = new tx_oelib_Mapper_FederalState();
	}

	public function tearDown() {
		$this->fixture->__destruct();
		unset($this->fixture);
	}


	/*
	 * Tests concerning find
	 */

	/**
	 * @test
	 */
	public function findWithUidOfExistingRecordReturnsFederalStateInstance() {
		$this->assertInstanceOf(
			'tx_oelib_Model_FederalState',
			$this->fixture->find(88)
		);
	}

	/**
	 * @test
	 */
	public function findWithUidOfExistingRecordReturnsRecordAsModel() {
		$this->assertSame(
			'NW',
			$this->fixture->find(88)->getIsoAlpha2ZoneCode()
		);
	}


	/**
	 * Tests concerning findByIsoAlpha2Code
	 *

	/**
	 * @test
	 */
	public function findByIsoAlpha2CountryCodeAndIsoAlpha2ZoneCodeWithIsoAlpha2CodeOfExistingRecordReturnsFederalStateInstance() {
		$this->assertInstanceOf(
			'tx_oelib_Model_FederalState',
			$this->fixture->findByIsoAlpha2CountryCodeAndIsoAlpha2ZoneCode('DE', 'NW')
		);
	}

	/**
	 * @test
	 */
	public function findByIsoAlpha2CountryCodeAndIsoAlpha2ZoneCodeWithIsoAlpha2CodeOfExistingRecordReturnsRecordAsModel() {
		$this->assertSame(
			'NW',
			$this->fixture->findByIsoAlpha2CountryCodeAndIsoAlpha2ZoneCode('DE', 'NW')->getIsoAlpha2ZoneCode()
		);
	}
}
?>