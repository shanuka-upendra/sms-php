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

    public function getAverage()
    {
        if (empty($this->scores)) return 0;
        return round(
            array_sum($this->scores) / count($this->scores),
            2
        );
    }

    public function getGrade()
    {
        $avg = $this->getAverage();
        if ($avg >= 90) return "A";
        elseif ($avg >= 80) return "B";
        elseif ($avg >= 70) return "C";
        elseif ($avg >= 60) return "D";
        else                return "F";
    }

    public function isPassing()
    {
        return $this->getAverage() >= 60;
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
                echo "Error: Email already exists!" . PHP_EOL;
                return false;
            }
        }

        $student = new Student(
            $this->nextId++,
            $name,
            $email,
            $age
        );

        $this->students[] = $student;
        echo "Student added: " . $name . " (ID: " . $student->id . ")" . PHP_EOL;
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
            echo "Error: Student not found!" . PHP_EOL;
            return false;
        }

        $student->name  = $name;
        $student->email = $email;
        $student->age   = $age;

        echo "Student updated: " . $name . PHP_EOL;
        return true;
    }

    public function deleteStudent($id)
    {
        foreach ($this->students as $index => $student) {
            if ($student->id === $id) {
                $name = $student->name;
                array_splice($this->students, $index, 1);
                echo "Student deleted: " . $name . PHP_EOL;
                return true;
            }
        }
        echo "Error: Student not found!" . PHP_EOL;
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
    echo PHP_EOL . "========================================" . PHP_EOL;
    if ($title) echo "  " . $title . PHP_EOL;
    echo "========================================" . PHP_EOL;
}

function printStudent($student)
{
    echo "ID:      " . $student->id              . PHP_EOL;
    echo "Name:    " . $student->name            . PHP_EOL;
    echo "Email:   " . $student->email           . PHP_EOL;
    echo "Age:     " . $student->age             . PHP_EOL;

    $scores = $student->getScores();
    if (!empty($scores)) {
        echo "Scores:" . PHP_EOL;
        foreach ($scores as $subject => $score) {
            echo "  - " . $subject . ": " . $score . PHP_EOL;
        }
        echo "Average: " . $student->getAverage() . PHP_EOL;
        echo "Grade:   " . $student->getGrade()   . PHP_EOL;
        echo "Status:  " . ($student->isPassing() ? "Passing" : "Failing") . PHP_EOL;
    }
    echo "----------------------------------------" . PHP_EOL;
}

function printAllStudents($manager)
{
    $students = $manager->getAllStudents();

    if (empty($students)) {
        echo "No students found!" . PHP_EOL;
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
        echo "No data available!" . PHP_EOL;
        return;
    }

    echo "Total Students: " . $stats["total"]    . PHP_EOL;
    echo "Passing:        " . $stats["passing"]  . PHP_EOL;
    echo "Failing:        " . $stats["failing"]  . PHP_EOL;
    echo "Class Average:  " . $stats["classAvg"] . PHP_EOL;
}


//=====Main Program=====
$manager = new StudentManager();

// ── CREATE Students ─────────────────────────
printDivider("ADDING STUDENTS");

$s1 = $manager->addStudent("Alice Johnson", "alice@email.com", 20);
$s2 = $manager->addStudent("Bob Smith",     "bob@email.com",   22);
$s3 = $manager->addStudent("Maria Garcia",  "maria@email.com", 21);
$s4 = $manager->addStudent("David Lee",     "david@email.com", 23);
$s5 = $manager->addStudent("Sarah Wilson",  "sarah@email.com", 20);

// Try adding duplicate email
$manager->addStudent("Fake Alice", "alice@email.com", 25);

// ── Add Scores ──────────────────────────────
printDivider("ADDING SCORES");

$s1->addScore("PHP",     92);
$s1->addScore("Laravel", 88);
$s1->addScore("MySQL",   95);

$s2->addScore("PHP",     65);
$s2->addScore("Laravel", 55);
$s2->addScore("MySQL",   70);

$s3->addScore("PHP",     85);
$s3->addScore("Laravel", 90);
$s3->addScore("MySQL",   88);

$s4->addScore("PHP",     45);
$s4->addScore("Laravel", 50);
$s4->addScore("MySQL",   55);

$s5->addScore("PHP",     78);
$s5->addScore("Laravel", 82);
$s5->addScore("MySQL",   80);

echo "Scores added for all students!" . PHP_EOL;

// ── READ All Students ───────────────────────
printDivider("ALL STUDENTS");
printAllStudents($manager);

// ── READ — Find by ID ───────────────────────
printDivider("FIND STUDENT BY ID (ID: 3)");
$found = $manager->findById(3);
if ($found) {
    printStudent($found);
} else {
    echo "Student not found!" . PHP_EOL;
}

// ── READ — Search by Name ───────────────────
printDivider("SEARCH BY NAME: 'son'");
$results = $manager->searchByName("son");
if (empty($results)) {
    echo "No students found!" . PHP_EOL;
} else {
    foreach ($results as $student) {
        printStudent($student);
    }
}

// ── UPDATE ──────────────────────────────────
printDivider("UPDATE STUDENT (ID: 2)");
$manager->updateStudent(2, "Robert Smith", "robert@email.com", 23);
$updated = $manager->findById(2);
printStudent($updated);

// ── DELETE ──────────────────────────────────
printDivider("DELETE STUDENT (ID: 4)");
$manager->deleteStudent(4);
$manager->deleteStudent(99);  // Try deleting non-existent

// ── FINAL LIST ──────────────────────────────
printDivider("FINAL STUDENT LIST");
printAllStudents($manager);

// ── STATISTICS ──────────────────────────────
printDivider("CLASS STATISTICS");
printStats($manager);

printDivider("PROGRAM COMPLETE");
?>
