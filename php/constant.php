<?php
class message {
    const LEAVING_MESSAGE = "Bye, Allah Hafez";
    const MORNING_MESSAGE = "Assalamuolikum, Good Morning";
    const MISSED_MESSAGE = " I miss you";
    public function intro()
    {
        echo self::MORNING_MESSAGE;
    }
    public function feeling($name)
    {
        echo self::MISSED_MESSAGE. " ".$name;
    }
    public function bye() {
        echo self::LEAVING_MESSAGE;
    }
}
$goodbye = new message();
$goodbye->intro();
echo "\n";
$goodbye->feeling("jalal");
echo  "\n";
$goodbye->bye();
