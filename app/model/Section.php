<?php

use Database\Connection;

class Section
{
    private $id;
    private $name;
    private $disciplineId;
    private $professorName;
    private $startTime;
    private $endTime;
    private $isClosed;

    public function __construct(
        $id = null,
        $name = null,
        $disciplineId = null,
        $professorName = null,
        $startTime = null,
        $endTime = null,
        $isClosed = 0
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->disciplineId = $disciplineId;
        $this->professorName = $professorName;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->isClosed = $isClosed;
    }

    public static function getSectionById($classId)
    {
        try {
            $conn = Connection::getConn();

            $sql = 'SELECT d.nome as disciplina, t.turma_id, t.nome as turma, t.horario_inicio, t.horario_termino, t.professor_nome
                    FROM turmas t
                    INNER JOIN disciplinas d ON t.disciplina_id = d.disciplina_id
                    WHERE t.turma_id = :classId';

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':classId', $classId);
            $stmt->execute();

            $classe = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $classe;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter turmas por módulo: ' . $e->getMessage());
        }
    }

    public function storeSectionInObjectById($classId)
    {
        try {
            $conn = Connection::getConn();
    
            $sql = 'SELECT * FROM turmas WHERE turma_id = :classId';
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':classId', $classId);
            $stmt->execute();
    
            $sectionData = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($sectionData) {
                $this->id = $sectionData['turma_id'];
                $this->name = $sectionData['nome'];
                $this->disciplineId = $sectionData['disciplina_id'];
                $this->professorName = $sectionData['professor_nome'];
                $this->startTime = $sectionData['horario_inicio'];
                $this->endTime = $sectionData['horario_termino'];
                $this->isClosed = $sectionData['turma_fechada'];
            }
    
            return true; // Return true upon successful update
        } catch (\Exception $e) {
            throw new \Exception('Error updating section information: ' . $e->getMessage());
        }
    }

    public function updateSectionInDatabase()
    {
        try {
            $conn = Connection::getConn();

            $sql = 'UPDATE turmas SET 
                nome = :name,
                disciplina_id = :disciplineId,
                professor_nome = :professorName,
                horario_inicio = :startTime,
                horario_termino = :endTime,
                turma_fechada = :isClosed 
                WHERE turma_id = :id';

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':name', $this->name);
            $stmt->bindValue(':disciplineId', $this->disciplineId);
            $stmt->bindValue(':professorName', $this->professorName);
            $stmt->bindValue(':startTime', $this->startTime);
            $stmt->bindValue(':endTime', $this->endTime);
            $stmt->bindValue(':isClosed', $this->isClosed);
            $stmt->bindValue(':id', $this->id);

            $stmt->execute();

            return true; // Return true upon successful update
        } catch (\Exception $e) {
            throw new \Exception('Error updating section in the database: ' . $e->getMessage());
        }
    }

    public static function getSectionsByModule($moduleId)
    {
        try {
            $conn = Connection::getConn();

            $sql = 'SELECT d.disciplina_id, d.nome as disciplina, t.turma_id, t.nome as turma, t.horario_inicio, t.horario_termino, t.professor_nome
                    FROM turmas t
                    INNER JOIN disciplinas d ON t.disciplina_id = d.disciplina_id
                    WHERE d.modulo_id = :moduleId';

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':moduleId', $moduleId);
            $stmt->execute();

            $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $classes;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter turmas por módulo: ' . $e->getMessage());
        }
    }

    public static function getSectionInfo($turmaId, $disciplina)
    {
        $conn = Connection::getConn();

        $sql = "SELECT turma_fechada, nome FROM turmas WHERE turma_id = :turma_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':turma_id', $turmaId);
        $stmt->execute();

        $rowTurmaFechada = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($rowTurmaFechada && $rowTurmaFechada['turma_fechada']) {
            return 'A turma ' . $rowTurmaFechada['nome'] . ' de ' . $disciplina . ' está fechada.';
        }

        return '';
    }

    public static function isUserEnrolled($usuarioId, $turmaId, $disciplina)
    {
        $conn = Connection::getConn();

        $sql = "SELECT turmas.nome 
                FROM inscricoes
                JOIN turmas ON inscricoes.turma_id = turmas.turma_id
                WHERE inscricoes.usuario_id = :usuario_id AND inscricoes.turma_id = :turma_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuario_id', $usuarioId);
        $stmt->bindParam(':turma_id', $turmaId);
        $stmt->execute();

        $qtd = $stmt->rowCount();

        if ($qtd == 1) {
            $rowInscrito = $stmt->fetch(PDO::FETCH_ASSOC);
            return 'Você já está inscrito na turma ' . $rowInscrito['nome'] . ' de ' . $disciplina;
        }

        return '';
    }

    public static function checkScheduleConflict($usuarioId, $turmaId, $disciplina)
    {
        $conn = Connection::getConn();

        $sql = "SELECT turmas.turma_id, turmas.horario_inicio, turmas.horario_termino 
                FROM turmas
                JOIN inscricoes ON turmas.turma_id = inscricoes.turma_id
                WHERE inscricoes.usuario_id = :usuario_id";
        $stmtTurmasInscrito = $conn->prepare($sql);
        $stmtTurmasInscrito->bindParam(':usuario_id', $usuarioId);
        $stmtTurmasInscrito->execute();

        $resultados = $stmtTurmasInscrito->fetchAll(PDO::FETCH_ASSOC);

        foreach ($resultados as $row) {
            $turmaInscrita = $row['turma_id'];

            $sqlTurmaDesejada = "SELECT nome, horario_inicio, horario_termino FROM turmas WHERE turma_id = :turma_id";
            $stmtTurmaDesejada = $conn->prepare($sqlTurmaDesejada);
            $stmtTurmaDesejada->bindParam(':turma_id', $turmaId);
            $stmtTurmaDesejada->execute();
            $rowTurmaDesejada = $stmtTurmaDesejada->fetch(PDO::FETCH_ASSOC);

            if ($rowTurmaDesejada) {
                $horarioInicioDesejado = $rowTurmaDesejada['horario_inicio'];
                $horarioTerminoDesejado = $rowTurmaDesejada['horario_termino'];

                $sqlTurmaInscrita = "SELECT horario_inicio, horario_termino FROM turmas WHERE turma_id = :turma_inscrita";
                $stmtTurmaInscrita = $conn->prepare($sqlTurmaInscrita);
                $stmtTurmaInscrita->bindParam(':turma_inscrita', $turmaInscrita);
                $stmtTurmaInscrita->execute();
                $rowTurmaInscrita = $stmtTurmaInscrita->fetch(PDO::FETCH_ASSOC);

                $horarioInicioInscrito = $rowTurmaInscrita['horario_inicio'];
                $horarioTerminoInscrito = $rowTurmaInscrita['horario_termino'];

                if (
                    ($horarioInicioDesejado <= $horarioTerminoInscrito && $horarioTerminoDesejado >= $horarioInicioInscrito) ||
                    ($horarioInicioInscrito <= $horarioTerminoDesejado && $horarioTerminoInscrito >= $horarioInicioDesejado)
                ) {
                    return "A " . $rowTurmaDesejada['nome'] . " da " . $disciplina . " está em choque de horários com a outra turma a qual você está inscrito(a). Selecione outra turma.";
                }
            }
        }

        return '';
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

    public function getDisciplineId()
    {
        return $this->disciplineId;
    }

    public function setDisciplineId($disciplineId)
    {
        $this->disciplineId = $disciplineId;
    }

    public function getProfessorName()
    {
        return $this->professorName;
    }

    public function setProfessorName($professorName)
    {
        $this->professorName = $professorName;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }

    public function getIsClosed()
    {
        return $this->isClosed;
    }

    public function setIsClosed($isClosed)
    {
        $this->isClosed = $isClosed;
    }

    public function getClasses()
    {
        try {
            $conn = Connection::getConn();

            $sql = 'SELECT * FROM turmas';
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $classes;
        } catch (\Exception $e) {
            throw new \Exception('Erro ao obter turmas: ' . $e->getMessage());
        }
    }
}
