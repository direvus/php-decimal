<?php
include 'decimal.php';
use Decimal\Decimal as D;

class DecimalTest extends PHPUnit_Framework_TestCase {
    private $values = array(
        50,
        -25000,
        0.00001,
        '-5.000067',
        '    abc01    ',
        '0000005',
        '6.22e23');

    /**
     * @covers Decimal\Decimal::__construct
     */
    public function testValidConstructor(){
        foreach($this->values as $value){
            $d = new D($value);
            $this->assertInstanceOf('Decimal\\Decimal', $d);
        }
    }

    /**
     * @expectedException DomainException
     */
    public function testInvalidConstructor(){
        $d = new D('');
    }

    /**
     * @covers Decimal\Decimal::__construct
     * @covers Decimal\Decimal->__toString
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
            $d = new D($value);
            $results[] = (string) $d;
        }
        $this->assertEquals($results, $expect);
    }
}
