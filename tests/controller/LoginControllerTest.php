<?php

use PHPUnit\Framework\TestCase;

require_once 'app/controller/LoginController.php';
class LoginControllerTest extends TestCase
{
    // Teste do cenario inicial do login
    public function testLoginPage()
    {
        $controller = new LoginController();
        $html = $controller->index();
        $this->assertStringContainsString('login', $html);
    }
    // Teste do cenario de verificacao
    public function testLoginCheckValidoFunction()
    {
        $_POST['email'] = 'joao@example.com';
        $_POST['password'] = '1234';
        $controller = new LoginController();
        $controller->check();
        $this->assertFalse(isset($_SESSION['msg_error']));
    }

    // Teste do cenario de verificacao com email e senha invalidos
    public function testLoginCheckInvalidoFunction()
    {
        $_POST['email'] = 'teste@invalido.com';
        $_POST['password'] = '1122333423';
        $controller = new LoginController();
        $controller->check();
        $this->assertTrue(isset($_SESSION['msg_error']));
    }

    // Teste do cenario de verificacao com email invalido
    public function testLoginCheckEmailInvalidoFunction()
    {
        $_POST['email'] = 'teste@invalido.com';
        $_POST['password'] = '1234';
        $controller = new LoginController();
        $controller->check();
        $this->assertTrue(isset($_SESSION['msg_error']));
    }
    // Teste do cenario de verificacao com senha invalida
    public function testLoginCheckSenhaInvalidaFunction()
    {
        $_POST['email'] = 'joao@example.com';
        $_POST['password'] = '12332313';
        $controller = new LoginController();
        $controller->check();
        $this->assertTrue(isset($_SESSION['msg_error']));
    }
}
