<?php
class Fruit {
    public $name;
    public $color;
    public $weight;

    function set_name($n) {
        $this->name = $n;
    }
    protected function set_color($n) {
        $this->color = $n;
    }
    private function set_weight($n) { // a private function
        $this->weight = $n;
    }
}

$mango = new Fruit();
$mango->set_name('Mango'); // OK
//$mango->set_color('Yellow'); // ERROR
//$mango->set_weight('300'); // ERROR
