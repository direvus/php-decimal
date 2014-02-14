php-decimal
===========

An arbitrary-precision decimal library for PHP.

Background
----------

The PHP language only offers two numeric data types: int and float.  Neither of
these types are suitable for a wide range of real-world problems, where exact
arithmetic with values of arbitrary precision are required---notably, when
working with monetary values.

PHP's optional extension 'BCMath' provides some limited features in this area,
but it is awkward to use with arbitrary precision, and it does not support
rounding.

This library uses the BCMath functions internally, but hides them behind a more
convenient, object-oriented, and intuitive API, inspired by Python's standard
library module 'decimal'.

Installation
------------

Copy the library file 'decimal.php' to anywhere on your system you like.
Personally I recommend something like `/usr/local/lib/php-decimal`, but it's
totally up to you.

In your PHP code, execute `include '/path/to/lib/decimal.php';` and you're
ready to use the library.

Usage
-----

One classic example of how binary floating-point values can ruin your day is
the expression (0.1 + 0.1 + 0.1 - 0.3), which is of course zero.  Let's use
this as a worked example.  First, using PHP's built-in float data type:

    php > $n = 0.1 + 0.1 + 0.1 - 0.3;
    php > var_dump($n);
    float(5.5511151231258E-17)
    
    php > var_dump($n == 0);
    bool(false)

Unless you want to do all your comparisons using an epsilon value, binary
floating-point is exactly this kind of trouble waiting to happen.  The next
most obvious place to turn is the BCMath extension, which would look like:

    php > bcscale(1);
    php > $n = bcsub(bcadd(bcadd('0.1', '0.1'), '0.1'), '0.3');
    php > var_dump($n);
    string(3) "0.0"
    
    php > var_dump(bccomp($n, 0) == 0);
    bool(true)

Now we're getting the right answer, but it's an unreadable mess, and it gets
worse if you don't know ahead of time what the precision of your inputs is
going to be, and therefore can't choose a global value for bcscale that will be
appropriate for all circumstances.

Enter php-decimal:

    php > include '/path/to/lib/decimal.php';
    
    php > $n = new Decimal\Decimal;
    php > $n->increase('0.1', '0.1', '0.1', '-0.3');
    php > var_dump((string) $n);
    string(1) "0"
    
    php > var_dump($n->zero());
    bool(true)

We didn't have to lock php-decimal into any particular precision for its
computations; it noticed that all its operands had a scale of 1, so it told
BCMath to performs its operations at scale 1.  If any of the operands had had a
greater scale, then we would have used that scale instead.

Unfortunately, as PHP does not offer any way to hook into its built-in
comparison and arithmetic operators, it isn't possible to use natural syntax
like $c = $a + $b, where $a and $b are Decimal objects.  We have to make do
with relatively ugly method calls.

Internals
---------

Each instance of the Decimal class consists of:

  * a sequence of decimal digits stored as a PHP string,
  * an integer exponent, and
  * a boolean indicating whether the number is negative.

Each Decimal value represents the real number *n* such that:

    n = [-]digits * (10 ^ exponent)

The number 7500 would be therefore represented internally by the values
('75', '2', false), respectively, as 7500 = 75 x 10^2.

It can be seen that, for every real number which has a finite representation in
decimal form, there are infinitely many possible Decimal representations, but
only one representation which uses the minimal number of decimal digits, which
is called the 'normal' or 'canonical' representation.

Decimals can be initialised from other Decimal instances, strings describing
numbers, integers and floats (with the caveat that if you initialise from a
float you might end up exposing yourself to precisely the kind of mischief this
library was created to avoid).

In terms of performance, this library is probably pretty awful and should
really only be used as a last resort.  If at all possible, perform all
computations in your database or in some other programming environment, and
leave PHP blissfully agnostic of difficult problems like basic arithmetic.

Standards
---------

This library has some superficial resemblances to the IEEE 854 and 754
standards, but does not comply, nor does it attempt to comply, with these
standards.  It would be possible to extend the library into compliance, but at
this time the author has no intention of doing so.

In particular, php-decimal does not attempt to represent abstract numeric
concepts like 'Not a Number', infinity or negative infinity.

License
-------

php-decimal is released under the "BSD 2-clause license", the full text of
which can be found in the LICENSE file at the top level of the repository.

Acknowledgements
----------------

This library was heavily inspired by the [decimal][1] module of Python's standard
library, and the [numeric data type][2] of PostgreSQL.

  [1]: http://docs.python.org/2/library/decimal.html
  [2]: http://www.postgresql.org/docs/current/static/datatype-numeric.html#DATATYPE-NUMERIC-DECIMAL

