<?php 
use PHPUnit\Framework\TestCase;
require_once './lib/Database/Connection.php';
require_once './app/model/Enrollment.php';
require_once './app/model/Section.php';
require_once './app/model/Wait.php';
class FilaDeEsperaTest extends TestCase
{
    public function testInscricaoNaFilaDeEspera(){
        // Simula uma inscricao mal sucedida devido a Inscricao Sem Usuario
        $turma = 1;
        $userId = 1; // ID do usuário

        $result = (int) Wait::insertIntoWaitList($userId, $turma);
        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testInscricaoSemTurmaNaFilaDeEspera(){
        // Simula uma inscricao mal sucedida devido a Inscricao Sem Usuario
        $turma = 0;
        $userId = 1; // ID do usuário
        $this->expectException(Exception::class);
        Wait::insertIntoWaitList($userId, $turma);
    }

    public function testInscricaoSemUsuarioNaFilaDeEspera(){
        // Simula uma inscricao mal sucedida devido a Inscricao na Fila De Espera Sem Usuario
        $turma = 1;
        $userId = 0; // ID do usuário
        $this->expectException(Exception::class);
        Wait::insertIntoWaitList($userId, $turma);
    }

    

}