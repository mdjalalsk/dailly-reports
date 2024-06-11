<?php
# three type comment in php
// This is a single-line comment

# This is also a single-line comment

/* This is a
multi-line comment */
/*Rules for PHP variables:
    A variable starts with the $ sign, followed by the name of the variable
A variable name must start with a letter or the underscore character
A variable name cannot start with a number
A variable name can only contain alpha-numeric characters and underscores (A-z, 0-9, and _ )
Variable names are case-sensitive ($age and $AGE are two different variables)
*/

$name="jalal";
$_age=45;
//$5_jj=55;
//Jakhon kno varibale declear korar somoy kno number first a debo takon syntax error debe
//syntax error, unexpected '5'
$AGE=25;
$age=30;
#php variable is case-sensitive $age and $AGE 2 ta alada variable.
//var_dump($AGE);

//PHP echo and print Statements
/*The differences are small: echo has no♂ return value while print has a return value of 1 so it can be used in expressions. echo can take multiple parameters (although such usage is rare) while print can take one argument. echo is marginally faster than print.*/

#PHP supports the following data types:

//String
//Integer
//Float (floating point numbers - also called double)
//Boolean
//Array
//Object
//NULL
//Resource

$name="jalal";

$num=55;
$float=10.04;
$bool=true;
$array=["apple","banana","pear"];
class fruits
{
    public $name;
    public $color;
    public function __construct($name,$color){
        $this->name=$name;
        $this->color=$color;

    }
    public function intro()
    {
      echo "This fruit is " . $this->name . "color is " . $this->color."\n";
        echo "This fruit is {$this->name} and the color is {$this->color}.";

    }

}

$apple=new fruits("apple","red");
//echo $apple->intro();

//String
$paragraph="This is an paragraph ";
//sting length janr jonno.
//echo strlen($paragraph);
// word count korar jonno
//echo str_word_count($paragraph);
// string search korar jonno
//echo strpos($paragraph, "is");
// sting upper case korar jonno
//echo strtoupper($paragraph);
// string lower case korar jonno
//echo strtolower($paragraph);
//string replace

//echo str_replace("an", "a", $paragraph);
// string reverse korar jonno
//echo strrev($paragraph);

//remove whitespace
//echo trim($paragraph);
// string separator
 $result=explode(" ", $paragraph);
//print_r($result);

//math function
$num=[5,8,6,5];
//maximum vlau in array
//echo max($num);
//manimum vlau in array
//echo min($num);
$n=-3.8;
echo abs($n);
echo("\n");
echo round($n);
echo("\n");
echo rand(1,10);

/*
 * const vs. define()

const are always case-sensitive
define() has has a case-insensitive option.
const cannot be created inside another block scope, like inside a function or inside an if statement.
define can be created inside another block scope.
 */
//TODO: start if esle in h3shool