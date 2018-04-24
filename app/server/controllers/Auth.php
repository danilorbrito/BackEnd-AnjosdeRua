<?php namespace app\server\controllers;

    //use app\server\controllers\Conn;
    use app\server\models\Login;
    use app\server\controllers;

    class Auth {
        private $login;
        private $password;
        private $typeUser;

        function __construct( $login, $pass, $typeUser )
        {
            //verificar login no banco
            //gerar jwt, se válido
            //retornar jwt
            //caso erro, retornar erro
            $this->login = $login;
            $this->password = $pass;
            $this->typeUser = $typeUser;
        }

        public function logar() {
            $resultSQL = '';

            if ($this->typeUser === 1) { //para usuarios comuns
                $resultSQL = Login::logarCommonUser($this->login, $this->password);
                if ($resultSQL > 0)
                    return Router::Jwt();
            } elseif ($this->typeUser === 2) { //para administradores
                $resultSQL = Login::logarAdmin($this->login, $this->password);
                if ($resultSQL > 0)
                    return Router::Jwt();
            } 
            
            return 'err';
            
        }

    }