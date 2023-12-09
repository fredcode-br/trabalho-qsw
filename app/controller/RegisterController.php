<?php

class RegisterController
{
    public function index()
    {
        $loader = new \Twig\Loader\FilesystemLoader('app/view');
        $twig = new \Twig\Environment($loader, [
            'cache' => '/path/to/compilation_cache',
            'auto_reload' => true,
        ]);
        $template = $twig->load('register.html');
        $parameters['error'] = $_SESSION['msg_error'] ?? null;

        return $template->render($parameters);
    }

    public function create()
    {
        try {
            $user = new User;
            $user->setName($_POST['name']);
            $user->setEmail($_POST['email']);
            $user->setPassword($_POST['password']);
            $user->validateRegistration();

            header('Location: /trabalho-qsw/login');
        } catch (\Exception $e) {
            $_SESSION['msg_error'] = array('msg' => $e->getMessage(), 'count' => 0);
            header('Location: /trabalho-qsw/register');
        }
    }
}
