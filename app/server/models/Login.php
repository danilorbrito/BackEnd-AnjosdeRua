<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Login {

        public static function logarCommonUser($login, $pass) {
            $hash = md5($pass);
            $result = Conn::getConn()->prepare("select * from associados where email=? and pass=? limit 1");
            $result->bindValue(1, $login);
            $result->bindValue(2, $hash);
            $result->execute();
            $user = $result->fetch(PDO::FETCH_ASSOC);

            $user <> '' ?
                $adocao = Conn::getConn()->query("select id from adocoes where id_associado=".$user['id'])->fetchAll(PDO::FETCH_ASSOC) :
                $adocao = '';
            
            return array ($result->rowCount(), $user['id'], $adocao);
        }

        public static function logarAdmin($login, $pass) {
            $hash = md5($pass);
            $result = Conn::getConn()->prepare("select * from admin where login=? and pass=? limit 1");
            $result->bindValue(1, $login);
            $result->bindValue(2, $hash);
            $result->execute();
            return $result->rowCount();
        }
    }