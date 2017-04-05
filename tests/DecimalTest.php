<?php
include 'decimal.php';
use \Direvus\Decimal\Decimal;

class DecimalTest extends PHPUnit\Framework\TestCase {
    /**
     * @covers \Direvus\Decimal\Decimal::__construct
     * @covers \Direvus\Decimal\Decimal::__toString
     * @dataProvider baseProvider
     */
    public function testConstructor($value, $expected){
        $d = new Decimal($value);
        $this->assertInstanceOf('\\Direvus\\Decimal\\Decimal', $d);
        $this->assertSame($expected, (string) $d);
    }

    /**
     * @expectedException DomainException
     */
    public function testInvalidConstructor(){
        $d = new Decimal('');
    }

    public function baseProvider(){
        return [
            [50, '50'],
            [-25000, '-25000'],
            [0.00001, '0.00001'],
            ['-5.000067', '-5.000067'],
            ['    abc01    ', '1'],
            ['0000005', '5'],
            ['6.22e23', '622000000000000000000000'],
            [new Decimal('-12.375'), '-12.375'],
            ['0000', '0'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::quantize
     * @dataProvider quantizeProvider
     */
    public function testQuantize($input, $exponent, $expected){
        $d = new Decimal($input);
        $this->assertSame($expected, (string) $d->quantize($exponent));
    }

    public function quantizeProvider(){
        return [
            ['12.375', 3,  '0'],
            ['12.375', 2,  '0'],
            ['12.375', 1,  '10'],
            ['12.375', 0,  '12'],
            ['12.375', -1, '12.4'],
            ['12.375', -2, '12.38'],
            ['12.375', -3, '12.375'],
            ['12.375', -4, '12.375'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::round
     * @dataProvider roundProvider
     */
    public function testRound($input, $precision, $expected){
        $d = new Decimal($input);
        $this->assertSame($expected, (string) $d->round($precision));
    }

    public function roundProvider(){
        return [
            ['12.375', -1, '12'],
            ['12.375', 0,  '12'],
            ['12.375', 1,  '12.4'],
            ['12.375', 2,  '12.38'],
            ['12.375', 3,  '12.375'],
            ['12.375', 4,  '12.375'],
            ];
    }
}
