<?php

declare(strict_types=1);

namespace Phpgeo\Formatter\Point;

use InvalidArgumentException;
use Phpgeo\Point;
use PHPUnit\Framework\TestCase;

class DecimalMinutesTest extends TestCase
{
    /**
     * @var DecimalMinutes
     */
    protected $formatter;

    protected function setUp(): void
    {
        $this->formatter = new DecimalMinutes();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    public function testSetUnitsUTF8(): void
    {
        $this->formatter->setUnits(DMS::UNITS_UTF8);

        $this->assertEquals(DMS::UNITS_UTF8, $this->formatter->getUnitType());
    }

    public function testSetUnitsASCII(): void
    {
        $this->formatter->setUnits(DMS::UNITS_ASCII);

        $this->assertEquals(DMS::UNITS_ASCII, $this->formatter->getUnitType());
    }

    public function testSetUnitsInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid unit type');

        $this->formatter->setUnits('invalid');
    }

    public function testFormatDefaultSeparator(): void
    {
        $point = new Point(52.5, 13.5);

        $this->assertEquals('52° 30.000′ 013° 30.000′', $this->formatter->format($point));
    }

    public function testFormatCustomSeparator(): void
    {
        $point = new Point(18.911306, - 155.678268);

        $this->formatter->setSeparator(', ');

        $this->assertEquals('18° 54.678′, -155° 40.696′', $this->formatter->format($point));
    }

    public function testFormatCardinalLetters(): void
    {
        $point = new Point(18.911306, - 155.678268);

        $this->formatter->setSeparator(', ')->useCardinalLetters(true);

        $this->assertEquals('18° 54.678′ N, 155° 40.696′ W', $this->formatter->format($point));
    }

    public function testFormatBothNegative(): void
    {
        $point = new Point(- 18.911306, - 155.678268);

        $this->formatter->setSeparator(', ');

        $this->assertEquals('-18° 54.678′, -155° 40.696′', $this->formatter->format($point));
    }

    public function testFormatASCIIUnits(): void
    {
        $point = new Point(- 18.911306, - 155.678268);

        $this->formatter->setSeparator(', ')->setUnits(DMS::UNITS_ASCII);

        $this->assertEquals("-18° 54.678', -155° 40.696'", $this->formatter->format($point));
    }

    public function testSetDigits(): void
    {
        $point = new Point(- 18.911306, - 155.678268);

        $this->formatter->setDigits(2);

        $this->assertEquals('-18° 54.68′ -155° 40.70′', $this->formatter->format($point));
    }

    public function testSetDecimalPoint(): void
    {
        $point = new Point(- 18.911306, - 155.678268);

        $this->formatter->setDecimalPoint(',');

        $this->assertEquals('-18° 54,678′ -155° 40,696′', $this->formatter->format($point));
    }
}