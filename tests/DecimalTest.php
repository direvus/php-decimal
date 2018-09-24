<?php
require_once('decimal.php');
use \Direvus\Decimal\Decimal;

/**
 * @covers \Direvus\Decimal\Decimal
 */
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
     * @covers \Direvus\Decimal\Decimal::__construct
     * @expectedException \DomainException
     */
    public function testInvalidConstructor(){
        $d = new Decimal('');
    }

    /**
     * @covers \Direvus\Decimal\Decimal::copy
     * @dataProvider baseProvider
     */
    public function testCopy($input, $expected){
        $src = new Decimal($input);
        $dest = new Decimal;
        $dest->copy($src);
        $this->assertTrue($dest->eq($src));
        $this->assertSame($expected, (string) $dest);
    }

    /**
     * @covers \Direvus\Decimal\Decimal::getScale
     * @dataProvider getScaleProvider
     */
    public function testGetScale($input, $expected){
        $d = new Decimal($input);
        $this->assertSame($expected, $d->getScale());
    }

    public function getScaleProvider(){
        return [
            [0, 0],
            [1, 0],
            [-1, 0],
            ['12.375', 3],
            ['-0.7', 1],
            ['6.22e23', 0],
            ['1e-10', 10],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::compare
     * @dataProvider compareProvider
     */
    public function testCompare($a, $b, $expected){
        $dec = new Decimal($a);
        $this->assertSame($expected, $dec->compare($b));
    }

    public function compareProvider(){
        return [
            [0, 0, 0],
            [1, 0, 1],
            [-1, 0, -1],
            ['12.375', '12.375', 0],
            ['12.374', '12.375', -1],
            ['12.376', '12.375', 1],
            ['6.22e23', '6.22e23', 0],
            ['1e-10', '1e-9', -1],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::equals
     * @covers \Direvus\Decimal\Decimal::eq
     * @dataProvider compareProvider
     */
    public function testEquals($a, $b, $expected){
        $dec = new Decimal($a);
        $this->assertSame($expected == 0, $dec->equals($b));
        $this->assertSame($expected == 0, $dec->eq($b));
    }

    /**
     * @covers \Direvus\Decimal\Decimal::greaterThan
     * @covers \Direvus\Decimal\Decimal::gt
     * @dataProvider compareProvider
     */
    public function testGreaterThan($a, $b, $expected){
        $dec = new Decimal($a);
        $this->assertSame($expected > 0, $dec->greaterThan($b));
        $this->assertSame($expected > 0, $dec->gt($b));
    }

    /**
     * @covers \Direvus\Decimal\Decimal::lessThan
     * @covers \Direvus\Decimal\Decimal::lt
     * @dataProvider compareProvider
     */
    public function testLessThan($a, $b, $expected){
        $dec = new Decimal($a);
        $this->assertSame($expected < 0, $dec->lessThan($b));
        $this->assertSame($expected < 0, $dec->lt($b));
    }

    /**
     * @covers \Direvus\Decimal\Decimal::ge
     * @dataProvider compareProvider
     */
    public function testGreaterEqual($a, $b, $expected){
        $dec = new Decimal($a);
        $this->assertSame($expected >= 0, $dec->ge($b));
    }

    /**
     * @covers \Direvus\Decimal\Decimal::le
     * @dataProvider compareProvider
     */
    public function testLessEqual($a, $b, $expected){
        $dec = new Decimal($a);
        $this->assertSame($expected <= 0, $dec->le($b));
    }

    /**
     * @covers \Direvus\Decimal\Decimal::zero
     * @dataProvider compareZeroProvider
     */
    public function testIsZero($input, $expected){
        $dec = new Decimal($input);
        $this->assertSame($expected == 0, $dec->isZero());
    }

    public function compareZeroProvider(){
        return [
            [0, 0],
            [1, 1],
            [-1, -1],
            [0.0, 0],
            ['0', 0],
            ['1', 1],
            ['-1', -1],
            ['00000', 0],
            ['  0.0   ', 0],
            ['0.00001', 1],
            ['1e-20', 1],
            ['-1e-20', -1],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::positive
     * @dataProvider compareZeroProvider
     */
    public function testPositive($input, $expected){
        $dec = new Decimal($input);
        $this->assertSame($expected > 0, $dec->positive());
    }

    /**
     * @covers \Direvus\Decimal\Decimal::negative
     * @dataProvider compareZeroProvider
     */
    public function testNegative($input, $expected){
        $dec = new Decimal($input);
        $this->assertSame($expected < 0, $dec->negative());
    }

    /**
     * @covers \Direvus\Decimal\Decimal::abs
     * @dataProvider absProvider
     */
    public function testAbs($input, $expected){
        $dec = new Decimal($input);
        $this->assertSame($expected, (string) $dec->abs());
    }

    public function absProvider(){
        return [
            [0, '0'],
            [1, '1'],
            [-1, '1'],
            ['12.375', '12.375'],
            ['-12.375', '12.375'],
            [-0.7, '0.7'],
            [0.7, '0.7'],
            ['6.22e23', '622000000000000000000000'],
            ['-6.22e23', '622000000000000000000000'],
            ['1e-10', '0.0000000001'],
            ['-1e-10', '0.0000000001'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::negation
     * @covers \Direvus\Decimal\Decimal::negate
     * @dataProvider negationProvider
     */
    public function testNegation($input, $expected){
        $dec = new Decimal($input);
        $negative = $dec->negative;
        $this->assertSame($expected, (string) $dec->negation());
        $dec->negate();
        $this->assertSame(!$negative, $dec->negative);
        $this->assertSame($expected, (string) $dec);
    }

    public function negationProvider(){
        return [
            [0, '0'],
            [1, '-1'],
            [-1, '1'],
            ['12.375', '-12.375'],
            ['-12.375', '12.375'],
            [-0.7, '0.7'],
            [0.7, '-0.7'],
            ['6.22e23', '-622000000000000000000000'],
            ['-6.22e23', '622000000000000000000000'],
            ['1e-10', '-0.0000000001'],
            ['-1e-10', '0.0000000001'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::add
     * @dataProvider additionProvider
     */
    public function testAdd($a, $b, $scale, $expected){
        $dec = new Decimal($a);
        $this->assertSame($expected, (string) $dec->add($b, $scale));
    }

    public function additionProvider(){
        return [
            ['0', '0', null, '0'],
            ['1', '10', null, '11'],
            ['1000', '10', null, '1010'],
            ['-10', '10', null, '0'],
            ['10', '-10', null, '0'],
            ['0.1', '1', null, '1.1'],
            ['0.1', '0.01', null, '0.11'],
            ['-0.001', '0.01', null, '0.009'],
            ['0', '0', 3, '0'],
            ['1000', '0.001', 3, '1000.001'],
            ['1000', '0.001', 0, '1000'],
            ['6.22e23', '-6.22e23', null, '0'],
            ['1e-10', '1e-10', null, '0.0000000002'],
            ['1e-10', '1e-10', 10, '0.0000000002'],
            ['1e-10', '1e-10', 9, '0'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::sub
     * @covers \Direvus\Decimal\Decimal::subtract
     * @dataProvider subtractionProvider
     */
    public function testSubtract($a, $b, $scale, $expected){
        $dec = new Decimal($a);
        $this->assertSame($expected, (string) $dec->sub($b, $scale));
        $this->assertSame($expected, (string) $dec->subtract($b, $scale));
    }

    public function subtractionProvider(){
        return [
            ['0', '0', null, '0'],
            ['1', '10', null, '-9'],
            ['1000', '10', null, '990'],
            ['-10', '10', null, '-20'],
            ['10', '-10', null, '20'],
            ['10', '10', null, '0'],
            ['0.1', '1', null, '-0.9'],
            ['0.1', '0.01', null, '0.09'],
            ['-0.001', '0.01', null, '-0.011'],
            ['0', '0', 3, '0'],
            ['1000', '0.001', 3, '999.999'],
            ['1000', '0.001', 0, '999'],
            ['6.22e23', '-6.22e23', null, '1244000000000000000000000'],
            ['6.22e23', '6.22e23', null, '0'],
            ['1e-10', '1e-10', null, '0'],
            ['1e-10', '-1e-10', 10, '0.0000000002'],
            ['-1e-10', '1e-10', 10, '-0.0000000002'],
            ['1e-10', '-1e-10', 9, '0'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::mul
     * @covers \Direvus\Decimal\Decimal::multiply
     * @dataProvider multiplicationProvider
     */
    public function testMultiply($a, $b, $scale, $expected){
        $dec = new Decimal($a);
        $this->assertSame($expected, (string) $dec->mul($b, $scale));
        $this->assertSame($expected, (string) $dec->multiply($b, $scale));
    }

    public function multiplicationProvider(){
        return [
            ['0', '0', null, '0'],
            ['1', '10', null, '10'],
            ['1000', '10', null, '10000'],
            ['-10', '10', null, '-100'],
            ['10', '-10', null, '-100'],
            ['10', '10', null, '100'],
            ['0.1', '1', null, '0.1'],
            ['0.1', '0.01', null, '0.001'],
            ['-0.001', '0.01', null, '-0.00001'],
            ['0', '0', 3, '0'],
            ['9', '0.001', 3, '0.009'],
            ['9', '0.001', 0, '0'],
            ['6.22e23', '2', null, '1244000000000000000000000'],
            ['6.22e23', '-1', null, '-622000000000000000000000'],
            ['1e-10', '28', null, '0.0000000028'],
            ['1e-10', '-1e-10', null, '-0.00000000000000000001'],
            ['1e-10', '-1e-10', 20, '-0.00000000000000000001'],
            ['1e-10', '-1e-10', 19, '0'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::div
     * @covers \Direvus\Decimal\Decimal::divide
     * @dataProvider divisionProvider
     */
    public function testDivide($a, $b, $scale, $expected){
        $dec = new Decimal($a);
        $this->assertSame($expected, (string) $dec->div($b, $scale));
        $this->assertSame($expected, (string) $dec->divide($b, $scale));
    }

    public function divisionProvider(){
        return [
            ['0', '1', null, '0'],
            ['1', '1', null, '1'],
            ['0', '1e6', null, '0'],
            [1, 10, 1, '0.1'],
            ['1000', '10', null, '100'],
            ['-10', '10', null, '-1'],
            ['10', '-10', null, '-1'],
            ['10', '10', null, '1'],
            ['0.1', '1', null, '0.1'],
            ['0.1', '0.01', null, '10'],
            ['-0.001', '0.01', 1, '-0.1'],
            ['1', '3', 3, '0.333'],
            ['1', '3', 0, '0'],
            ['6.22e23', '2', null, '311000000000000000000000'],
            ['6.22e23', '-1', null, '-622000000000000000000000'],
            ['1e-10', 3, null, '0'],
            ['1e-10', 3, 11, '0.00000000003'],
            ['1e-10', 3, 12, '0.000000000033'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::divide
     * @expectedException \DomainException
     */
    public function testDivideByZero(){
        $dec = new Decimal(1);
        $dec->divide(0);
    }

    /**
     * @covers \Direvus\Decimal\Decimal::inverse
     * @dataProvider inverseProvider
     */
    public function testInverse($input, $scale, $expected){
        $dec = new Decimal($input);
        $this->assertSame($expected, (string) $dec->inverse($scale));
    }

    public function inverseProvider(){
        return [
            [1, null, '1'],
            [-1, null, '-1'],
            [2, null, '0.5'],
            [1000, null, '0.001'],
            ['-10', null, '-0.1'],
            ['10', null, '0.1'],
            ['10', 0, '0'],
            ['0.1', null, '10'],
            ['-0.001', null, '-1000'],
            [3, 3, '0.333'],
            [3, 0, '0'],
            [6, 4, '0.1666'],
            [6, 3, '0.166'],
            [6, 2, '0.16'],
            [6, 1, '0.1'],
            ['6.22e23', 30, '0.000000000000000000000001607717'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::inverse
     * @expectedException \DomainException
     */
    public function testInvertZero(){
        $dec = new Decimal(0);
        $dec->inverse();
    }


    /**
     * @covers \Direvus\Decimal\Decimal::increase
     * @dataProvider increaseProvider
     */
    public function testIncrease($input, $args, $expected){
        $dec = new Decimal($input);
        call_user_func_array([$dec, 'increase'], $args);
        $this->assertSame($expected, (string) $dec);
    }

    public function increaseProvider(){
        return [
            [0, [], '0'],
            [1, [], '1'],
            [1, [0], '1'],
            [1, [0, 0, 0], '1'],
            [1, [1, 0, 1], '3'],
            [1, [[1, 0, 3]], '5'],
            [1, [[1, 0, 3], '0.1'], '5.1'],
            [1, [[1, 0, 3], ['0.1', '0.01']], '5.11'],
            [1, [-1], '0'],
            [0, ['0.1', '0.1', '0.1', '-0.3'], '0'],
            ['6.22e23', [1], '622000000000000000000001'],
            ['6.22e23', [-1], '621999999999999999999999'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::decrease
     * @dataProvider decreaseProvider
     */
    public function testDecrease($input, $args, $expected){
        $dec = new Decimal($input);
        call_user_func_array([$dec, 'decrease'], $args);
        $this->assertSame($expected, (string) $dec);
    }

    public function decreaseProvider(){
        return [
            [0, [], '0'],
            [1, [], '1'],
            [1, [0], '1'],
            [1, [0, 0, 0], '1'],
            [1, [1, 0, 1], '-1'],
            [1, [[1, 0, 3]], '-3'],
            [1, [[1, 0, 3], '0.1'], '-3.1'],
            [1, [[1, 0, 3], ['0.1', '0.01']], '-3.11'],
            [1, [-1], '2'],
            [0, ['0.1', '0.1', '0.1', '-0.3'], '0'],
            ['6.22e23', [1], '621999999999999999999999'],
            ['6.22e23', [-1], '622000000000000000000001'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::compress
     * @dataProvider compressProvider
     */
    public function testCompress($input, $expected){
        $d = new Decimal;
        list($d->digits, $d->exponent, $d->negative) = $input;

        $d = $d->compress();
        $this->assertSame($expected, [$d->digits, $d->exponent, $d->negative]);
    }

    public function compressProvider(){
        return [
            [['0', 0, false], ['0', 0, false]],
            [['0', 1, false], ['0', 0, false]],
            [['0', 0, true],  ['0', 0, false]],
            [['00000000', 0, false], ['0', 0, false]],
            [['75', 2, false], ['75', 2, false]],
            [['750', 1, false], ['75', 2, false]],
            [['7500', 0, false], ['75', 2, false]],
            [['75000', -1, false], ['75', 2, false]],
            [['001', -8, true], ['1', -10, true]],
            [['01', -9, true], ['1', -10, true]],
            [['1', -10, true], ['1', -10, true]],
            [['10', -11, true], ['1', -10, true]],
            [['100', -12, true], ['1', -10, true]],
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
     * @covers \Direvus\Decimal\Decimal::toFloat
     * @dataProvider toFloatProvider
     */
    public function testToFloat($input, $expected){
        $d = new Decimal($input);
        $result = $d->toFloat();
        $this->assertInternalType('float', $result);
        $this->assertEquals($expected, $result);
    }

    public function toFloatProvider(){
        return [
            [0, 0.0],
            [1, 1.0],
            [-1, -1.0],
            ['12.375', 12.375],
            [-0.7, -0.7],
            [0.7, 0.7],
            ['6.22e23', 622000000000000000000000.0],
            ['-6.22e23', -622000000000000000000000.0],
            ['1e-10', 0.0000000001],
            ['-1e-10', -0.0000000001],
            ['1e100', 10.0 ** 100],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::format
     * @dataProvider formatProvider
     */
    public function testFormat($input, $format_args, $expected){
        $d = new Decimal($input);
        $result = call_user_func_array([$d, 'format'], $format_args);
        $this->assertSame($expected, $result);
    }

    public function formatProvider(){
        return [
            [0, [], '0'],
            [1, [], '1'],
            [-1, [], '-1'],
            ['12.375', [], '12.375'],
            ['12.375', [4], '12.3750'],
            ['12.375', [3], '12.375'],
            ['12.375', [2], '12.38'],
            ['12.375', [1], '12.4'],
            ['12.375', [0], '12'],
            [-0.7, [null], '-0.7'],
            [0.7, [0], '1'],
            ['6.22e23', [null], '622000000000000000000000'],
            ['6.22e23', [null, ','], '622,000,000,000,000,000,000,000'],
            ['6.22e23', [null, ' '], '622 000 000 000 000 000 000 000'],
            ['6.22e23', [2, ','], '622,000,000,000,000,000,000,000.00'],
            ['6.22e23', [2, '.', ','], '622.000.000.000.000.000.000.000,00'],
            ['-6.22e23', [null, ','], '-622,000,000,000,000,000,000,000'],
            ['1e-10', [], '0.0000000001'],
            ['-1e-10', [], '-0.0000000001'],
            ['-1e-10', [9], '-0.000000000'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::make
     * @dataProvider baseProvider
     */
    public function testMake($input, $expected){
        $d = Decimal::make($input);
        $this->assertInstanceOf('\\Direvus\\Decimal\\Decimal', $d);
        $this->assertSame($expected, (string) $d);
    }

    /**
     * @covers \Direvus\Decimal\Decimal::cleanValue
     * @dataProvider cleanProvider
     */
    public function testCleanValue($input, $expected){
        $this->assertSame($expected, Decimal::cleanValue($input));
    }

    public function cleanProvider(){
        return [
            ['0', '0'],
            [0, '0'],
            [0.2, '0.2'],
            [.2, '0.2'],
            ['.2', '0.2'],
            [-0.1, '-0.1'],
            ['6.22e23', '6.22e23'],
            ['6.22 E 23', '6.22E23'],
            [' 1 ', '1'],
            ['=1', '1'],
            ['abc1', '1'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::cleanValue
     * @expectedException \DomainException
     */
    public function testCleanValueException(){
        Decimal::cleanValue('not a number');
    }

    /**
     * @covers \Direvus\Decimal\Decimal::zeroes
     * @dataProvider zeroesProvider
     */
    public function testZeroes($length, $expected){
        $zeroes = Decimal::zeroes($length);
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
     * @covers \Direvus\Decimal\Decimal::scaleValid
     * @dataProvider scaleValidProvider
     */
    public function testScaleValid($scale, $expected){
        $valid = Decimal::scaleValid($scale);
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
     * @covers \Direvus\Decimal\Decimal::min
     * @dataProvider minProvider
     */
    public function testMin($args, $expected){
        $func = ['\\Direvus\\Decimal\\Decimal', 'min'];
        $result = call_user_func_array($func, $args);
        $this->assertSame($expected, (string) $result);
    }

    public function minProvider(){
        return [
            [[], ''],
            [[1], '1'],
            [[0, 1, 2], '0'],
            [[2, 1, 0], '0'],
            [[1, 0, 2], '0'],
            [[-5.0, -7.3, -25], '-25'],
            [[-100, '-1e5', -25], '-100000'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::max
     * @dataProvider maxProvider
     */
    public function testMax($args, $expected){
        $func = ['\\Direvus\\Decimal\\Decimal', 'max'];
        $result = call_user_func_array($func, $args);
        $this->assertSame($expected, (string) $result);
    }

    public function maxProvider(){
        return [
            [[], ''],
            [[1], '1'],
            [[0, 1, 2], '2'],
            [[2, 1, 0], '2'],
            [[1, 0, 2], '2'],
            [[-5.0, -7.3, -25], '-5'],
            [[100, '1e5', 25], '100000'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\Decimal::zero
     */
    public function testZeroFunction(){
        $d = Decimal::zero();
        $this->assertInstanceOf('\\Direvus\\Decimal\\Decimal', $d);
        $this->assertSame('0', (string) $d);
    }

    /**
     * @covers \Direvus\Decimal\Decimal::one
     */
    public function testOne(){
        $d = Decimal::one();
        $this->assertInstanceOf('\\Direvus\\Decimal\\Decimal', $d);
        $this->assertSame('1', (string) $d);
    }

    /**
     * @covers \Direvus\Decimal\Decimal::resultScale
     * @dataProvider resultScaleProvider
     */
    public function testResultScale($a, $b, $scale, $expected){
        $this->assertSame($expected, Decimal::resultScale(
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
