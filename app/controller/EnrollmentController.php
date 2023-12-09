<?php

    class EnrollmentController
    {
        public function index()
        {   
            $moduleId = 1;
            $classes = Section::getSectionsByModule($moduleId);
            $disciplines = array();
            
            foreach ($classes as $row) {
                $discipline = $row['disciplina'];

                $disciplines[$discipline][] = array(
                    'turmaId' => $row['turma_id'], 
                    'nome' => $row['turma'],
                    'horarioInicio' => $row['horario_inicio'],
                    'horarioTermino' => $row['horario_termino'],
                    'professorNome' => $row['professor_nome']
                );
            }

            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader, [
                // 'cache' => '/path/to/compilation_cache',
                'auto_reload' => true,
            ]);

            $template = $twig->load('enrollment.html');
            $parameters = array();
            $parameters['disciplines']  = $disciplines;
            return $template->render($parameters);
        }

        public function check() {
            $data = json_decode(file_get_contents('php://input'), true);

            $usuarioId = $_SESSION['usr']['id_user'];
            $resuls = array();
            
            foreach ($data['turmas'] as $turma) {
                $turmaId = $turma['turmaId'];
                $discipline = $turma['disciplina'];
                $resultado = array();
                
                $resultado['turma_id'] = $turmaId;
                $resultado['status'] = Section::getSectionInfo($turmaId, $discipline);
                
                if ($resultado['status'] === '') {
                    $resultado['enrolled'] = Section::isUserEnrolled($usuarioId, $turmaId, $discipline);
            
                    if ($resultado['enrolled'] === '') {
                        $resultado['conflict'] = Section::checkScheduleConflict($usuarioId, $turmaId, $discipline);
                    }
                }else {
                    $resultado['wait'] = Wait::checkIfIsWaitList($usuarioId,  $turmaId);
                }
            
                $resuls[] = $resultado;
            }

            $jsonResults = json_encode($resuls);
            echo $jsonResults;
        }

        public function review() {
            $moduleId = 1;
            $classes = array();

            foreach ($_POST['selectedClasses'] as $classId) {
                array_push($classes, Section::getSectionById($classId));
            }

            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader, [
                // 'cache' => '/path/to/compilation_cache',
                'auto_reload' => true,
            ]);

            $template = $twig->load('revision.html');
            $parameters = array();
            $parameters['classes']  = $classes;

            return $template->render($parameters);
        }

        public function list() {
            $moduleId = 1;
            $usuarioId = $_SESSION['usr']['id_user'];
            $enrollments = array();
            $wait = array();

            $enrollments = Enrollment::getEnrollmentsByUserId($usuarioId);
            $wait = Wait::getWaitListRegistrations($usuarioId);
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader, [
                // 'cache' => '/path/to/compilation_cache',
                'auto_reload' => true,
            ]);

            $template = $twig->load('enrollments.html');
            $parameters = array();
            $parameters['enrollments']  = $enrollments;
            $parameters['wait']  = $wait;

            return $template->render($parameters);
        }

        public function unsubscribe()
        {
            $data = json_decode(file_get_contents('php://input'), true);
            $inscricaoId = $data['inscricaoId'];

            $result = Enrollment::unenroll($inscricaoId);
            $jsonResult = json_encode($result);
            echo $jsonResult;
        }


        public function inscribe()
        {
            $usuarioId = $_SESSION['usr']['id_user'];
            $data = json_decode(file_get_contents('php://input'), true);
            $turmaId = $data['turmaId'];
            
            $result = Enrollment::enroll($usuarioId, $turmaId);
            $jsonResult = json_encode($result);
            echo $jsonResult;
        }

        public function waitlist()
        {
            $usuarioId = $_SESSION['usr']['id_user'];
            $data = json_decode(file_get_contents('php://input'), true);
            $turmaId = $data['turmaId'];
            
            Wait::insertIntoWaitList($usuarioId, $turmaId);
            $result = Wait::getPositionInWaitList($usuarioId, $turmaId);
            $jsonResult = json_encode($result);
            echo $jsonResult;
        }

        public function success()
        {   
            $loader = new \Twig\Loader\FilesystemLoader('app/view');
            $twig = new \Twig\Environment($loader, [
                // 'cache' => '/path/to/compilation_cache',
                'auto_reload' => true,
            ]);

            $template = $twig->load('success.html');
            $parameters = array();
            return $template->render($parameters);
        }

    }
