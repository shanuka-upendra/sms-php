<?php

//=====Student Class=====
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

    public function isPassing()
    {
        return $this->getAvarage() >= 60;
    }
}

class StudentManager
{
    private $students = [];
    private $nextId = 1;

    public function addStudent($name, $email, $age)
    {
        foreach ($this->students as $student) {
            if ($student->email === $email) {
                echo "Error: Email Already exists! " . "<br>";
            }
        }

        $student = new Student(
            $this->nextId++,
            $name,
            $email,
            $age
        );

        $this->students[] = $student;
        echo "Student added: " . $name . "(ID: " . $student->id . ")" . "<br>";
        return $student;
    }

    public function getAllStudents()
    {
        return $this->students;
    }

    public function findById($id)
    {
        foreach ($this->students as $student) {
            if ($student->id === $id) {
                return $student;
            }
        }
        return null;    // Not found
    }

    public function searchByName($keyword)
    {
        $results = [];
        foreach ($this->students as $student) {
            if (stripos($student->name, $keyword) !== false) {
                $results[] = $student;
            }
        }
        return $results;
    }

    public function updateStudent($id, $name, $email, $age) {
        $student = $this->findById($id);

        if ($student === null) {
            echo "❌ Error: Student not found!" . "<br>";
            return false;
        }

        $student->name  = $name;
        $student->email = $email;
        $student->age   = $age;

        echo "✅ Student updated: " . $name . "<br>";
        return true;
    }
}
