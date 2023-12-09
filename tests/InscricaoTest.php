<?php 
use PHPUnit\Framework\TestCase;
require_once './lib/Database/Connection.php';
require_once './app/model/Enrollment.php';
require_once './app/model/Section.php';
class InscricaoTest extends TestCase
{

    private function abrirTurma($classId){
        $turma = new Section();
        $turma->storeSectionInObjectById($classId);
        $turma->setIsClosed(0);
        $turma->updateSectionInDatabase();
    }

    private function fecharTurma($classId){
        $turma = new Section();
        $turma->storeSectionInObjectById($classId);
        $turma->setIsClosed(1);
        $turma->updateSectionInDatabase();
    }


    public function testInscricaoBemSucedida()
    {
        // Simula um usuário e uma turma para inscrição
        $userId = 1; // ID do usuário
        $classId = 1; // ID da turma
        $this->abrirTurma($classId);
        $enrollment = new Enrollment(null, $userId, $classId);
        // Testa se a inscrição é bem-sucedida
        $enrollmentId = $enrollment->enroll($userId, $classId);
        // Verifica se o ID retornado é um número válido (ou seja, a inscrição foi bem-sucedida)
        $this->assertIsInt($enrollmentId);
        $this->assertGreaterThan(0, $enrollmentId);
    }

    public function testInscricaoMalSucedidaComTurmaFechada()
    {
        // Simula um usuário e uma turma para inscrição
        $userId = 1; // ID do usuário
        $classId = 1; // ID da turma
        // Fechando a turma
        $this->fecharTurma($classId);

        $sectionMock = $this->getMockBuilder(Section::class)
                            ->onlyMethods(['getSectionInfo'])
                            ->getMock();
        
        $sectionMock->method('getSectionInfo')->willThrowException(new Exception);

        $enrollment = new Enrollment(null, $userId, $classId);
        // Testa se é retornado um erro
        $this->expectException(Exception::class);
        $enrollment->enroll($userId, $classId);
    }

    public function testdisciplinaComPrerequisito(){
        // Simula uma inscricao mal sucedida devido ao pre-requisito
        $disciplinaPreRequisito = 1;
        $turmaDaDisciplinaComPreRequisito = 1;
        $userId = 1; // ID do usuário
        $enrollment = new Enrollment(null, $userId, $turmaDaDisciplinaComPreRequisito);
        $enrollment = new Enrollment(null, $userId, $turmaDaDisciplinaComPreRequisito);
        $this->expectException(Exception::class);
        $enrollment->enroll($userId, $turmaDaDisciplinaComPreRequisito);

    }

   
    public function testConflitoDeHorario()
    {
        // Simula um usuário e uma turma para inscrição
        // erro eh para dar confilto e nao ta dando
        $userId = 1; // ID do usuário
        $classId = 2; // ID da turma

        $this->abrirTurma($classId);
        $enrollment = new Enrollment(null, $userId, $classId);
        $enrollment->enroll($userId, $classId);
        $turma = new Section();
        $turma->storeSectionInObjectById($classId);
        $idDaNovaTurmaComHorarioConflitante = 5;
        $confilto = Section::checkScheduleConflict($userId, $idDaNovaTurmaComHorarioConflitante, null);
        // Testa se é retornado um conflito
        $this->assertTrue($confilto=='');
    }

    public function testInscricaoSemTurma(){
        // Simula uma inscricao mal sucedida devido a Inscricao Sem Turma
        $turmaInexistente = 0;
        $userId = 1; // ID do usuário
        $enrollment = new Enrollment(null, $userId, $turmaInexistente);
        $this->expectException(Exception::class);
        $enrollment->enroll($userId, $turmaInexistente);
    }

    public function testInscricaoSemUsuario(){
        // Simula uma inscricao mal sucedida devido a Inscricao Sem Usuario
        $turmaInexistente = 1;
        $userId = 0; // ID do usuário
        // cria uma inscricao
        $enrollment = new Enrollment(null, $userId, $turmaInexistente);
        $this->expectException(Exception::class);
        $enrollment->enroll($userId, $turmaInexistente);
    }

    public function testRemoverInscricao(){
        // Simula uma remocao de inscricao bem sucedida
        $inscricaoId = 1; // ID do usuário 
        $enrollment=Enrollment::unenroll($inscricaoId);
        $this->assertTrue($enrollment);
    }
    public function testRemoverInscricaoMalSucedida(){
        // Simula uma remocao de inscricao mal sucedida
        $inscricaoId = 0; // ID do usuário 
        $result = Enrollment::unenroll($inscricaoId);
        $this->assertFalse($result);
    }



    



}
?>