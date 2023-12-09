<?php
use PHPUnit\Framework\TestCase;
require_once './lib/Database/Connection.php';
require_once './app/model/Enrollment.php';
require_once './app/model/Section.php';
use Database\Connection;
class TurmaTest extends TestCase
{
    public function testArmazenarTurmaNoObjetoPorId()
    {
        // Cria um objeto Turma
        $turma = new Section();
        // Executa o método storeSectionInObjectById com um ID válido
        $turmaId = 1;
        $turma->storeSectionInObjectById($turmaId);
        // Verifica se as propriedades foram preenchidas corretamente
        $this->assertEquals(1, $turma->getId());
    }

    public function testAbrirEFechaTurma()
    {
        // Executa o método abrirTurma com um ID válido
        $turmaId = 1;
        $aberto = Section::abrirTurma($turmaId);
        $this->assertTrue($aberto);
        // Executa o método fecharTurma com um ID válido
        $fechado = Section::fecharTurma($turmaId);
        $this->assertTrue($fechado);
    }

    public function testAbrirEFechaTurmaInexistente()
    {
        // Executa o método abrirTurma com um ID válido
        $turmaId = 0;
        $aberto = Section::abrirTurma($turmaId);
        $this->assertFalse($aberto);
        // Executa o método fecharTurma com um ID válido
        $fechado = Section::fecharTurma($turmaId);
        $this->assertFalse($fechado);
    }

    public function testObterStatusTurmaFechadaOuAberta(){
        $turmaId = 1; // teste com turma valida
        $status = Section::getSectionInfo($turmaId, null);
        $this->assertIsString($status);
        $fechada = Section::abrirTurma($turmaId);
        $this->assertTrue($fechada);
        $status = Section::getSectionInfo($turmaId, null);
        $this->assertTrue($status=='');
    }
    public function testObterStatusTurmaInvalida(){
        $turmaId = 0;
        $this->expectException(Exception::class);
        Section::getSectionInfo($turmaId, null);  
    }
}
