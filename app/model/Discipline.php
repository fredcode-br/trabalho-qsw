<?php

use Database\Connection;

class Discipline
{
    private $id;
    private $name;
    private $description;
    private $moduleId;

    public function __construct($id = null, $name = null, $description = null, $moduleId = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->moduleId = $moduleId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getModuleId()
    {
        return $this->moduleId;
    }

    public function setModuleId($moduleId)
    {
        $this->moduleId = $moduleId;
    }

    public function getDisciplines()
    {
        try {
            $conn = Connection::getConn();

            $sql = 'SELECT * FROM disciplinas';
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $disciplines = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $disciplines;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter disciplinas: ' . $e->getMessage());
        }
    }
}
