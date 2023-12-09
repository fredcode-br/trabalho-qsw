<?php

use DebugDB\ConnectionDebug;
use PHPUnit\Framework\TestCase;

require_once './app/utils/functions.php';

require_once './app/model/User.php'; // Substitua pelo caminho correto para sua classe




class UserTest extends TestCase
{
  
  
    public function testValidateLoginValidCredentials()
    {
        // Simulando o retorno dos dados do banco de dados para o email e senha fornecidos
        // Define a conexão simulada
        $user = new User();
        $user->setEmail('joao@example.com');
        $user->setPassword('1234');
        $result = $user->validateLogin();
        $this->assertTrue($result);
    }

    public function testValidateLoginInvalidCredentials()
    {
        $user = new User();
        $user->setEmail('invalid@example.com');
        $user->setPassword('wrongpassword');
        $this->expectException(Exception::class);
        $user->validateLogin();
    }

    public function testValidateRegistrationSuccess()
    {
        // Define a conexão simulada
        $email = generateRandomEmail();
        $user = new User();
        $user->setName('John Doe');
        $user->setEmail($email);
        $user->setPassword('password123');
        $this->assertTrue($user->validateRegistration());
    }
    public function testValidateRegistrationDuplicateEmail()
    {
        // Define a conexão simulada
        $user = new User();
        $user->setName('John Doe');
        $user->setEmail('duplicate@example.com');
        $user->setPassword('password123');
        $user_2 = new User();
        $user_2->setName('Maria Doe');
        $user_2->setEmail('duplicate@example.com');
        $user_2->setPassword('password1234');
        $this->expectException(Exception::class);
        $user->validateRegistration();
    }

    public function testValidateRegistrationWeakPassword()
    {   
        // Senha fraca com menos de 4 caracteres
        $email = str_repeat('a', 64).'@'.str_repeat('g', 255).'.com';
        $user = new User();
        $user->setName('John Doe');
        $user->setEmail($email);
        $user->setPassword('abc'); 

        $this->expectException(Exception::class);
        $user->validateRegistration();
    }
}
