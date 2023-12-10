<?php

use PHPUnit\Framework\TestCase;

require_once 'app/controller/HomeController.php';

class HomeControllerTest extends TestCase
{
     // Testa o cenario de home
    public function testHome()
    {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
        $controller = new HomeController();
        $html = $controller->index();
        $this->assertStringContainsString('Selecione uma opção', $html);
    }
     // Testa o cenario de logout
     public function testLogOut()
     {
        $_SESSION['usr'] = array(
            'id_user' => '1',
            'name_user' => 'Joao'
        );
         $controller = new HomeController();
         $controller->logout();
         $this->assertFalse(isset($_SESSION['usr']));
     }

}