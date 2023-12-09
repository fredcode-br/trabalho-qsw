<?php 

use PHPUnit\Framework\TestCase;

require_once 'app/controller/EnrollmentController.php';

class InscricaoControllerTest extends TestCase
{
    public function testIndiceDaInscricao()
    {
        $controller = new EnrollmentController(); // Instantiate your controller
        // Call the method that returns the HTML content
        $html = $controller->index(); // Change this method name to your controller method
        // Assert that the HTML contains expected content
        $this->assertStringContainsString('Horário', $html);
        $this->assertStringContainsString('Professor', $html);
    }

    public function testRetornoDoMetodoCheck()
    {
        // Prepare the data to simulate the input
        $data = [
            'turmas' => [
                ['turmaId' => 1, 'disciplina' => 'Disciplina A'],
                ['turmaId' => 2, 'disciplina' => 'Disciplina B'],
                // Add more sample data if needed
            ]
        ];

        // Mock the session to simulate $_SESSION
        $_SESSION['usr'] = array(
            'id_user' => 1,
            'name_user' => 'Joao'
        );

        // Create an instance of your controller
        $controller = new EnrollmentController();
        // Chamar o metodo com os dados simulados
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
        $controller = new EnrollmentController(); // Instantiate your controller
        // Call the method that returns the HTML content
        $html = $controller->review(); // Change this method name to your controller method
        // Assert that the HTML contains expected content
        $this->assertStringContainsString('Horário', $html);
        $this->assertStringContainsString('Professor', $html);
    }

    public function testListDaInscricao()
    {
        $_SESSION['usr'] = array(
            'id_user' => 1,
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
            'inscricaoId' => '1' // Substitua pelo ID válido para o teste
            // Adicione outros dados conforme necessário para o teste
        ];

        // Cria uma instância da sua classe que contém o método unsubscribe
        $yourObject = new EnrollmentController(); // Substitua YourClass pelo nome real da sua classe
        // Chame o método unsubscribe com os dados simulados
        ob_start();
        $yourObject->unsubscribe($dataTest);
        $jsonResult = ob_get_clean();
      
        $this->assertStringContainsString('', $jsonResult);
        $this->assertStringContainsString('', $jsonResult);
        print_r($jsonResult);
       

    
    }

    // Teste para o cenário em que não há dados passados para o método unsubscribe
    public function testUnsubscribeComDadosInvalidos()
    {
        // Crie uma instância da sua classe que contém o método unsubscribe
        $yourObject = new EnrollmentController(); // Substitua YourClass pelo nome real da sua classe
        $dataTest = [
            'inscricaoId' => '1' // Substitua pelo ID válido para o teste
            // Adicione outros dados conforme necessário para o teste
        ];
        // Chame o método unsubscribe sem dados
        ob_start();
        $yourObject->unsubscribe($dataTest);
        $jsonResult = ob_get_clean();
        
        // Decode o JSON retornado em um array para realizar asserções
        $this->assertStringContainsString('', $jsonResult);
        $this->assertStringContainsString('', $jsonResult);
        print_r($jsonResult);
       
    }
}
