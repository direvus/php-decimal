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
     * @covers \Direvus\Decimal\Decimal::__construct
     * @expectedException \DomainException
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
            ['1500', 3, '1500'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::quantize
     * @dataProvider quantizeMethodProvider
     */
    public function testQuantizeMethod($input, $exponent, $method, $expected){
        $d = new Decimal($input);
        $q = $d->quantize($exponent, $method);
        $this->assertSame($expected, (string) $q);
    }

    public function quantizeMethodProvider(){
        return [
            ['12.375', -2, PHP_ROUND_HALF_UP,   '12.38'],
            ['12.375', -2, PHP_ROUND_HALF_DOWN, '12.37'],
            ['12.375', -2, PHP_ROUND_HALF_EVEN, '12.38'],
            ['12.375', -2, PHP_ROUND_HALF_ODD,  '12.37'],
            ['-0.05',  -1, PHP_ROUND_HALF_UP,   '-0.1'],
            ['-0.05',  -1, PHP_ROUND_HALF_DOWN, '0'],
            ['-0.05',  -1, PHP_ROUND_HALF_EVEN, '0'],
            ['-0.05',  -1, PHP_ROUND_HALF_ODD,  '-0.1'],
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

    /**
     * @covers \Direvus\Decimal\make
     * @dataProvider baseProvider
     */
    public function testMake($input, $expected){
        $d = \Direvus\Decimal\make($input);
        $this->assertInstanceOf('\\Direvus\\Decimal\\Decimal', $d);
        $this->assertSame($expected, (string) $d);
    }

    /**
     * @covers \Direvus\Decimal\clean_value
     * @dataProvider cleanProvider
     */
    public function testCleanValue($input, $expected){
        $this->assertSame($expected, \Direvus\Decimal\clean_value($input));
    }

    public function cleanProvider(){
        return [
            ['0', '0'],
            [0, '0'],
            [0.2, '0.2'],
            [-0.1, '-0.1'],
            ['6.22e23', '6.22e23'],
            ['6.22 E 23', '6.22E23'],
            [' 1 ', '1'],
            ['=1', '1'],
            ['abc1', '1'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\clean_value
     * @expectedException \DomainException
     */
    public function testCleanValueException(){
        \Direvus\Decimal\clean_value('not a number');
    }

    /**
     * @covers \Direvus\Decimal\zeroes
     * @dataProvider zeroesProvider
     */
    public function testZeroes($length, $expected){
        $zeroes = \Direvus\Decimal\zeroes($length);
        $this->assertSame($expected, $zeroes);
    }

    public function zeroesProvider(){
        return [
            [0, ''],
            [1, '0'],
            [2, '00'],
            [3, '000'],
            [50, '00000000000000000000000000000000000000000000000000'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\scale_valid
     * @dataProvider scaleValidProvider
     */
    public function testScaleValid($scale, $expected){
        $valid = \Direvus\Decimal\scale_valid($scale);
        $this->assertSame($expected, $valid);
    }

    public function scaleValidProvider(){
        return [
            [null, false],
            [0, true],
            [1, true],
            [2, true],
            [-1, false],
            [-2, false],
            [7.5, false],
            ['', false],
            [new stdClass, false],
            ];
    }

    /**
     * @covers \Direvus\Decimal\min
     * @dataProvider minProvider
     */
    public function testMin($args, $expected){
        $result = call_user_func_array('\\Direvus\\Decimal\\min', $args);
        $this->assertSame($expected, (string) $result);
    }

    public function minProvider(){
        return [
            [[0, 1, 2], '0'],
            [[2, 1, 0], '0'],
            [[1, 0, 2], '0'],
            [[-5.0, -7.3, -25], '-25'],
            [[-100, '-1e5', -25], '-100000'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\max
     * @dataProvider maxProvider
     */
    public function testMax($args, $expected){
        $result = call_user_func_array('\\Direvus\\Decimal\\max', $args);
        $this->assertSame($expected, (string) $result);
    }

    public function maxProvider(){
        return [
            [[0, 1, 2], '2'],
            [[2, 1, 0], '2'],
            [[1, 0, 2], '2'],
            [[-5.0, -7.3, -25], '-5'],
            [[100, '1e5', 25], '100000'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\zero
     */
    public function testZero(){
        $d = \Direvus\Decimal\zero();
        $this->assertInstanceOf('\\Direvus\\Decimal\\Decimal', $d);
        $this->assertSame('0', (string) $d);
    }

    /**
     * @covers \Direvus\Decimal\one
     */
    public function testOne(){
        $d = \Direvus\Decimal\one();
        $this->assertInstanceOf('\\Direvus\\Decimal\\Decimal', $d);
        $this->assertSame('1', (string) $d);
    }

    /**
     * @covers \Direvus\Decimal\result_scale
     * @dataProvider resultScaleProvider
     */
    public function testResultScale($a, $b, $scale, $expected){
        $this->assertSame($expected, \Direvus\Decimal\result_scale(
            new Decimal($a),
            new Decimal($b),
            $scale));
    }

    public function resultScaleProvider(){
        return [
            ['0', '0', null, 0],
            ['1', '10', null, 0],
            ['1000', '10', null, 0],
            ['-10', '10', null, 0],
            ['10', '-10', null, 0],
            ['0.1', '1', null, 1],
            ['0.1', '0.01', null, 2],
            ['-0.001', '0.01', null, 3],
            ['0', '0', 3, 3],
            ['1000', '0.001', 0, 0],
            ];
    }
}
