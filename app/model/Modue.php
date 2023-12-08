<?php

use Database\Connection;

class Module
{
    private $id;
    private $name;

    public function __construct($id = null, $name = null)
    {
        $this->id = $id;
        $this->name = $name;
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

    public function getModules()
    {
        try {
            $conn = Connection::getConn();

            $sql = 'SELECT * FROM modulos';
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $modules;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter módulos: ' . $e->getMessage());
        }
    }
}
