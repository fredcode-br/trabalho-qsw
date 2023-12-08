<?php

    class HomeController
    {
        public function index()
        {
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader, [
                'cache' => '/path/to/compilation_cache',
                'auto_reload' => true,
            ]);

            $template = $twig->load('home.html');
                $parameters['name_user'] = $_SESSION['usr']['name_user'];

            return $template->render($parameters);
        }

        public function logout()
        {
            unset($_SESSION['usr']);
            session_destroy();

            header('Location: http://localhost/trabalho-qsw');
        }
    }
