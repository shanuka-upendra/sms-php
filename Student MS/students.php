<?php

class student
{
    public $id;
    public $name;
    public $email;
    public $age;
    private $scores = [];

    public function __construct($id, $name, $email, $age)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->age = $age;
    }

    public function addScore($subject, $score)
    {
        $this->scores[$subject] = $score;
    }

    public function getScores()
    {
        return $this->scores;
    }

    public function getAvarage()
    {
        if (empty($this->scores)) return 0;
        return round(
            array_sum($this->scores) / count($this->scores),
            2
        );
    }

    public function getGrade()
    {
        $avg = $this->getAvarage();
        if ($avg >= 90) return "A";
        elseif ($avg >= 80) return "B";
        elseif ($avg >= 70) return "C";
        elseif ($avg >= 60) return "D";
        else                return "F";
    }

    public function isPassing() {
        return $this->getAverage() >= 60;
    }
}
