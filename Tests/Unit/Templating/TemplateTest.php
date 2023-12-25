<?php

declare(strict_types=1);

namespace OliverKlee\Oelib\Tests\Unit\Templating;

use OliverKlee\Oelib\Exception\NotFoundException;
use OliverKlee\Oelib\Templating\Template;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * @covers \OliverKlee\Oelib\Templating\Template
 */
final class TemplateTest extends UnitTestCase
{
    private Template $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new Template();
    }

    // Tests for getting subparts.

    /**
     * @test
     */
    public function getSubpartWithNoSubpartNameInitiallyReturnsAnEmptyString(): void
    {
        self::assertSame(
            '',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function getSubpartWithEmptySubpartNameInitiallyReturnsAnEmptyString(): void
    {
        self::assertSame(
            '',
            $this->subject->getSubpart('')
        );
    }

    /**
     * @test
     */
    public function getSubpartWithNotExistingSubpartNameThrowsException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            '$key contained the subpart name "FOOBAR", but only the following subparts are available: ()'
        );
        $this->expectExceptionCode(1_632_760_625);

        self::assertSame(
            '',
            $this->subject->getSubpart('FOOBAR')
        );
    }

    /**
     * @test
     */
    public function getSubpartWithNotExistingSubpartNameThrowsExceptionWithSubpartNames(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            '$key contained the subpart name "COFFEE", but only the following subparts are available: (FOO, BAR)'
        );
        $this->expectExceptionCode(1_632_760_625);

        $this->subject->processTemplate(
            '<!-- ###FOO### -->' .
            '<!-- ###FOO### -->' .
            '<!-- ###BAR### -->' .
            '<!-- ###BAR### -->'
        );

        self::assertSame(
            '',
            $this->subject->getSubpart('COFFEE')
        );
    }

    /**
     * @test
     */
    public function getSubpartWithoutParametersReturnsCompleteTemplateContent(): void
    {
        $templateCode = "This is a test including\na linefeed.\n";
        $this->subject->processTemplate($templateCode);
        self::assertSame(
            $templateCode,
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function getSubpartWithEmptySubpartNameReturnsCompleteTemplateContent(): void
    {
        $templateCode = "This is a test including\na linefeed.\n";
        $this->subject->processTemplate($templateCode);
        self::assertSame(
            $templateCode,
            $this->subject->getSubpart('')
        );
    }

    /**
     * @test
     */
    public function getSimpleSubpart(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = 'Text before the subpart'
            . '<!-- ###MY_SUBPART### -->'
            . $subpartContent
            . '<!-- ###MY_SUBPART### -->'
            . 'Text after the subpart.';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartFindsSubpartWithTextBeforeClosingSubpartStartComment(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = 'Text before the subpart' .
            '<!-- ###MY_SUBPART### start -->' .
            $subpartContent .
            '<!-- ###MY_SUBPART### -->';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartFindsSubpartWithTextBeforeClosingSubpartEndComment(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = 'Text before the subpart' .
            '<!-- ###MY_SUBPART### -->' .
            $subpartContent .
            '<!-- ###MY_SUBPART### end -->';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartFindsSubpartWithTextBeforeOpeningAndClosingSubpartEndComment(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = 'Text before the subpart' .
            '<!-- ###MY_SUBPART### start -->' .
            $subpartContent .
            '<!-- ###MY_SUBPART### end -->';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartFindsSubpartWithTextWithLinefeedsBeforeOpeningAndClosingSubpartEndComment(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = "<!-- ###MY_SUBPART### start \n start -->" .
            $subpartContent .
            "<!-- ###MY_SUBPART### end \n end -->";
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartFindsSubpartWithTextWithDashesBeforeOpeningAndClosingSubpartEndComment(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = '<!-- ###MY_SUBPART### start - hey hey -->' .
            $subpartContent .
            '<!-- ###MY_SUBPART### end - really the end -->';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartFindsSubpartWithTextWithHtmlBeforeOpeningAndClosingSubpartEndComment(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = '<!-- ###MY_SUBPART### <em>start</em> -->' .
            $subpartContent .
            '<!-- ###MY_SUBPART### <em>end</em> -->';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartFindsSubpartWithHtmlCommentInIt(): void
    {
        $subpartContent = 'Subpart <!-- this is hidden --> content';
        $templateCode = '<!-- ###MY_SUBPART### -->' .
            $subpartContent .
            '<!-- ###MY_SUBPART### -->';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartWithTextBeforeClosingSubpartStartCommentReplacesNestedSubpart(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = 'Text before the subpart' .
            '<!-- ###MY_SUBPART### start -->' .
            '<!-- ###MY_INNER_SUBPART### start -->' .
            $subpartContent .
            '<!-- ###MY_INNER_SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartWithTextBeforeClosingSubpartEndCommentReplacesNestedSubpart(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = 'Text before the subpart' .
            '<!-- ###MY_SUBPART### -->' .
            '<!-- ###MY_INNER_SUBPART### -->' .
            $subpartContent .
            '<!-- ###MY_INNER_SUBPART### end -->' .
            '<!-- ###MY_SUBPART### end -->';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartWithTextBeforeOpeningAndClosingSubpartEndCommentReplacesNestedSubpart(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = 'Text before the subpart' .
            '<!-- ###MY_SUBPART### -->' .
            '<!-- ###MY_INNER_SUBPART### start -->' .
            $subpartContent .
            '<!-- ###MY_INNER_SUBPART### end -->' .
            '<!-- ###MY_SUBPART### end -->';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartFromTemplateCanContainUtf8Umlauts(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->' .
            'äöüßÄÖÜßéèáàóò' .
            '<!-- ###MY_SUBPART### -->'
        );

        self::assertSame(
            'äöüßÄÖÜßéèáàóò',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartFromTemplateCanContainIso88591Umlauts(): void
    {
        // 228 = ä, 223 = ß (in ISO8859-1)
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->' .
            \chr(228) . \chr(223) .
            '<!-- ###MY_SUBPART### -->'
        );

        self::assertSame(
            \chr(228) . \chr(223),
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getOneOfTwoSimpleSubparts(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = 'Text before the subpart'
            . '<!-- ###MY_SUBPART### -->'
            . $subpartContent
            . '<!-- ###MY_SUBPART### -->'
            . 'Text inbetween.'
            . '<!-- ###ANOTHER_SUBPART### -->'
            . 'More text.'
            . '<!-- ###ANOTHER_SUBPART### -->'
            . 'Text after the subpart.';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSimpleSubpartWithLinefeed(): void
    {
        $subpartContent = "\nSubpart content\n";
        $templateCode = "Text before the subpart\n"
            . '<!-- ###MY_SUBPART### -->'
            . $subpartContent
            . "<!-- ###MY_SUBPART### -->\n"
            . "Text after the subpart.\n";
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getDoubleOccurringSubpart(): void
    {
        $subpartContent = 'Subpart content';
        $templateCode = 'Text before the subpart'
            . '<!-- ###MY_SUBPART### -->'
            . $subpartContent
            . '<!-- ###MY_SUBPART### -->'
            . 'Text inbetween.'
            . '<!-- ###MY_SUBPART### -->'
            . 'More text.'
            . '<!-- ###MY_SUBPART### -->'
            . 'Text after the subpart.';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $subpartContent,
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartWithNestedInnerSubparts(): void
    {
        $subpartContent = 'Subpart content ';
        $templateCode = 'Text before the subpart'
            . '<!-- ###MY_SUBPART### -->'
            . 'outer start, '
            . '<!-- ###OUTER_SUBPART### -->'
            . 'inner start, '
            . '<!-- ###INNER_SUBPART### -->'
            . $subpartContent
            . '<!-- ###INNER_SUBPART### -->'
            . 'inner end, '
            . '<!-- ###OUTER_SUBPART### -->'
            . 'outer end '
            . '<!-- ###MY_SUBPART### -->'
            . 'Text after the subpart.';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            'outer start, inner start, ' . $subpartContent . 'inner end, outer end ',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getEmptyExistingSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
        );
        self::assertSame(
            '',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getHiddenSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'Some text. '
            . '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->hideSubparts('MY_SUBPART');

        self::assertSame(
            '',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayAndGetHiddenSubpartReturnsEmptySubpartContent(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->' .
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);

        self::assertSame(
            '',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    // Tests concerning render

    /**
     * @test
     */
    public function renderReturnsCompleteTemplateContent(): void
    {
        $templateCode = "This is a test including\na linefeed.\n";
        $this->subject->processTemplate($templateCode);
        self::assertSame(
            $templateCode,
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function renderCanContainUtf8Umlauts(): void
    {
        $this->subject->processTemplate('äöüßÄÖÜßéèáàóò');

        self::assertSame(
            'äöüßÄÖÜßéèáàóò',
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function renderCanContainIso88591Umlauts(): void
    {
        // 228 = ä, 223 = ß (in ISO8859-1)
        $this->subject->processTemplate(\chr(228) . \chr(223));

        self::assertSame(
            \chr(228) . \chr(223),
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function renderTemplateWithComment(): void
    {
        $templateCode = 'This is a test including a comment. '
            . '<!-- This is a comment. -->'
            . 'And some more text.';
        $this->subject->processTemplate(
            $templateCode
        );
        self::assertSame(
            $templateCode,
            $this->subject->render()
        );
    }

    //////////////////////////////////
    // Tests for filling in markers.
    //////////////////////////////////

    /**
     * @test
     */
    public function getInexistentMarkerWillReturnAnEmptyString(): void
    {
        $this->subject->processTemplate(
            'foo'
        );
        self::assertSame(
            '',
            $this->subject->getMarker('bar')
        );
    }

    /**
     * @test
     */
    public function setAndGetInexistentMarkerSucceeds(): void
    {
        $this->subject->processTemplate(
            'foo'
        );

        $this->subject->setMarker('bar', 'test');
        self::assertSame(
            'test',
            $this->subject->getMarker('bar')
        );
    }

    /**
     * @test
     */
    public function setAndGetExistingMarkerSucceeds(): void
    {
        $this->subject->processTemplate(
            '###BAR###'
        );

        $this->subject->setMarker('bar', 'test');
        self::assertSame(
            'test',
            $this->subject->getMarker('bar')
        );
    }

    /**
     * @test
     */
    public function setMarkerAndGetMarkerCanHaveUtf8UmlautsInMarkerContent(): void
    {
        $this->subject->processTemplate(
            '###BAR###'
        );
        $this->subject->setMarker('bar', 'äöüßÄÖÜßéèáàóò');

        self::assertSame(
            'äöüßÄÖÜßéèáàóò',
            $this->subject->getMarker('bar')
        );
    }

    /**
     * @test
     */
    public function setMarkerAndGetMarkerCanHaveIso88591UmlautsInMarkerContent(): void
    {
        $this->subject->processTemplate(
            '###BAR###'
        );
        // 228 = ä, 223 = ß (in ISO8859-1)
        $this->subject->setMarker('bar', \chr(228) . \chr(223));

        self::assertSame(
            \chr(228) . \chr(223),
            $this->subject->getMarker('bar')
        );
    }

    /**
     * @test
     */
    public function getMarkerReturnsIntegerContentAsString(): void
    {
        $value = 42;
        $this->subject->processTemplate('###BAR###');
        $this->subject->setMarker('bar', $value);

        $result = $this->subject->getMarker('bar');

        self::assertSame((string)$value, $result);
    }

    /**
     * @test
     */
    public function setLowercaseMarkerInCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'This is some template code. ###MARKER### More text.'
        );
        $this->subject->setMarker('marker', 'foo');
        self::assertSame(
            'This is some template code. foo More text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setUppercaseMarkerInCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'This is some template code. ###MARKER### More text.'
        );
        $this->subject->setMarker('MARKER', 'foo');
        self::assertSame(
            'This is some template code. foo More text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setLowercaseMarkerInSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'This is some template code. ###MARKER### More text.'
            . '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->setMarker('marker', 'foo');
        self::assertSame(
            'This is some template code. foo More text.',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setUppercaseMarkerInSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'This is some template code. ###MARKER### More text.'
            . '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->setMarker('MARKER', 'foo');
        self::assertSame(
            'This is some template code. foo More text.',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setDoubleMarkerInSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '###MARKER### This is some template code. ###MARKER### More text.'
            . '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->setMarker('marker', 'foo');
        self::assertSame(
            'foo This is some template code. foo More text.',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setMarkerInCompleteTemplateTwoTimes(): void
    {
        $this->subject->processTemplate(
            'This is some template code. ###MARKER### More text.'
        );

        $this->subject->setMarker('marker', 'foo');
        self::assertSame(
            'This is some template code. foo More text.',
            $this->subject->getSubpart()
        );

        $this->subject->setMarker('marker', 'bar');
        self::assertSame(
            'This is some template code. bar More text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setMarkerInSubpartTwoTimes(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'This is some template code. ###MARKER### More text.'
            . '<!-- ###MY_SUBPART### -->'
        );

        $this->subject->setMarker('marker', 'foo');
        self::assertSame(
            'This is some template code. foo More text.',
            $this->subject->getSubpart('MY_SUBPART')
        );

        $this->subject->setMarker('marker', 'bar');
        self::assertSame(
            'This is some template code. bar More text.',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function markerNamesArePrefixesBothUsed(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER### ###MY_MARKER_TOO###'
        );

        $this->subject->setMarker('my_marker', 'foo');
        $this->subject->setMarker('my_marker_too', 'bar');
        self::assertSame(
            'foo bar',
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function markerNamesAreSuffixesBothUsed(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER### ###ALSO_MY_MARKER###'
        );

        $this->subject->setMarker('my_marker', 'foo');
        $this->subject->setMarker('also_my_marker', 'bar');
        self::assertSame(
            'foo bar',
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function markerNamesArePrefixesFirstUsed(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER### ###MY_MARKER_TOO###'
        );

        $this->subject->setMarker('my_marker', 'foo');
        self::assertSame(
            'foo ###MY_MARKER_TOO###',
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function markerNamesAreSuffixesFirstUsed(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER### ###ALSO_MY_MARKER###'
        );

        $this->subject->setMarker('my_marker', 'foo');
        self::assertSame(
            'foo ###ALSO_MY_MARKER###',
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function markerNamesArePrefixesSecondUsed(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER### ###MY_MARKER_TOO###'
        );

        $this->subject->setMarker('my_marker_too', 'bar');
        self::assertSame(
            '###MY_MARKER### bar',
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function markerNamesAreSuffixesSecondUsed(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER### ###ALSO_MY_MARKER###'
        );

        $this->subject->setMarker('also_my_marker', 'bar');
        self::assertSame(
            '###MY_MARKER### bar',
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function markerNamesArePrefixesBothUsedWithSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '###MY_MARKER### ###MY_MARKER_TOO###'
            . '<!-- ###MY_SUBPART### -->'
        );

        $this->subject->setMarker('my_marker', 'foo');
        $this->subject->setMarker('my_marker_too', 'bar');
        self::assertSame(
            'foo bar',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function markerNamesAreSuffixesBothUsedWithSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '###MY_MARKER### ###ALSO_MY_MARKER###'
            . '<!-- ###MY_SUBPART### -->'
        );

        $this->subject->setMarker('my_marker', 'foo');
        $this->subject->setMarker('also_my_marker', 'bar');
        self::assertSame(
            'foo bar',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    ///////////////////////////////////////////////////////////////
    // Tests for replacing subparts with their content on output.
    ///////////////////////////////////////////////////////////////

    /**
     * @test
     */
    public function getUnchangedSubpartInCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'This is some template code.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'This is some subpart code.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'More text.'
        );
        self::assertSame(
            'This is some template code.'
            . 'This is some subpart code.'
            . 'More text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function getUnchangedDoubleSubpartInCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'This is some template code.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'This is some subpart code.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'More text.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'This is other subpart code.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'Even more text.'
        );
        self::assertSame(
            'This is some template code.'
            . 'This is some subpart code.'
            . 'More text.'
            . 'This is some subpart code.'
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function getUnchangedSubpartInRequestedSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'This is some template code.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'This is some subpart code.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'More text.'
            . '<!-- ###MY_SUBPART### -->'
        );
        self::assertSame(
            'This is some template code.'
            . 'This is some subpart code.'
            . 'More text.',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getUnchangedDoubleSubpartInRequestedSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'This is some template code.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'This is some subpart code.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'More text.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'This is other subpart code.'
            . '<!-- ###INNER_SUBPART### -->'
            . 'Even more text.'
            . '<!-- ###MY_SUBPART### -->'
        );
        self::assertSame(
            'This is some template code.'
            . 'This is some subpart code.'
            . 'More text.'
            . 'This is some subpart code.'
            . 'Even more text.',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    ///////////////////////////////////////////////////////////////
    // Tests for retrieving subparts with names that are prefixes
    // or suffixes of other subpart names.
    ///////////////////////////////////////////////////////////////

    /**
     * @test
     */
    public function subpartNamesArePrefixesGetCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'foo'
            . '<!-- ###MY_SUBPART### -->'
            . ' Some more text. '
            . '<!-- ###MY_SUBPART_TOO### -->'
            . 'bar'
            . '<!-- ###MY_SUBPART_TOO### -->'
        );
        self::assertSame(
            'foo Some more text. bar',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function subpartNamesAreSuffixesGetCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'foo'
            . '<!-- ###MY_SUBPART### -->'
            . ' Some more text. '
            . '<!-- ###ALSO_MY_SUBPART### -->'
            . 'bar'
            . '<!-- ###ALSO_MY_SUBPART### -->'
        );
        self::assertSame(
            'foo Some more text. bar',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function subpartNamesArePrefixesGetFirstSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'foo'
            . '<!-- ###MY_SUBPART### -->'
            . ' Some more text. '
            . '<!-- ###MY_SUBPART_TOO### -->'
            . 'bar'
            . '<!-- ###MY_SUBPART_TOO### -->'
        );
        self::assertSame(
            'foo',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function subpartNamesAreSuffixesGetFirstSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'foo'
            . '<!-- ###MY_SUBPART### -->'
            . ' Some more text. '
            . '<!-- ###ALSO_MY_SUBPART### -->'
            . 'bar'
            . '<!-- ###ALSO_MY_SUBPART### -->'
        );
        self::assertSame(
            'foo',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function subpartNamesArePrefixesGetSecondSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'foo'
            . '<!-- ###MY_SUBPART### -->'
            . ' Some more text. '
            . '<!-- ###MY_SUBPART_TOO### -->'
            . 'bar'
            . '<!-- ###MY_SUBPART_TOO### -->'
        );
        self::assertSame(
            'bar',
            $this->subject->getSubpart('MY_SUBPART_TOO')
        );
    }

    /**
     * @test
     */
    public function subpartNamesAreSuffixesGetSecondSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . 'foo'
            . '<!-- ###MY_SUBPART### -->'
            . ' Some more text. '
            . '<!-- ###ALSO_MY_SUBPART### -->'
            . 'bar'
            . '<!-- ###ALSO_MY_SUBPART### -->'
        );
        self::assertSame(
            'bar',
            $this->subject->getSubpart('ALSO_MY_SUBPART')
        );
    }

    ////////////////////////////////////////////
    // Tests for hiding and unhiding subparts.
    ////////////////////////////////////////////

    /**
     * @test
     */
    public function hideSubpartInCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideOverwrittenSubpartInCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->setSubpart('MY_SUBPART', 'More text. ');
        $this->subject->hideSubparts('MY_SUBPART');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function unhideSubpartInCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->unhideSubparts('MY_SUBPART');
        self::assertSame(
            'Some text. '
            . 'More text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartInCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        $this->subject->unhideSubparts('MY_SUBPART');
        self::assertSame(
            'Some text. '
            . 'More text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideSubpartInSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###OUTER_SUBPART### -->'
            . 'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
            . '<!-- ###OUTER_SUBPART### -->'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function twoSubpartInNestedSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###SINGLE_VIEW###  -->'
            . '<!-- ###FIELD_WRAPPER_TITLE### -->'
            . '<h3 class="seminars-item-title">Title'
            . '<!-- ###FIELD_WRAPPER_SUBTITLE### -->'
            . '<span class="seminars-item-subtitle"> - ###SUBTITLE###</span>'
            . '<!-- ###FIELD_WRAPPER_SUBTITLE### -->'
            . '</h3>'
            . '<!-- ###FIELD_WRAPPER_TITLE### -->'
            . '<!-- ###SINGLE_VIEW###  -->'
        );
        $this->subject->hideSubparts('FIELD_WRAPPER_SUBTITLE');
        self::assertSame(
            '<h3 class="seminars-item-title">Title'
            . '</h3>',
            $this->subject->getSubpart('SINGLE_VIEW')
        );
    }

    /**
     * @test
     */
    public function unhideSubpartInSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###OUTER_SUBPART### -->'
            . 'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
            . '<!-- ###OUTER_SUBPART### -->'
        );
        $this->subject->unhideSubparts('MY_SUBPART');
        self::assertSame(
            'Some text. '
            . 'More text. '
            . 'Even more text.',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartInSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###OUTER_SUBPART### -->'
            . 'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
            . '<!-- ###OUTER_SUBPART### -->'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        $this->subject->unhideSubparts('MY_SUBPART');
        self::assertSame(
            'Some text. '
            . 'More text. '
            . 'Even more text.',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function hideTwoSubpartsSeparately(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART_1### -->'
            . 'More text here.'
            . '<!-- ###MY_SUBPART_1### -->'
            . '<!-- ###MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART_1');
        $this->subject->hideSubparts('MY_SUBPART_2');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideTwoSubpartsWithoutSpaceAfterComma(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART_1### -->'
            . 'More text here.'
            . '<!-- ###MY_SUBPART_1### -->'
            . '<!-- ###MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART_1,MY_SUBPART_2');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideTwoSubpartsInReverseOrder(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART_1### -->'
            . 'More text here.'
            . '<!-- ###MY_SUBPART_1### -->'
            . '<!-- ###MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART_2,MY_SUBPART_1');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideTwoSubpartsWithSpaceAfterComma(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART_1### -->'
            . 'More text here.'
            . '<!-- ###MY_SUBPART_1### -->'
            . '<!-- ###MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART_1, MY_SUBPART_2');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideTwoSubpartsSeparately(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART_1### -->'
            . 'More text here.'
            . '<!-- ###MY_SUBPART_1### -->'
            . '<!-- ###MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART_1');
        $this->subject->hideSubparts('MY_SUBPART_2');
        $this->subject->unhideSubparts('MY_SUBPART_1');
        $this->subject->unhideSubparts('MY_SUBPART_2');
        self::assertSame(
            'Some text. '
            . 'More text here.'
            . 'More text there. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideTwoSubpartsInSameOrder(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART_1### -->'
            . 'More text here.'
            . '<!-- ###MY_SUBPART_1### -->'
            . '<!-- ###MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART_1,MY_SUBPART_2');
        $this->subject->unhideSubparts('MY_SUBPART_1,MY_SUBPART_2');
        self::assertSame(
            'Some text. '
            . 'More text here.'
            . 'More text there. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideTwoSubpartsInReverseOrder(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART_1### -->'
            . 'More text here.'
            . '<!-- ###MY_SUBPART_1### -->'
            . '<!-- ###MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART_1,MY_SUBPART_2');
        $this->subject->unhideSubparts('MY_SUBPART_2,MY_SUBPART_1');
        self::assertSame(
            'Some text. '
            . 'More text here.'
            . 'More text there. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideTwoSubpartsUnhideFirst(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART_1### -->'
            . 'More text here.'
            . '<!-- ###MY_SUBPART_1### -->'
            . '<!-- ###MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART_1,MY_SUBPART_2');
        $this->subject->unhideSubparts('MY_SUBPART_1');
        self::assertSame(
            'Some text. '
            . 'More text here.'
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideTwoSubpartsUnhideSecond(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART_1### -->'
            . 'More text here.'
            . '<!-- ###MY_SUBPART_1### -->'
            . '<!-- ###MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART_1,MY_SUBPART_2');
        $this->subject->unhideSubparts('MY_SUBPART_2');
        self::assertSame(
            'Some text. '
            . 'More text there. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function unhidePermanentlyHiddenSubpart(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text here. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        $this->subject->unhideSubparts('MY_SUBPART', 'MY_SUBPART');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function unhideOneOfTwoPermanentlyHiddenSubparts(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text here. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        $this->subject->unhideSubparts('MY_SUBPART', 'MY_SUBPART,MY_OTHER_SUBPART');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function unhideSubpartAndPermanentlyHideAnother(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text here. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        $this->subject->unhideSubparts('MY_SUBPART', 'MY_OTHER_SUBPART');
        self::assertSame(
            'Some text. '
            . 'More text here. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function unhidePermanentlyHiddenSubpartWithPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###SUBPART### -->'
            . 'Some text. '
            . '<!-- ###SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
            . 'More text here. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        $this->subject->unhideSubparts('SUBPART', 'SUBPART', 'MY');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function unhideOneOfTwoPermanentlyHiddenSubpartsWithPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###SUBPART### -->'
            . 'Some text. '
            . '<!-- ###SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
            . 'More text here. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        $this->subject->unhideSubparts('SUBPART', 'SUBPART,OTHER_SUBPART', 'MY');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function unhideSubpartAndPermanentlyHideAnotherWithPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###SUBPART### -->'
            . 'Some text. '
            . '<!-- ###SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
            . 'More text here. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        $this->subject->unhideSubparts('SUBPART', 'OTHER_SUBPART', 'MY');
        self::assertSame(
            'Some text. '
            . 'More text here. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function subpartIsInvisibleIfTheSubpartNameIsEmpty(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
        );
        self::assertFalse(
            $this->subject->isSubpartVisible('')
        );
    }

    /**
     * @test
     */
    public function noExistentSubpartIsInvisible(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
        );
        self::assertFalse(
            $this->subject->isSubpartVisible('FOO')
        );
    }

    /**
     * @test
     */
    public function subpartIsVisibleByDefault(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
        );
        self::assertTrue(
            $this->subject->isSubpartVisible('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function subpartIsNotVisibleAfterHiding(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        self::assertFalse(
            $this->subject->isSubpartVisible('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function subpartIsVisibleAfterHidingAndUnhiding(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        $this->subject->unhideSubparts('MY_SUBPART');
        self::assertTrue(
            $this->subject->isSubpartVisible('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function getSubpartReturnsContentOfVisibleSubpartThatWasFilledWhenHidden(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->hideSubparts('MY_SUBPART');
        $this->subject->setSubpart('MY_SUBPART', 'foo');
        $this->subject->unhideSubparts('MY_SUBPART');
        self::assertSame(
            'foo',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayWithCompleteTemplateHidesSubpart(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'More text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayWithCompleteTemplateHidesOverwrittenSubpart(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->setSubpart('MY_SUBPART', 'More text. ');
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function unhideSubpartsArrayWithCompleteTemplateUnhidesSubpart(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'More text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->unhideSubpartsArray(['MY_SUBPART']);
        self::assertSame(
            'Some text. ' .
            'More text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayWithCompleteTemplateHidesAndUnhidesSubpart(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'More text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        $this->subject->unhideSubpartsArray(['MY_SUBPART']);
        self::assertSame(
            'Some text. ' .
            'More text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayHidesSubpartInSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###OUTER_SUBPART### -->' .
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'More text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.' .
            '<!-- ###OUTER_SUBPART### -->'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayHidesSubpartInNestedSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###SINGLE_VIEW###  -->' .
            '<!-- ###FIELD_WRAPPER_TITLE### -->' .
            '<h3 class="seminars-item-title">Title' .
            '<!-- ###FIELD_WRAPPER_SUBTITLE### -->' .
            '<span class="seminars-item-subtitle"> - ###SUBTITLE###</span>' .
            '<!-- ###FIELD_WRAPPER_SUBTITLE### -->' .
            '</h3>' .
            '<!-- ###FIELD_WRAPPER_TITLE### -->' .
            '<!-- ###SINGLE_VIEW###  -->'
        );
        $this->subject->hideSubpartsArray(['FIELD_WRAPPER_SUBTITLE']);
        self::assertSame(
            '<h3 class="seminars-item-title">Title' .
            '</h3>',
            $this->subject->getSubpart('SINGLE_VIEW')
        );
    }

    /**
     * @test
     */
    public function unhideSubpartsArrayUnhidesSubpartInSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###OUTER_SUBPART### -->' .
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'More text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.' .
            '<!-- ###OUTER_SUBPART### -->'
        );
        $this->subject->unhideSubpartsArray(['MY_SUBPART']);
        self::assertSame(
            'Some text. ' .
            'More text. ' .
            'Even more text.',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesAndUnhidesSubpartInSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###OUTER_SUBPART### -->' .
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'More text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.' .
            '<!-- ###OUTER_SUBPART### -->'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        $this->subject->unhideSubpartsArray(['MY_SUBPART']);
        self::assertSame(
            'Some text. ' .
            'More text. ' .
            'Even more text.',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayHidesTwoSubpartsSeparately(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART_1### -->' .
            'More text here.' .
            '<!-- ###MY_SUBPART_1### -->' .
            '<!-- ###MY_SUBPART_2### -->' .
            'More text there. ' .
            '<!-- ###MY_SUBPART_2### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART_1']);
        $this->subject->hideSubpartsArray(['MY_SUBPART_2']);
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayHidesTwoSubparts(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART_1### -->' .
            'More text here.' .
            '<!-- ###MY_SUBPART_1### -->' .
            '<!-- ###MY_SUBPART_2### -->' .
            'More text there. ' .
            '<!-- ###MY_SUBPART_2### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART_1', 'MY_SUBPART_2']);
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayHidesTwoSubpartsInReverseOrder(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART_1### -->' .
            'More text here.' .
            '<!-- ###MY_SUBPART_1### -->' .
            '<!-- ###MY_SUBPART_2### -->' .
            'More text there. ' .
            '<!-- ###MY_SUBPART_2### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART_2', 'MY_SUBPART_1']);
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesAndUnhidesTwoSubpartsSeparately(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART_1### -->' .
            'More text here.' .
            '<!-- ###MY_SUBPART_1### -->' .
            '<!-- ###MY_SUBPART_2### -->' .
            'More text there. ' .
            '<!-- ###MY_SUBPART_2### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART_1']);
        $this->subject->hideSubpartsArray(['MY_SUBPART_2']);
        $this->subject->unhideSubpartsArray(['MY_SUBPART_1']);
        $this->subject->unhideSubpartsArray(['MY_SUBPART_2']);
        self::assertSame(
            'Some text. ' .
            'More text here.' .
            'More text there. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesAndUnhidesTwoSubpartsInSameOrder(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART_1### -->' .
            'More text here.' .
            '<!-- ###MY_SUBPART_1### -->' .
            '<!-- ###MY_SUBPART_2### -->' .
            'More text there. ' .
            '<!-- ###MY_SUBPART_2### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART_1', 'MY_SUBPART_2']);
        $this->subject->unhideSubpartsArray(['MY_SUBPART_1', 'MY_SUBPART_2']);
        self::assertSame(
            'Some text. ' .
            'More text here.' .
            'More text there. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesAndUnhidesTwoSubpartsInReverseOrder(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART_1### -->' .
            'More text here.' .
            '<!-- ###MY_SUBPART_1### -->' .
            '<!-- ###MY_SUBPART_2### -->' .
            'More text there. ' .
            '<!-- ###MY_SUBPART_2### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART_1', 'MY_SUBPART_2']);
        $this->subject->unhideSubpartsArray(['MY_SUBPART_2', 'MY_SUBPART_1']);
        self::assertSame(
            'Some text. ' .
            'More text here.' .
            'More text there. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesTwoSubpartsAndUnhidesTheFirst(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART_1### -->' .
            'More text here.' .
            '<!-- ###MY_SUBPART_1### -->' .
            '<!-- ###MY_SUBPART_2### -->' .
            'More text there. ' .
            '<!-- ###MY_SUBPART_2### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART_1', 'MY_SUBPART_2']);
        $this->subject->unhideSubpartsArray(['MY_SUBPART_1']);
        self::assertSame(
            'Some text. ' .
            'More text here.' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesTwoSubpartsAndUnhidesTheSecond(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART_1### -->' .
            'More text here.' .
            '<!-- ###MY_SUBPART_1### -->' .
            '<!-- ###MY_SUBPART_2### -->' .
            'More text there. ' .
            '<!-- ###MY_SUBPART_2### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART_1', 'MY_SUBPART_2']);
        $this->subject->unhideSubpartsArray(['MY_SUBPART_2']);
        self::assertSame(
            'Some text. ' .
            'More text there. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesPermanentlyHiddenSubpart(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'More text here. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        $this->subject->unhideSubpartsArray(
            ['MY_SUBPART'],
            ['MY_SUBPART']
        );
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesOneOfTwoPermanentlyHiddenSubparts(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'More text here. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        $this->subject->unhideSubpartsArray(
            ['MY_SUBPART'],
            ['MY_SUBPART', 'MY_OTHER_SUBPART']
        );
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayUnhidesSubpartAndPermanentlyHidesAnother(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###MY_SUBPART### -->' .
            'More text here. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        $this->subject->unhideSubpartsArray(
            ['MY_SUBPART'],
            ['MY_OTHER_SUBPART']
        );
        self::assertSame(
            'Some text. ' .
            'More text here. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesPermanentlyHiddenSubpartWithPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###SUBPART### -->' .
            'Some text. ' .
            '<!-- ###SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->' .
            'More text here. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        $this->subject->unhideSubpartsArray(
            ['SUBPART'],
            ['SUBPART'],
            'MY'
        );
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesOneOfTwoPermanentlyHiddenSubpartsWithPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###SUBPART### -->' .
            'Some text. ' .
            '<!-- ###SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->' .
            'More text here. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        $this->subject->unhideSubpartsArray(
            ['SUBPART'],
            ['SUBPART', 'OTHER_SUBPART'],
            'MY'
        );
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayUnhidesSubpartAndPermanentlyHidesAnotherWithPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###SUBPART### -->' .
            'Some text. ' .
            '<!-- ###SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->' .
            'More text here. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        $this->subject->unhideSubpartsArray(
            ['SUBPART'],
            ['OTHER_SUBPART'],
            'MY'
        );
        self::assertSame(
            'Some text. ' .
            'More text here. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayResultsInNotVisibleSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        self::assertFalse(
            $this->subject->isSubpartVisible('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayResultsInVisibleSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        $this->subject->unhideSubpartsArray(['MY_SUBPART']);
        self::assertTrue(
            $this->subject->isSubpartVisible('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayWithFilledSubpartWhenHiddenReturnsContentOfUnhiddenSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART']);
        $this->subject->setSubpart('MY_SUBPART', 'foo');
        $this->subject->unhideSubpartsArray(['MY_SUBPART']);
        self::assertSame(
            'foo',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    ////////////////////////////////
    // Tests for setting subparts.
    ////////////////////////////////

    /**
     * @test
     */
    public function setSubpartNotEmptyGetCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text.'
            . '<!-- ###MY_SUBPART### -->'
            . ' Even more text.'
        );
        $this->subject->setSubpart('MY_SUBPART', 'foo');
        self::assertSame(
            'Some text. '
            . 'foo'
            . ' Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setSubpartNotEmptyGetSubpart(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text.'
            . '<!-- ###MY_SUBPART### -->'
            . ' Even more text.'
        );
        $this->subject->setSubpart('MY_SUBPART', 'foo');
        self::assertSame(
            'foo',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setNewSubpartNotEmptyGetSubpart(): void
    {
        $this->subject->processTemplate(
            'Some text.'
        );
        $this->subject->setSubpart('MY_SUBPART', 'foo');
        self::assertSame(
            'foo',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setNewSubpartWithNameWithSpaceThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'The value of the parameter $subpartName is not valid.'
        );

        $this->subject->processTemplate(
            'Some text.'
        );
        $this->subject->setSubpart('MY SUBPART', 'foo');
    }

    /**
     * @test
     */
    public function setNewSubpartWithNameWithUtf8UmlautThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'The value of the parameter $subpartName is not valid.'
        );

        $this->subject->processTemplate(
            'Some text.'
        );
        $this->subject->setSubpart('MY_SÜBPART', 'foo');
    }

    /**
     * @test
     */
    public function setNewSubpartWithNameWithUnderscoreSuffixThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'The value of the parameter $subpartName is not valid.'
        );

        $this->subject->processTemplate(
            'Some text.'
        );
        $this->subject->setSubpart('MY_SUBPART_', 'foo');
    }

    /**
     * @test
     */
    public function setNewSubpartWithNameStartingWithUnderscoreThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'The value of the parameter $subpartName is not valid.'
        );

        $this->subject->processTemplate(
            'Some text.'
        );
        $this->subject->setSubpart('_MY_SUBPART', 'foo');
    }

    /**
     * @test
     */
    public function setNewSubpartWithNameStartingWithNumberThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'The value of the parameter $subpartName is not valid.'
        );

        $this->subject->processTemplate(
            'Some text.'
        );
        $this->subject->setSubpart('1_MY_SUBPART', 'foo');
    }

    /**
     * @test
     */
    public function setSubpartNotEmptyGetOuterSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###OUTER_SUBPART### -->'
            . 'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text.'
            . '<!-- ###MY_SUBPART### -->'
            . ' Even more text.'
            . '<!-- ###OUTER_SUBPART### -->'
        );
        $this->subject->setSubpart('MY_SUBPART', 'foo');
        self::assertSame(
            'Some text. foo Even more text.',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setSubpartToEmptyGetCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text.'
            . '<!-- ###MY_SUBPART### -->'
            . ' Even more text.'
        );
        $this->subject->setSubpart('MY_SUBPART', '');
        self::assertSame(
            'Some text. '
            . ' Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setSubpartToEmptyGetSubpart(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text.'
            . '<!-- ###MY_SUBPART### -->'
            . ' Even more text.'
        );
        $this->subject->setSubpart('MY_SUBPART', '');
        self::assertSame(
            '',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setSubpartToEmptyGetOuterSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###OUTER_SUBPART### -->'
            . 'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'More text.'
            . '<!-- ###MY_SUBPART### -->'
            . ' Even more text.'
            . '<!-- ###OUTER_SUBPART### -->'
        );
        $this->subject->setSubpart('MY_SUBPART', '');
        self::assertSame(
            'Some text.  Even more text.',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setSubpartAndGetSubpartCanHaveUtf8UmlautsInSubpartContent(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->setSubpart('MY_SUBPART', 'äöüßÄÖÜßéèáàóò');

        self::assertSame(
            'äöüßÄÖÜßéèáàóò',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setSubpartAndGetSubpartCanHaveIso88591UmlautsInSubpartContent(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->'
        );
        // 228 = ä, 223 = ß (in ISO8859-1)
        $this->subject->setSubpart('MY_SUBPART', \chr(228) . \chr(223));

        self::assertSame(
            \chr(228) . \chr(223),
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    //////////////////////////////////////////////////////
    // Tests for setting markers within nested subparts.
    //////////////////////////////////////////////////////

    /**
     * @test
     */
    public function setMarkerInSubpartWithinCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'This is some template code. ###MARKER### More text.'
            . '<!-- ###MY_SUBPART### -->'
            . ' Even more text.'
        );
        $this->subject->setMarker('marker', 'foo');
        self::assertSame(
            'Some text. '
            . 'This is some template code. foo More text.'
            . ' Even more text.',
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function setMarkerInSubpartWithinOtherSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###OUTER_SUBPART### -->'
            . 'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . 'This is some template code. ###MARKER### More text.'
            . '<!-- ###MY_SUBPART### -->'
            . ' Even more text.'
            . '<!-- ###OUTER_SUBPART### -->'
        );
        $this->subject->setMarker('marker', 'foo');
        self::assertSame(
            'Some text. '
            . 'This is some template code. foo More text.'
            . ' Even more text.',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setMarkerInOverwrittenSubpartWithinCompleteTemplate(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
            . ' Even more text.'
        );
        $this->subject->setSubpart(
            'MY_SUBPART',
            'This is some template code. ###MARKER### More text.'
        );
        $this->subject->setMarker('marker', 'foo');
        self::assertSame(
            'Some text. '
            . 'This is some template code. foo More text.'
            . ' Even more text.',
            $this->subject->render()
        );
    }

    /**
     * @test
     */
    public function setMarkerInOverwrittenSubpartWithinOtherSubpart(): void
    {
        $this->subject->processTemplate(
            '<!-- ###OUTER_SUBPART### -->'
            . 'Some text. '
            . '<!-- ###MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
            . ' Even more text.'
            . '<!-- ###OUTER_SUBPART### -->'
        );
        $this->subject->setSubpart(
            'MY_SUBPART',
            'This is some template code. ###MARKER### More text.'
        );
        $this->subject->setMarker('marker', 'foo');
        self::assertSame(
            'Some text. '
            . 'This is some template code. foo More text.'
            . ' Even more text.',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function setMarkerWithinNestedInnerSubpart(): void
    {
        $templateCode = 'Text before the subpart'
            . '<!-- ###MY_SUBPART### -->'
            . 'outer start, '
            . '<!-- ###OUTER_SUBPART### -->'
            . 'inner start, '
            . '<!-- ###INNER_SUBPART### -->'
            . '###MARKER###'
            . '<!-- ###INNER_SUBPART### -->'
            . 'inner end, '
            . '<!-- ###OUTER_SUBPART### -->'
            . 'outer end '
            . '<!-- ###MY_SUBPART### -->'
            . 'Text after the subpart.';
        $this->subject->processTemplate(
            $templateCode
        );
        $this->subject->setMarker('marker', 'foo ');

        self::assertSame(
            'outer start, inner start, foo inner end, outer end ',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    ////////////////////////////////////////////////////////////
    // Tests for using the prefix to marker and subpart names.
    ////////////////////////////////////////////////////////////

    /**
     * @test
     */
    public function setMarkerWithPrefix(): void
    {
        $this->subject->processTemplate(
            'This is some template code. '
            . '###FIRST_MARKER### ###MARKER### More text.'
        );
        $this->subject->setMarker('marker', 'foo', 'first');
        self::assertSame(
            'This is some template code. foo ###MARKER### More text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setSubpartWithPrefix(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###FIRST_MY_SUBPART### -->'
            . 'More text here. '
            . '<!-- ###FIRST_MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->setSubpart('MY_SUBPART', 'foo', 'FIRST');
        self::assertSame(
            'Some text. '
            . 'foo'
            . 'More text there. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideSubpartWithPrefix(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###FIRST_MY_SUBPART### -->'
            . 'More text here. '
            . '<!-- ###FIRST_MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('MY_SUBPART', 'FIRST');
        self::assertSame(
            'Some text. '
            . 'More text there. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartWithPrefix(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###FIRST_MY_SUBPART### -->'
            . 'More text here. '
            . '<!-- ###FIRST_MY_SUBPART### -->'
            . '<!-- ###MY_SUBPART### -->'
            . 'More text there. '
            . '<!-- ###MY_SUBPART### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('FIRST_MY_SUBPART');
        $this->subject->unhideSubparts('MY_SUBPART', '', 'FIRST');
        self::assertSame(
            'Some text. '
            . 'More text here. '
            . 'More text there. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideTwoSubpartsWithPrefix(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###FIRST_MY_SUBPART_1### -->'
            . 'More text here. '
            . '<!-- ###FIRST_MY_SUBPART_1### -->'
            . '<!-- ###FIRST_MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###FIRST_MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('1,2', 'FIRST_MY_SUBPART');
        self::assertSame(
            'Some text. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideTwoSubpartsWithPrefix(): void
    {
        $this->subject->processTemplate(
            'Some text. '
            . '<!-- ###FIRST_MY_SUBPART_1### -->'
            . 'More text here. '
            . '<!-- ###FIRST_MY_SUBPART_1### -->'
            . '<!-- ###FIRST_MY_SUBPART_2### -->'
            . 'More text there. '
            . '<!-- ###FIRST_MY_SUBPART_2### -->'
            . 'Even more text.'
        );
        $this->subject->hideSubparts('FIRST_MY_SUBPART_1');
        $this->subject->hideSubparts('FIRST_MY_SUBPART_2');
        $this->subject->unhideSubparts('1,2', '', 'FIRST_MY_SUBPART');
        self::assertSame(
            'Some text. '
            . 'More text here. '
            . 'More text there. '
            . 'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayHidesSubpartWithPrefix(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###FIRST_MY_SUBPART### -->' .
            'More text here. ' .
            '<!-- ###FIRST_MY_SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->' .
            'More text there. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['MY_SUBPART'], 'FIRST');
        self::assertSame(
            'Some text. ' .
            'More text there. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideSubpartsArrayHidesTwoSubpartsWithPrefix(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###FIRST_MY_SUBPART_1### -->' .
            'More text here. ' .
            '<!-- ###FIRST_MY_SUBPART_1### -->' .
            '<!-- ###FIRST_MY_SUBPART_2### -->' .
            'More text there. ' .
            '<!-- ###FIRST_MY_SUBPART_2### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(
            ['1', '2'],
            'FIRST_MY_SUBPART'
        );
        self::assertSame(
            'Some text. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesAndUnhidesSubpartWithPrefix(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###FIRST_MY_SUBPART### -->' .
            'More text here. ' .
            '<!-- ###FIRST_MY_SUBPART### -->' .
            '<!-- ###MY_SUBPART### -->' .
            'More text there. ' .
            '<!-- ###MY_SUBPART### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['FIRST_MY_SUBPART']);
        $this->subject->unhideSubpartsArray(['MY_SUBPART'], [''], 'FIRST');
        self::assertSame(
            'Some text. ' .
            'More text here. ' .
            'More text there. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function hideAndUnhideSubpartsArrayHidesAndUnhidesTwoSubpartsWithPrefix(): void
    {
        $this->subject->processTemplate(
            'Some text. ' .
            '<!-- ###FIRST_MY_SUBPART_1### -->' .
            'More text here. ' .
            '<!-- ###FIRST_MY_SUBPART_1### -->' .
            '<!-- ###FIRST_MY_SUBPART_2### -->' .
            'More text there. ' .
            '<!-- ###FIRST_MY_SUBPART_2### -->' .
            'Even more text.'
        );
        $this->subject->hideSubpartsArray(['FIRST_MY_SUBPART_1']);
        $this->subject->hideSubpartsArray(['FIRST_MY_SUBPART_2']);
        $this->subject->unhideSubpartsArray(
            ['1', '2'],
            [''],
            'FIRST_MY_SUBPART'
        );
        self::assertSame(
            'Some text. ' .
            'More text here. ' .
            'More text there. ' .
            'Even more text.',
            $this->subject->getSubpart()
        );
    }

    /////////////////////////////////////////////////////////////////////
    // Test for conditional filling and hiding of markers and subparts.
    /////////////////////////////////////////////////////////////////////

    /**
     * @test
     */
    public function setMarkerIfNotZeroWithPositiveInteger(): void
    {
        $this->subject->processTemplate(
            '###MARKER###'
        );

        self::assertTrue(
            $this->subject->setMarkerIfNotZero('marker', 42)
        );
        self::assertSame(
            '42',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setMarkerIfNotZeroWithNegativeInteger(): void
    {
        $this->subject->processTemplate(
            '###MARKER###'
        );

        self::assertTrue(
            $this->subject->setMarkerIfNotZero('marker', -42)
        );
        self::assertSame(
            '-42',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setMarkerIfNotZeroWithZero(): void
    {
        $this->subject->processTemplate(
            '###MARKER###'
        );

        self::assertFalse(
            $this->subject->setMarkerIfNotZero('marker', 0)
        );
        self::assertSame(
            '###MARKER###',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setMarkerIfNotZeroWithPositiveIntegerWithPrefix(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER###'
        );

        self::assertTrue(
            $this->subject->setMarkerIfNotZero('marker', 42, 'MY')
        );
        self::assertSame(
            '42',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setMarkerIfNotZeroWithNegativeIntegerWithPrefix(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER###'
        );

        self::assertTrue(
            $this->subject->setMarkerIfNotZero('marker', -42, 'MY')
        );
        self::assertSame(
            '-42',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setMarkerIfNotZeroWithZeroWithPrefix(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER###'
        );

        self::assertFalse(
            $this->subject->setMarkerIfNotZero('marker', 0, 'MY')
        );
        self::assertSame(
            '###MY_MARKER###',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setMarkerIfNotEmptyWithNotEmpty(): void
    {
        $this->subject->processTemplate(
            '###MARKER###'
        );

        self::assertTrue(
            $this->subject->setMarkerIfNotEmpty('marker', 'foo')
        );
        self::assertSame(
            'foo',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setMarkerIfNotEmptyWithEmpty(): void
    {
        $this->subject->processTemplate(
            '###MARKER###'
        );

        self::assertFalse(
            $this->subject->setMarkerIfNotEmpty('marker', '')
        );
        self::assertSame(
            '###MARKER###',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setMarkerIfNotEmptyWithNotEmptyWithPrefix(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER###'
        );

        self::assertTrue(
            $this->subject->setMarkerIfNotEmpty('marker', 'foo', 'MY')
        );
        self::assertSame(
            'foo',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setMarkerIfNotEmptyWithEmptyWithPrefix(): void
    {
        $this->subject->processTemplate(
            '###MY_MARKER###'
        );

        self::assertFalse(
            $this->subject->setMarkerIfNotEmpty('marker', '', 'MY')
        );
        self::assertSame(
            '###MY_MARKER###',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerWithTrue(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertTrue(
            $this->subject->setOrDeleteMarker(
                'marker',
                true,
                'foo',
                '',
                'WRAPPER'
            )
        );
        self::assertSame(
            'foo',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerWithFalse(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertFalse(
            $this->subject->setOrDeleteMarker(
                'marker',
                false,
                'foo',
                '',
                'WRAPPER'
            )
        );
        self::assertSame(
            '',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerWithTrueWithMarkerPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MY_MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertTrue(
            $this->subject->setOrDeleteMarker(
                'marker',
                true,
                'foo',
                'MY',
                'WRAPPER'
            )
        );
        self::assertSame(
            'foo',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerWithFalseWithMarkerPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MY_MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertFalse(
            $this->subject->setOrDeleteMarker(
                'marker',
                false,
                'foo',
                'MY',
                'WRAPPER'
            )
        );
        self::assertSame(
            '',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerIfNotZeroWithZero(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertFalse(
            $this->subject->setOrDeleteMarkerIfNotZero(
                'marker',
                0,
                '',
                'WRAPPER'
            )
        );
        self::assertSame(
            '',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerIfNotZeroWithPositiveIntegers(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertTrue(
            $this->subject->setOrDeleteMarkerIfNotZero(
                'marker',
                42,
                '',
                'WRAPPER'
            )
        );
        self::assertSame(
            '42',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerIfNotZeroWithNegativeIntegers(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertTrue(
            $this->subject->setOrDeleteMarkerIfNotZero(
                'marker',
                -42,
                '',
                'WRAPPER'
            )
        );
        self::assertSame(
            '-42',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerIfNotZeroWithZeroWithMarkerPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MY_MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertFalse(
            $this->subject->setOrDeleteMarkerIfNotZero(
                'marker',
                0,
                'MY',
                'WRAPPER'
            )
        );
        self::assertSame(
            '',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerIfNotZeroWithPositiveIntegerWithMarkerPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MY_MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertTrue(
            $this->subject->setOrDeleteMarkerIfNotZero(
                'marker',
                42,
                'MY',
                'WRAPPER'
            )
        );
        self::assertSame(
            '42',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerIfNotZeroWithNegativeIntegerWithMarkerPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MY_MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertTrue(
            $this->subject->setOrDeleteMarkerIfNotZero(
                'marker',
                -42,
                'MY',
                'WRAPPER'
            )
        );
        self::assertSame(
            '-42',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerIfNotEmptyWithEmpty(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertFalse(
            $this->subject->setOrDeleteMarkerIfNotEmpty(
                'marker',
                '',
                '',
                'WRAPPER'
            )
        );
        self::assertSame(
            '',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerIfNotEmptyWithNotEmpty(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertTrue(
            $this->subject->setOrDeleteMarkerIfNotEmpty(
                'marker',
                'foo',
                '',
                'WRAPPER'
            )
        );
        self::assertSame(
            'foo',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerIfNotEmptyWithEmptyWithMarkerPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MY_MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertFalse(
            $this->subject->setOrDeleteMarkerIfNotEmpty(
                'marker',
                '',
                'MY',
                'WRAPPER'
            )
        );
        self::assertSame(
            '',
            $this->subject->getSubpart()
        );
    }

    /**
     * @test
     */
    public function setOrDeleteMarkerIfNotEmptyWithNotEmptyWithMarkerPrefix(): void
    {
        $this->subject->processTemplate(
            '<!-- ###WRAPPER_MARKER### -->'
            . '###MY_MARKER###'
            . '<!-- ###WRAPPER_MARKER### -->'
        );

        self::assertTrue(
            $this->subject->setOrDeleteMarkerIfNotEmpty(
                'marker',
                'foo',
                'MY',
                'WRAPPER'
            )
        );
        self::assertSame(
            'foo',
            $this->subject->getSubpart()
        );
    }

    ///////////////////////////////////////////////////
    // Test concerning unclosed markers and subparts.
    ///////////////////////////////////////////////////

    /**
     * @test
     */
    public function unclosedMarkersAreIgnored(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '###MY_MARKER_1### '
            . '###MY_MARKER_2 '
            . '###MY_MARKER_3# '
            . '###MY_MARKER_4## '
            . '###MY_MARKER_5###'
            . '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->setMarker('my_marker_1', 'test 1');
        $this->subject->setMarker('my_marker_2', 'test 2');
        $this->subject->setMarker('my_marker_3', 'test 3');
        $this->subject->setMarker('my_marker_4', 'test 4');
        $this->subject->setMarker('my_marker_5', 'test 5');

        self::assertSame(
            'test 1 '
            . '###MY_MARKER_2 '
            . '###MY_MARKER_3# '
            . '###MY_MARKER_4## '
            . 'test 5',
            $this->subject->getSubpart()
        );
        self::assertSame(
            'test 1 '
            . '###MY_MARKER_2 '
            . '###MY_MARKER_3# '
            . '###MY_MARKER_4## '
            . 'test 5',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    /**
     * @test
     */
    public function unclosedSubpartsAreIgnored(): void
    {
        $this->subject->processTemplate(
            'Text before. '
            . '<!-- ###UNCLOSED_SUBPART_1### -->'
            . '<!-- ###OUTER_SUBPART### -->'
            . '<!-- ###UNCLOSED_SUBPART_2### -->'
            . '<!-- ###INNER_SUBPART### -->'
            . '<!-- ###UNCLOSED_SUBPART_3### -->'
            . 'Inner text. '
            . '<!-- ###UNCLOSED_SUBPART_4### -->'
            . '<!-- ###INNER_SUBPART### -->'
            . '<!-- ###UNCLOSED_SUBPART_5### -->'
            . '<!-- ###OUTER_SUBPART### -->'
            . '<!-- ###UNCLOSED_SUBPART_6### -->'
            . 'Text after.'
        );

        self::assertSame(
            'Text before. '
            . '<!-- ###UNCLOSED_SUBPART_1### -->'
            . '<!-- ###UNCLOSED_SUBPART_2### -->'
            . '<!-- ###UNCLOSED_SUBPART_3### -->'
            . 'Inner text. '
            . '<!-- ###UNCLOSED_SUBPART_4### -->'
            . '<!-- ###UNCLOSED_SUBPART_5### -->'
            . '<!-- ###UNCLOSED_SUBPART_6### -->'
            . 'Text after.',
            $this->subject->getSubpart()
        );
        self::assertSame(
            '<!-- ###UNCLOSED_SUBPART_2### -->'
            . '<!-- ###UNCLOSED_SUBPART_3### -->'
            . 'Inner text. '
            . '<!-- ###UNCLOSED_SUBPART_4### -->'
            . '<!-- ###UNCLOSED_SUBPART_5### -->',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function unclosedSubpartMarkersAreIgnored(): void
    {
        $this->subject->processTemplate(
            'Text before. '
            . '<!-- ###UNCLOSED_SUBPART_1###'
            . '<!-- ###OUTER_SUBPART### -->'
            . '<!-- ###UNCLOSED_SUBPART_2 -->'
            . '<!-- ###INNER_SUBPART### -->'
            . '<!-- ###UNCLOSED_SUBPART_3### --'
            . 'Inner text. '
            . '<!-- UNCLOSED_SUBPART_4### -->'
            . '<!-- ###INNER_SUBPART### -->'
            . ' ###UNCLOSED_SUBPART_5### -->'
            . '<!-- ###OUTER_SUBPART### -->'
            . '<!-- ###UNCLOSED_SUBPART_6### -->'
            . 'Text after.'
        );

        self::assertSame(
            'Text before. '
            . '<!-- ###UNCLOSED_SUBPART_1###'
            . '<!-- ###UNCLOSED_SUBPART_2 -->'
            . '<!-- ###UNCLOSED_SUBPART_3### --'
            . 'Inner text. '
            . '<!-- UNCLOSED_SUBPART_4### -->'
            . ' ###UNCLOSED_SUBPART_5### -->'
            . '<!-- ###UNCLOSED_SUBPART_6### -->'
            . 'Text after.',
            $this->subject->getSubpart()
        );
        self::assertSame(
            '<!-- ###UNCLOSED_SUBPART_2 -->'
            . '<!-- ###UNCLOSED_SUBPART_3### --'
            . 'Inner text. '
            . '<!-- UNCLOSED_SUBPART_4### -->'
            . ' ###UNCLOSED_SUBPART_5### -->',
            $this->subject->getSubpart('OUTER_SUBPART')
        );
    }

    /**
     * @test
     */
    public function invalidMarkerNamesAreIgnored(): void
    {
        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART### -->'
            . '###MARKER 1### '
            . '###MARKER-2### '
            . '###marker_3### '
            . '###MÄRKER_4### '
            . '<!-- ###MY_SUBPART### -->'
        );
        $this->subject->setMarker('marker 1', 'foo');
        $this->subject->setMarker('marker-2', 'foo');
        $this->subject->setMarker('marker_3', 'foo');
        $this->subject->setMarker('märker_4', 'foo');

        self::assertSame(
            '###MARKER 1### '
            . '###MARKER-2### '
            . '###marker_3### '
            . '###MÄRKER_4### ',
            $this->subject->getSubpart()
        );
        self::assertSame(
            '###MARKER 1### '
            . '###MARKER-2### '
            . '###marker_3### '
            . '###MÄRKER_4### ',
            $this->subject->getSubpart('MY_SUBPART')
        );
    }

    ///////////////////////////////////////////////////
    // Tests for getting subparts with invalid names.
    ///////////////////////////////////////////////////

    /**
     * @test
     */
    public function getSubpartWithNameWithSpaceThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'The value of the parameter $key is not valid.'
        );

        $this->subject->processTemplate(
            '<!-- ###MY SUBPART### -->'
            . 'Some text.'
            . '<!-- ###MY SUBPART### -->'
        );

        $this->subject->getSubpart('MY SUBPART');
    }

    /**
     * @test
     */
    public function getSubpartWithNameWithUtf8UmlautThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'The value of the parameter $key is not valid.'
        );

        $this->subject->processTemplate(
            '<!-- ###MY_SÜBPART### -->'
            . 'Some text.'
            . '<!-- ###MY_SÜBPART### -->'
        );

        $this->subject->getSubpart('MY_SÜBPART');
    }

    /**
     * @test
     */
    public function getSubpartWithNameWithUnderscoreSuffixThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'The value of the parameter $key is not valid.'
        );

        $this->subject->processTemplate(
            '<!-- ###MY_SUBPART_### -->'
            . 'Some text.'
            . '<!-- ###MY_SUBPART_### -->'
        );

        $this->subject->getSubpart('MY_SUBPART_');
    }

    /**
     * @test
     */
    public function getSubpartWithNameStartingWithUnderscoreThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'The value of the parameter $key is not valid.'
        );

        $this->subject->processTemplate(
            '<!-- ###_MY_SUBPART### -->'
            . 'Some text.'
            . '<!-- ###_MY_SUBPART### -->'
        );

        $this->subject->getSubpart('_MY_SUBPART');
    }

    /**
     * @test
     */
    public function getSubpartWithNameStartingWithNumberThrowsException(): void
    {
        $this->expectException(
            \InvalidArgumentException::class
        );
        $this->expectExceptionMessage(
            'The value of the parameter $key is not valid.'
        );

        $this->subject->processTemplate(
            '<!-- ###1_MY_SUBPART### -->'
            . 'Some text.'
            . '<!-- ###1_MY_SUBPART### -->'
        );

        $this->subject->getSubpart('1_MY_SUBPART');
    }

    /**
     * @test
     */
    public function getSubpartWithLowercaseNameWithUsingLowercaseThrowsException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            '$key contained the subpart name "my_subpart", but only the following subparts are available: ()'
        );
        $this->expectExceptionCode(1_632_760_625);

        $this->subject->processTemplate(
            '<!-- ###my_subpart### -->' .
            'Some text.' .
            '<!-- ###my_subpart### -->'
        );

        $this->subject->getSubpart('my_subpart');
    }

    /**
     * @test
     */
    public function getSubpartWithLowercaseNameWithUsingUppercaseThrowsException(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            '$key contained the subpart name "MY_SUBPART", but only the following subparts are available: ()'
        );
        $this->expectExceptionCode(1_632_760_625);

        $this->subject->processTemplate(
            '<!-- ###my_subpart### -->' .
            'Some text.' .
            '<!-- ###my_subpart### -->'
        );

        $this->subject->getSubpart('MY_SUBPART');
    }

    // Tests concerning getLabelMarkers

    /**
     * @test
     */
    public function getLabelMarkersForNoMatchesReturnsEmptyArray(): void
    {
        $this->subject->processTemplate('###BLA###');

        self::assertSame(
            [],
            $this->subject->getLabelMarkerNames()
        );
    }

    /**
     * @test
     */
    public function getLabelMarkersForOneMatchReturnsArrayWithCompleteMarkerName(): void
    {
        $this->subject->processTemplate('###LABEL_BAR###');

        self::assertSame(
            ['label_bar'],
            $this->subject->getLabelMarkerNames()
        );
    }

    /**
     * @test
     */
    public function getLabelMarkersForTwoIdenticalMatchesReturnsArrayWithCompleteMarkerNameOnce(): void
    {
        $this->subject->processTemplate('###LABEL_BAR### ###LABEL_BAR###');

        self::assertSame(
            ['label_bar'],
            $this->subject->getLabelMarkerNames()
        );
    }

    /**
     * @test
     */
    public function getLabelMarkersForTwoMatchesReturnsArrayWithCompleteMarkerNames(): void
    {
        $this->subject->processTemplate('###LABEL_BAR### ###LABEL_BAZ###');

        self::assertSame(
            ['label_bar', 'label_baz'],
            $this->subject->getLabelMarkerNames()
        );
    }
}
