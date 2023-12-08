<?php

use Database\Connection;

class Wait
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

    public static function getWaitListRegistrations($userId)
    {
        try {
            $conn = Connection::getConn();

            $sql = 'SELECT le.*, t.nome AS turma, d.nome AS disciplina
                    FROM lista_espera le
                    INNER JOIN turmas t ON le.turma_id = t.turma_id
                    INNER JOIN disciplinas d ON t.disciplina_id = d.disciplina_id
                    WHERE le.usuario_id = :userId';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            $waitListRegistrations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $waitListRegistrations;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter inscrições na lista de espera: ' . $e->getMessage());
        }
    }

    public static function checkIfIsWaitList($userId, $classId)
    {
        try {
            $conn = Connection::getConn();
    
            $sql = 'SELECT COUNT(*) as count FROM lista_espera WHERE usuario_id = :userId AND turma_id = :classId';
    
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':classId', $classId, PDO::PARAM_INT);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $result['count'] > 0;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao verificar lista de espera: ' . $e->getMessage());
        }
    }

    public static function insertIntoWaitList($userId, $classId)
    {
        try {
            $conn = Connection::getConn();

            $sql = 'INSERT INTO lista_espera (usuario_id, turma_id, data_entrada) 
                    VALUES (:userId, :classId, NOW())';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':userId', $userId);
            $stmt->bindValue(':classId', $classId);
            $stmt->execute();

            return $conn->lastInsertId();
        } catch (\Exception $e) {
            throw new \Exception('Erro ao inserir na lista de espera: ' . $e->getMessage());
        }
    }

    public static function getPositionInWaitList($userId, $classId)
    {
        try {
            $conn = Connection::getConn();

            $sql = 'SELECT COUNT(*) + 1 as position
                    FROM inscricoes
                    WHERE turma_id = :classId
                    AND data_inscricao < (
                        SELECT data_inscricao
                        FROM inscricoes
                        WHERE turma_id = :classId
                        AND usuario_id = :userId
                    )';

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':userId', $userId);
            $stmt->bindValue(':classId', $classId);
            $stmt->execute();

            $position = $stmt->fetch(\PDO::FETCH_ASSOC);

            return $position['position'];
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter posição na lista de espera: ' . $e->getMessage());
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
}
