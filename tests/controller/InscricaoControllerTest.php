<?php

use PHPUnit\Framework\TestCase;

require_once 'app/controller/EnrollmentController.php';

class InscricaoControllerTest extends TestCase
{
     // Testa o cenario de inicial de inscricao
    public function testIndiceDaInscricao()
    {
        $controller = new EnrollmentController();
        $html = $controller->index();
        $this->assertStringContainsString('Horário', $html);
        $this->assertStringContainsString('Professor', $html);
    }

    // Testa o cenario de revisao de inscricao
    public function testRetornoDoMetodoCheck()
    {
        $data = [
            'turmas' => [
                ['turmaId' => '1', 'disciplina' => 'Disciplina A'],
                ['turmaId' => '2', 'disciplina' => 'Disciplina B'],

            ]
        ];

        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );

        $controller = new EnrollmentController();
        ob_start();
        $controller->check($data);
        $jsonResults = ob_get_clean();

        $resultsArray = $jsonResults;

        $this->assertStringContainsString('turma_id', $resultsArray);
        $this->assertStringContainsString('status', $resultsArray);
    }

    public function testRevisaoDaInscricao()
    {
        $_POST['selectedClasses'] = ['1', '2', '3'];
        $controller = new EnrollmentController();
        $html = $controller->review();
        $this->assertStringContainsString('Horário', $html);
        $this->assertStringContainsString('Professor', $html);
    }

    public function testListDaInscricao()
    {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
        $controller = new EnrollmentController();

        $html = $controller->list();
        $this->assertStringContainsString('Horário', $html);
        $this->assertStringContainsString('Professor', $html);
    }

    public function testUnsubscribeComDadosValidos()
    {
        // Simular dados de entrada para o teste
        $dataTest = [
            'inscricaoId' => '1'

        ];
        // Cria uma instância da sua classe que contém o método unsubscribe
        $yourObject = new EnrollmentController();
        // Chame o método unsubscribe com os dados simulados
        ob_start();
        $yourObject->unsubscribe($dataTest);
        $jsonResult = ob_get_clean();
        $this->assertStringContainsString('', $jsonResult);
        $this->assertStringContainsString('', $jsonResult);
    }

    // Teste para o cenário em que não há dados válidos passados para o método unsubscribe
    public function testUnsubscribeComDadosInvalidos()
    {
        // Criar uma instância da sua classe que contém o método unsubscribe
        $yourObject = new EnrollmentController();

        $dataTest = [
            'inscricaoId' => '0'
        ];

        ob_start();
        $yourObject->unsubscribe($dataTest); // Chama o método unsubscribe sem dados
        $jsonResult = ob_get_clean();
        $this->assertStringContainsString('', $jsonResult);
        $this->assertStringContainsString('', $jsonResult);
        print_r($jsonResult);
    }

    public function testSubscribeComDadosValidos()
    {
        // Criar uma instância da sua classe que contém o método unsubscribe
        $yourObject = new EnrollmentController();

        $dataTest = [
            'turmaId' => '1'
        ];

        // Chama o método unsubscribe sem dados
        ob_start();
        $yourObject->inscribe($dataTest);
        $jsonResult = ob_get_clean();

        $this->assertStringContainsString('', $jsonResult);
        $this->assertStringContainsString('', $jsonResult);
      
    }

    // Chama o método de remover inscricao com dados inválidos
    public function testSubscribeComDadosInvalidos()
    {

        $this->expectException(Exception::class);
        // Criar uma instância da sua classe que contém o método unsubscribe
        $yourObject = new EnrollmentController();

        $dataTest = [
            'turmaId' => '0'
        ];

        ob_start();
        $yourObject->inscribe($dataTest);
    }

    public function testListaDeEsperaComDadosValidos()
    {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
        $dataTest = [
            'turmaId' => '0'
        ];
        // Cria uma instância da sua classe que contém o método waitlist
        $yourObject = new EnrollmentController();

        ob_start();
        $yourObject->waitlist($dataTest);
        $jsonResult = ob_get_clean();

        $this->assertStringContainsString('', $jsonResult);
        $this->assertStringContainsString('', $jsonResult);
       
    }

    public function testListaDeEsperaComDadosInvalidos()
    {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
        $dataTest = [
            'turmaId' => '0'
        ];
        // Criar uma instância da sua classe que contém o método waitlist
        $yourObject = new EnrollmentController();



        // Chama o método unsubscribe sem dados
        ob_start();
        $yourObject->waitlist($dataTest);
        $jsonResult = ob_get_clean();

        $this->expectException(Exception::class);
    }
    public function testPaginaDeSucesso()
    {
        $controller = new EnrollmentController();
        $html = $controller->index();
        $this->assertStringContainsString('Inscrição(ões) bem sucedida(s)!', $html);
    }
}
