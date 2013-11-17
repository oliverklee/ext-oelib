<?php
/***************************************************************
* Copyright notice
*
* (c) 2012 Oliver Klee (typo3-coding@oliverklee.de)
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
 * Testcase for the Tx_Oelib_ViewHelpers_UppercaseViewHelper class.
 *
 * @package TYPO3
 * @subpackage tx_oelib
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
class Tx_Oelib_ViewHelpers_UppercaseViewHelperTest extends Tx_Extbase_Tests_Unit_BaseTestCase {
	/**
	 * @test
	 */
	public function renderConvertsToUppercase() {
		$subject = $this->getMock('Tx_Oelib_ViewHelpers_UppercaseViewHelper', array('renderChildren'));
		$subject->expects($this->once())->method('renderChildren')->will($this->returnValue('foo bar'));

		$this->assertSame(
			'FOO BAR',
			$subject->render()
		);
	}

	/**
	 * @test
	 */
	public function renderCanConvertUmlautsToUppercase() {
		$subject = $this->getMock('Tx_Oelib_ViewHelpers_UppercaseViewHelper', array('renderChildren'));
		$subject->expects($this->once())->method('renderChildren')->will($this->returnValue('äöü'));

		$this->assertSame(
			'ÄÖÜ',
			$subject->render()
		);
	}

	/**
	 * @test
	 */
	public function renderCanConvertAccentedCharactersToUppercase() {
		$subject = $this->getMock('Tx_Oelib_ViewHelpers_UppercaseViewHelper', array('renderChildren'));
		$subject->expects($this->once())->method('renderChildren')->will($this->returnValue('áàéè'));

		$this->assertSame(
			'ÁÀÉÈ',
			$subject->render()
		);
	}
}
?>