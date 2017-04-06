<?php
require_once('decimal.php');
use \Direvus\Decimal\Decimal;

/**
 * @covers \Direvus\Decimal\Formatter
 * @covers \Direvus\Decimal\MoneyFormatter
 * @covers \Direvus\Decimal\GroupedMoneyFormatter
 */
class FormatterTest extends PHPUnit\Framework\TestCase {
    /**
     * @covers \Direvus\Decimal\Formatter::__construct
     * @covers \Direvus\Decimal\Formatter::format
     * @dataProvider formatProvider
     */
    public function testFormatter($value, $args, $expected){
        $reflect = new \ReflectionClass('\\Direvus\\Decimal\\Formatter');
        $formatter = $reflect->newInstanceArgs($args);
        $result = $formatter->format($value);
        $this->assertSame($expected, $result);
    }

    public function formatProvider(){
        return [
            [0, [], '0'],
            [1, [], '1'],
            [-1, [], '-1'],
            ['', [], '0'],
            [null, [], '0'],
            [new Decimal, [], '0'],
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
     * @covers \Direvus\Decimal\MoneyFormatter::__construct
     * @covers \Direvus\Decimal\MoneyFormatter::format
     * @dataProvider formatMoneyProvider
     */
    public function testMoneyFormatter($value, $args, $expected){
        $reflect = new \ReflectionClass('\\Direvus\\Decimal\\MoneyFormatter');
        $formatter = $reflect->newInstanceArgs($args);
        $result = $formatter->format($value);
        $this->assertSame($expected, $result);
    }

    public function formatMoneyProvider(){
        return [
            [0, [], '0.00'],
            [1, [], '1.00'],
            [-1, [], '-1.00'],
            ['12.375', [], '12.38'],
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
            ['1e-10', [], '0.00'],
            ['-1e-10', [], '-0.00'],
            ['-1e-10', [9], '-0.000000000'],
            ];
    }

    /**
     * @covers \Direvus\Decimal\GroupedMoneyFormatter::__construct
     * @covers \Direvus\Decimal\GroupedMoneyFormatter::format
     * @dataProvider formatGroupedMoneyProvider
     */
    public function testGroupedMoneyFormatter($value, $args, $expected){
        $reflect = new \ReflectionClass('\\Direvus\\Decimal\\GroupedMoneyFormatter');
        $formatter = $reflect->newInstanceArgs($args);
        $result = $formatter->format($value);
        $this->assertSame($expected, $result);
    }

    public function formatGroupedMoneyProvider(){
        return [
            [0, [], '0.00'],
            [1, [], '1.00'],
            [-1, [], '-1.00'],
            ['12.375', [], '12.38'],
            ['12.375', [4], '12.3750'],
            ['12.375', [3], '12.375'],
            ['12.375', [2], '12.38'],
            ['12.375', [1], '12.4'],
            ['12.375', [0], '12'],
            [-0.7, [null], '-0.7'],
            [0.7, [0], '1'],
            ['6.22e23', [], '622,000,000,000,000,000,000,000.00'],
            ['6.22e23', [null], '622,000,000,000,000,000,000,000'],
            ['6.22e23', [null, ','], '622,000,000,000,000,000,000,000'],
            ['6.22e23', [null, ' '], '622 000 000 000 000 000 000 000'],
            ['6.22e23', [2, ','], '622,000,000,000,000,000,000,000.00'],
            ['6.22e23', [2, '.', ','], '622.000.000.000.000.000.000.000,00'],
            ['-6.22e23', [null, ','], '-622,000,000,000,000,000,000,000'],
            ['1e-10', [], '0.00'],
            ['-1e-10', [], '-0.00'],
            ['-1e-10', [9], '-0.000000000'],
            ];
    }
}
