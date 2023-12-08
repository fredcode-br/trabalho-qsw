<?php

use Database\Connection;

class Requirement
{
    private $id;
    private $disciplineId;
    private $prerequisiteId;

    public function __construct($id = null, $disciplineId = null, $prerequisiteId = null)
    {
        $this->id = $id;
        $this->disciplineId = $disciplineId;
        $this->prerequisiteId = $prerequisiteId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getDisciplineId()
    {
        return $this->disciplineId;
    }

    public function setDisciplineId($disciplineId)
    {
        $this->disciplineId = $disciplineId;
    }

    public function getPrerequisiteId()
    {
        return $this->prerequisiteId;
    }

    public function setPrerequisiteId($prerequisiteId)
    {
        $this->prerequisiteId = $prerequisiteId;
    }

    public function getRequirements()
    {
        try {
            $conn = Connection::getConn();

            $sql = 'SELECT * FROM pre_requisitos';
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $requirements = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $requirements;
        } catch (\Exception $e) {
  
            throw new \Exception('Erro ao obter requisitos: ' . $e->getMessage());
        }
    }
}
