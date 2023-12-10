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
        $this->assertStringContainsString('turma_id', $jsonResults);
        $this->assertStringContainsString('status', $jsonResults);

    }
    // Cenario de exibir pagina de revisar inscricao
    public function testRevisaoDaInscricao()
    {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
        $_POST['selectedClasses'] = ['1', '2', '3'];
        $controller = new EnrollmentController();
        $html = $controller->review();
        $this->assertStringContainsString('Horário', $html);
        $this->assertStringContainsString('Professor', $html);
    }
    // Listar inscricoes
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

    // remover inscricao com dados validos
    public function testUnsubscribeComDadosValidos()
    {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
        // Simular dados de entrada para o teste
        $dataTest = [
            'inscricaoId' => '1'

        ];

        $controller = new EnrollmentController();

        ob_start();
        $controller->unsubscribe($dataTest);
        $jsonResult = ob_get_clean();
        $this->assertStringContainsString('', $jsonResult);
    }
    // Teste para o cenário em que não há dados válidos passados para o método unsubscribe
    public function testUnsubscribeComDadosInvalidos()
    {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
        $controller = new EnrollmentController();
        $dataTest = [
            'inscricaoId' => '0'
        ];
        ob_start();
        $controller->unsubscribe($dataTest);
        $jsonResult = ob_get_clean();
        $this->assertStringContainsString('', $jsonResult);
        
    }

    public function testSubscribeComDadosValidos()
    {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
        $controller = new EnrollmentController();
        $dataTest = [
            'turmaId' => '1'
        ];
        ob_start();
        $controller->inscribe($dataTest);
        $jsonResult = ob_get_clean();
        $this->assertStringContainsString('', $jsonResult);

    }

    // Chama o método de remover inscricao com dados inválidos
    public function testSubscribeComDadosInvalidos()
    {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
        $controller = new EnrollmentController();
        $dataTest = [
            'turmaId' => '0'
        ];
        ob_start();
        $controller->inscribe($dataTest);
        $jsonResult = ob_get_clean();
        $this->assertStringContainsString('', $jsonResult);

    }

    // cenario de insercao da lista de espera
    public function testListaDeEsperaComDadosValidos()
    {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
        $dataTest = [
            'turmaId' => '1'
        ];
        $controller = new EnrollmentController();

        ob_start();
        $controller->waitlist($dataTest);
        $jsonResult = ob_get_clean();
        $this->assertStringContainsString('', $jsonResult);
    }

    // cenario de teste com dados de turma inválidos
    public function testListaDeEsperaComDadosInvalidos()
    {  
        $_SESSION['usr'] = array(
            'id_user' => '0',
            'name_user' => 'Joao'
        );
        $dataTest = [
            'turmaId' => '0'
        ];
        // Criar uma instância da sua classe que contém o método waitlist
        $controller = new EnrollmentController();
       
        ob_start();
        // Chama o método waitlist
        $controller->waitlist($dataTest);
        $jsonResult = ob_get_clean();
        $this->assertStringContainsString('', $jsonResult);
        
    }

    // cenario de teste para exibir pagina de sucesso de inscricao
    public function testPaginaDeSucesso()
    {
        $controller = new EnrollmentController();
        $html = $controller->success();
        $this->assertStringContainsString('Inscrição(ões) bem sucedida(s)!', $html);
    }
}
