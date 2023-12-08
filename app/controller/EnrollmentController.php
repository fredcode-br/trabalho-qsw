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
                'cache' => '/path/to/compilation_cache',
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
                }
            
                $resuls[] = $resultado;
            }

            $jsonResults = json_encode($resuls);
            echo $jsonResults;
        }
        

    }
