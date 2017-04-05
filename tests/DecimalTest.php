<?php
namespace Direvus\Decimal;

include 'decimal.php';

class DecimalTest extends \PHPUnit\Framework\TestCase {
    private $values = array(
        50,
        -25000,
        0.00001,
        '-5.000067',
        '    abc01    ',
        '0000005',
        '6.22e23');

    /**
     * @covers Decimal::__construct
     */
    public function testValidConstructor(){
        foreach($this->values as $value){
            $d = new Decimal($value);
            $this->assertInstanceOf('\\Direvus\\Decimal\\Decimal', $d);
        }
    }

    /**
     * @expectedException DomainException
     */
    public function testInvalidConstructor(){
        $d = new Decimal('');
    }

    /**
     * @covers Decimal::__construct
     * @covers Decimal::__toString
     */
    public function testStringOutput(){
        $expect = array(
            '50',
            '-25000',
            '1.0E-5',
            '-5.000067',
            '1',
            '5',
            '622000000000000000000000');
        $results = array();
        foreach($this->values as $value){
            $d = new Decimal($value);
            $results[] = (string) $d;
        }
        $this->assertEquals($results, $expect);
    }

    /**
     * @covers Decimal::quantize
     */
    public function testQuantize(){
        $d = new Decimal('12.375');
        $this->assertEquals((string) $d->quantize(3),  '0');
        $this->assertEquals((string) $d->quantize(2),  '0');
        $this->assertEquals((string) $d->quantize(1),  '10');
        $this->assertEquals((string) $d->quantize(0),  '12');
        $this->assertEquals((string) $d->quantize(-1), '12.4');
        $this->assertEquals((string) $d->quantize(-2), '12.38');
        $this->assertEquals((string) $d->quantize(-3), '12.375');
        $this->assertEquals((string) $d->quantize(-4), '12.375');
    }

    /**
     * @covers Decimal::round
     */
    public function testRound(){
        $d = new Decimal('12.375');
        $this->assertEquals((string) $d->round(-1), '12');
        $this->assertEquals((string) $d->round(0),  '12');
        $this->assertEquals((string) $d->round(1),  '12.4');
        $this->assertEquals((string) $d->round(2),  '12.38');
        $this->assertEquals((string) $d->round(3),  '12.375');
        $this->assertEquals((string) $d->round(4),  '12.375');
    }
}
