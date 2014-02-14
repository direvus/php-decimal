#!/usr/bin/php
<?php
include 'decimal.php';

use Decimal\Decimal as D;

$d = new D;
print "Initial default:\n";
print_r($d);

$values = array(
    50,
    -25000,
    0.00001,
    '-5.000067',
    '    abc01    ',
    '0000005');

foreach($values as $value){
    print $value . "\n";
    $d = new D($value);
    print_r($d);
    print (string) $d . "\n";
    print_r($d->quantize(-2));
    print $d->quantize(-2)->format(2, ',');
    print "\n\n";
}
?>
