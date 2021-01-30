<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Configuration;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use OliverKlee\Oelib\Configuration\FlexformsConfiguration;
use OliverKlee\Oelib\DataStructures\AbstractObjectWithPublicAccessors;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Test case.
 *
 * @author Oliver Klee <typo3-coding@oliverklee.de>
 */
final class FlexformsConfigurationTest extends UnitTestCase
{
    private function buildContentObjectWithFlexformsData(string $key, string $value): ContentObjectRenderer
    {
        $flexformsXml = '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
                             <T3FlexForms>
                                 <data>
                                     <sheet index="sDEF">
                                         <language index="lDEF">
                                             <field index="' . $key . '">
                                                 <value index="vDEF">' . $value . '</value>
                                             </field>
                                         </language>
                                     </sheet>
                                 </data>
                             </T3FlexForms>';
        $data = ['pi_flexform' => $flexformsXml];

        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $data;

        return $contentObject;
    }

    /**
     * @test
     */
    public function isObjectWithPublicAccessors()
    {
        $subject = new FlexformsConfiguration(new ContentObjectRenderer());

        self::assertInstanceOf(AbstractObjectWithPublicAccessors::class, $subject);
    }

    /**
     * @test
     */
    public function setAsStringThrowsException()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This is a read-only configuration. You cannot set any values.');
        $this->expectExceptionCode(1612002594);

        $subject = new FlexformsConfiguration(new ContentObjectRenderer());

        $subject->setAsString('flavor', 'hello');
    }

    /**
     * @test
     */
    public function setAsIntegerThrowsException()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This is a read-only configuration. You cannot set any values.');
        $this->expectExceptionCode(1612002594);

        $subject = new FlexformsConfiguration(new ContentObjectRenderer());

        $subject->setAsInteger('flavor', 1);
    }

    /**
     * @test
     */
    public function setAsBooleanThrowsException()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('This is a read-only configuration. You cannot set any values.');
        $this->expectExceptionCode(1612002594);

        $subject = new FlexformsConfiguration(new ContentObjectRenderer());

        $subject->setAsBoolean('flavor', true);
    }

    /**
     * @return array<string, array<array<string, string|null>>>
     */
    public function noFlexformsDataDataProvider(): array
    {
        return [
            'no flexforms data field' => [[]],
            'null' => [['pi_flexform' => null]],
            'empty string' => [['pi_flexform' => '']],
            'non-XML string' => [['pi_flexform' => 'The cake is a lie.']],
            'non-flexforms XML string' => [
                ['pi_flexform' => '<?xml version="1.0" encoding="utf-8" standalone="yes" ?><html></html>'],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider noFlexformsDataDataProvider
     */
    public function getAsStringForNoFlexformsDataReturnsEmptyString(array $contentObjectData)
    {
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $contentObjectData;
        $subject = new FlexformsConfiguration($contentObject);

        self::assertSame('', $subject->getAsString('flavor'));
    }

    /**
     * @test
     * @dataProvider noFlexformsDataDataProvider
     */
    public function getAsIntegerForNoFlexformsDataReturnsZero(array $contentObjectData)
    {
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $contentObjectData;
        $subject = new FlexformsConfiguration($contentObject);

        self::assertSame(0, $subject->getAsInteger('flavor'));
    }

    /**
     * @test
     * @dataProvider noFlexformsDataDataProvider
     */
    public function getAsBooleanForNoFlexformsDataReturnsFalse(array $contentObjectData)
    {
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $contentObjectData;
        $subject = new FlexformsConfiguration($contentObject);

        self::assertFalse($subject->getAsBoolean('flavor'));
    }

    /**
     * @return array<string, array<array<string, string>>>
     */
    public function noFieldsDataDataProvider(): array
    {
        return [
            'flexforms without any fields' => [
                [
                    'pi_flexform' => '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
                                      <T3FlexForms>
                                          <data>
                                              <sheet index="sDEF">
                                                  <language index="lDEF"></language>
                                              </sheet>
                                          </data>
                                      </T3FlexForms>',
                ],
            ],
        ];
    }

    /**
     * @test
     *
     * @param array<string, string> $contentObjectData
     * @dataProvider noFieldsDataDataProvider
     */
    public function getAsStringForMissingFieldReturnsEmptyString(array $contentObjectData)
    {
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $contentObjectData;
        $subject = new FlexformsConfiguration($contentObject);

        self::assertSame('', $subject->getAsString('flavor'));
    }

    /**
     * @test
     *
     * @param array<string, string> $contentObjectData
     * @dataProvider noFieldsDataDataProvider
     */
    public function getAsIntegerForMissingFieldReturnsZero(array $contentObjectData)
    {
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $contentObjectData;
        $subject = new FlexformsConfiguration($contentObject);

        self::assertSame(0, $subject->getAsInteger('flavor'));
    }

    /**
     * @test
     *
     * @param array<string, string> $contentObjectData
     * @dataProvider noFieldsDataDataProvider
     */
    public function getAsBooleanForMissingFieldReturnsFalse(array $contentObjectData)
    {
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $contentObjectData;
        $subject = new FlexformsConfiguration($contentObject);

        self::assertFalse($subject->getAsBoolean('flavor'));
    }

    /**
     * @return array<string, array<array<string, string>>>
     */
    public function emptyFieldDataDataProvider(): array
    {
        return [
            'flexforms with with empty "flavor" field' => [
                [
                    'pi_flexform' => '<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
                                      <T3FlexForms>
                                          <data>
                                              <sheet index="sDEF">
                                                  <language index="lDEF">
                                                      <field index="flavor">
                                                          <value index="vDEF"></value>
                                                      </field>
                                                  </language>
                                              </sheet>
                                          </data>
                                      </T3FlexForms>',
                ],
            ],
        ];
    }

    /**
     * @test
     *
     * @param array<string, string> $contentObjectData
     * @dataProvider emptyFieldDataDataProvider
     */
    public function getAsStringForEmptyFieldReturnsEmptyString(array $contentObjectData)
    {
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $contentObjectData;
        $subject = new FlexformsConfiguration($contentObject);

        self::assertSame('', $subject->getAsString('flavor'));
    }

    /**
     * @test
     *
     * @param array<string, string> $contentObjectData
     * @dataProvider emptyFieldDataDataProvider
     */
    public function getAsIntegerForEmptyFieldReturnsZero(array $contentObjectData)
    {
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $contentObjectData;
        $subject = new FlexformsConfiguration($contentObject);

        self::assertSame(0, $subject->getAsInteger('flavor'));
    }

    /**
     * @test
     *
     * @param array<string, string> $contentObjectData
     * @dataProvider emptyFieldDataDataProvider
     */
    public function getAsBooleanForEmptyFieldReturnsFalse(array $contentObjectData)
    {
        $contentObject = new ContentObjectRenderer();
        $contentObject->data = $contentObjectData;
        $subject = new FlexformsConfiguration($contentObject);

        self::assertFalse($subject->getAsBoolean('flavor'));
    }

    /**
     * @test
     */
    public function getAsStringForExistingNonEmptyFieldReturnsValueFromField()
    {
        $key = 'flavor';
        $value = 'hazelnut';
        $subject = new FlexformsConfiguration($this->buildContentObjectWithFlexformsData($key, $value));

        self::assertSame($value, $subject->getAsString($key));
    }

    /**
     * @test
     */
    public function getAsIntegerForExistingNonEmptyFieldReturnsValueFromField()
    {
        $key = 'size';
        $value = 4;
        $subject = new FlexformsConfiguration($this->buildContentObjectWithFlexformsData($key, (string)$value));

        self::assertSame($value, $subject->getAsInteger($key));
    }

    /**
     * @test
     */
    public function getAsBooleanForExistingNonEmptyFieldReturnsValueFromField()
    {
        $key = 'hasCats';
        $subject = new FlexformsConfiguration($this->buildContentObjectWithFlexformsData($key, '1'));

        self::asserttrue($subject->getAsBoolean($key));
    }
}
