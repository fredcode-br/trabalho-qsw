<?php

use Database\Connection;

class Enrollment
{
    private $id;
    private $userId;
    private $classId;
    private $enrollmentDate;

    public function __construct($id = null, $userId = null, $classId = null, $enrollmentDate = null)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->classId = $classId;
        $this->enrollmentDate = $enrollmentDate;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getClassId()
    {
        return $this->classId;
    }

    public function setClassId($classId)
    {
        $this->classId = $classId;
    }

    public function getEnrollmentDate()
    {
        return $this->enrollmentDate;
    }

    public function setEnrollmentDate($enrollmentDate)
    {
        $this->enrollmentDate = $enrollmentDate;
    }

    public function getEnrollments()
    {
        try {
            $conn = Connection::getConn();

            $sql = 'SELECT * FROM inscricoes';
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $enrollments;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter inscrições: ' . $e->getMessage());
        }
    }
}
