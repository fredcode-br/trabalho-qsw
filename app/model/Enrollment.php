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

    public static function getEnrollmentsByUserId($userId)
    {
        try {
            $conn = Connection::getConn();

            $sql = 'SELECT i.*, t.turma_id, t.nome AS turma, t.horario_inicio, t.horario_termino, d.nome AS disciplina, t.professor_nome
            FROM inscricoes i
            INNER JOIN turmas t ON i.turma_id = t.turma_id
            INNER JOIN disciplinas d ON t.disciplina_id = d.disciplina_id
            WHERE i.usuario_id = :userId';


            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $sections;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter inscrições do usuário: ' . $e->getMessage());
        }
    }

    public static function enroll($userId, $classId)
    {
        try {
            $conn = Connection::getConn();

            $sql = 'INSERT INTO inscricoes (usuario_id, turma_id, data_inscricao) 
            VALUES (:userId, :classId, NOW())';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':classId', $classId, PDO::PARAM_INT);
            $stmt->execute();

            return $conn->lastInsertId();
        } catch (\Exception $e) {
            throw new \Exception('Erro ao se inscrever na turma: ' . $e->getMessage());
        }
    }

    public static function unenroll($enrollmentId)
    {
        try {
            $conn = Connection::getConn();

            $sql = 'DELETE FROM inscricoes WHERE inscricao_id = :enrollmentId';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':enrollmentId', $enrollmentId, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao se desinscrever da turma: ' . $e->getMessage());
        }
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
