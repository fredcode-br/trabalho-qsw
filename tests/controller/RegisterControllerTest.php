<?php

use PHPUnit\Framework\TestCase;

require_once 'app/controller/RegisterController.php';
class RegisterControllerTest extends TestCase
{
    public function testCreate()
    {
        $_POST['name'] = 'maria';
        $_POST['email'] = generateRandomEmail();
        $_POST['password'] = '12345';
        $obj = new RegisterController();  
       
        $obj->create(); 
        $this->assertFalse(isset($_SESSION['msg_error']));
    }

    // cenario sem nome
    public function testCreateInvalidoSemNome()
    {

        $_POST['name'] = '';
        $_POST['email'] = generateRandomEmail();
        $_POST['password'] = '12434';
        $obj = new RegisterController();  
        $obj->create();
        $this->assertTrue(isset($_SESSION['msg_error']));
        
    }

    // cenario sem senha
    public function testCreateInvalidoSemSenha()
    {

        $_POST['name'] = 'teste';
        $_POST['email'] = generateRandomEmail();
        $_POST['password'] = '';
        $obj = new RegisterController();  
        $obj->create();
        $this->assertTrue(isset($_SESSION['msg_error']));
        
    }

    // cenario sem email
    public function testCreateInvalidoSemEmail()
    {

      
        $_POST['name'] = 'teste';
        $_POST['email'] = '';
        $_POST['password'] = '12345';
        $obj = new RegisterController();  
        $obj->create();
        $this->assertTrue(isset($_SESSION['msg_error']));
        
    }

    public function testCreateInvalidoComDadosDeEmailJaExistente()
    {
       
        $_POST['name'] = 'John';
        $_POST['email'] = 'joao@example.com';
        $_POST['password'] = '12345';
        $obj = new RegisterController();  
        $obj->create();
        $this->assertTrue(isset($_SESSION['msg_error'])); 
    }

    // Cenario com dados de email e nome Ja Existente
    public function testCreateInvalidoComDadosDeEmaileNomeJaExistente()
    {
        $_POST['name'] = 'Joao';
        $_POST['email'] = 'joao@example.com';
        $_POST['password'] = '12345';
        $obj = new RegisterController();  
        $obj->create();
        $this->assertTrue(isset($_SESSION['msg_error'])); 
    }

    // cenario com cadastro de dados com nome ja cadastrado
    public function testCreateComDadosMesmoNome()
    {
        $_POST['name'] = 'Joao';
        $_POST['email'] = 'joaonovo@example.com';
        $_POST['password'] = '12345';
        $obj = new RegisterController();  
        $obj->create();
        $this->assertTrue(isset($_SESSION['msg_error']));
    }

    // cenario com cadastro de dados ja existentes
    public function testCreateComDadosJaExistentes()
    {
        $_POST['name'] = 'Joao';
        $_POST['email'] = 'joao@example.com';
        $_POST['password'] = '1234';
        $obj = new RegisterController();  
        $obj->create();
        $this->assertTrue(isset($_SESSION['msg_error']));
    }
}