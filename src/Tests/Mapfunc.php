<?php

/**
 * 
 */
class People 
{
    public $name;
    public $age;
    private $map;

    function __construct($name, $age, $map)
    {
        $this->name = $name;
        $this->age = $age;
        $this->map = $map;
    }

    function PrintCourse(){
        $this->{$this->map["send"]}(["Macarrao"]);
    }
}

/**
 * 
 */
class Student extends People
{
    public $course;

    function __construct($name, $age, $course)
    {
        $map = [
         "send" => "sendCourse"
        ];
        parent::__construct($name, $age, $map);
        $this->course = $course;
    }

    function sendCourse($args){
        print_r($args);
        echo $this->course . "\n";
    }
}

$example = new Student("Lucas", "18", "BCC");
$example->PrintCourse();
$nome = "Lucas";
$idade = "Loezer";
$seila = "";

$aux = [$nome, $idade, $seila];
print_r($aux)


?>