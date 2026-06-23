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


//=====Student Manager Class=====
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

    public function updateStudent($id, $name, $email, $age)
    {
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

    public function deleteStudent($id)
    {
        foreach ($this->students as $index => $student) {
            if ($student->id === $id) {
                $name = $student->name;
                array_splice($this->students, $index, 1);
                echo "✅ Student deleted: " . $name . "<br>";
                return true;
            }
        }
        echo "❌ Error: Student not found!" . "<br>";
        return false;
    }

    public function getStats()
    {
        $total    = count($this->students);
        if ($total === 0) return null;

        $passing  = 0;
        $failing  = 0;
        $avgTotal = 0;

        foreach ($this->students as $student) {
            if ($student->isPassing()) $passing++;
            else $failing++;
            $avgTotal += $student->getAverage();
        }

        return [
            "total"      => $total,
            "passing"    => $passing,
            "failing"    => $failing,
            "classAvg"   => round($avgTotal / $total, 2)
        ];
    }
}

//=====Display Functions=====
function printDivider($title = "")
{
    echo "<br>========================================" . "<br>";
    if ($title) echo "  " . $title . "<br>";
    echo "========================================" . "<br>";
}

function printStudent($student)
{
    echo "ID:      " . $student->id              . "<br>";
    echo "Name:    " . $student->name            . "<br>";
    echo "Email:   " . $student->email           . "<br>";
    echo "Age:     " . $student->age             . "<br>";

    $scores = $student->getScores();
    if (!empty($scores)) {
        echo "Scores:" . "<br>";
        foreach ($scores as $subject => $score) {
            echo "  → " . $subject . ": " . $score . "<br>";
        }
        echo "Average: " . $student->getAverage() . "<br>";
        echo "Grade:   " . $student->getGrade()   . "<br>";
        echo "Status:  " . ($student->isPassing() ? "✅ Passing" : "❌ Failing") . "<br>";
    }
    echo "---" . "<br>";
}

function printAllStudents($manager)
{
    $students = $manager->getAllStudents();

    if (empty($students)) {
        echo "No students found!" . "<br>";
        return;
    }

    foreach ($students as $student) {
        printStudent($student);
    }
}

function printStats($manager)
{
    $stats = $manager->getStats();

    if ($stats === null) {
        echo "No data available!" . "<br>";
        return;
    }

    echo "Total Students: " . $stats["total"]    . "<br>";
    echo "Passing:        " . $stats["passing"]  . "<br>";
    echo "Failing:        " . $stats["failing"]  . "<br>";
    echo "Class Average:  " . $stats["classAvg"] . "<br>";
}




?>
