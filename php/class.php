<?php

class fruits
{
    public $name;
    public $color;
    public function __construct($name, $color){
        $this->name = $name;
        $this->color = $color;
    }
    public function intro()
    {
        echo "The fruit is {$this->name} and the color is {$this->color}\n";
    }
}

class mango extends fruits{
public function message()
{
    echo "This is a {$this->name}";
}

}
 $result =new mango("mango","red");
$result->intro();
$result->message();
