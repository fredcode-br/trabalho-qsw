<?php

    use Database\Connection;

    class User
    {
        private $id;
        private $name;
        private $email;
        private $password;

        public function validateLogin()
        {
            $conn = Connection::getConn();
            $sql = 'SELECT * FROM usuarios WHERE email = :email';

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':email', $this->email);
            $stmt->execute();

            if ($stmt->rowCount()) {
                $result = $stmt->fetch();

                if ($result['senha'] === $this->password) {
                    $_SESSION['usr'] = array(
                        'id_user' => $result['usuario_id'], 
                        'name_user' => $result['nome']
                    );

                    return true;
                }
            }

            throw new \Exception('Login Inválido');
        }

        public function validateRegistration()
    {
        $conn = Connection::getConn();
        $emailCheckSql = 'SELECT * FROM usuarios WHERE email = :email';

        $emailCheckStmt = $conn->prepare($emailCheckSql);
        $emailCheckStmt->bindValue(':email', $this->email);
        $emailCheckStmt->execute();

        if ($emailCheckStmt->rowCount() > 0) {
            throw new \Exception('E-mail já está em uso. Escolha outro e tente novamente.');
        }

        if (strlen($this->password) < 4) {
            throw new \Exception('A senha deve ter no mínimo 4 caracteres.');
        }

        $insertSql = 'INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)';
        $insertStmt = $conn->prepare($insertSql);

        $insertStmt->bindValue(':nome', $this->name);
        $insertStmt->bindValue(':email', $this->email);
        $insertStmt->bindValue(':senha', $this->password);

        if ($insertStmt->execute()) {
            return true;
        } else {

            throw new \Exception('Falha no registro. Por favor, tente novamente.');
        }
    }

        public function setEmail($email)
        {
            $this->email = $email;
        }

        public function setName($name)
        {
            $this->name = $name;
        }

        public function setPassword($password)
        {
            $this->password = $password;
        }

        public function getName()
        {
            return $this->name;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function getPassword()
        {
            return $this->password;
        }
    }
