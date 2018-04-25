<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Mensagem {
        //recebe um array com os dados da mensagem que insere no banco
        public function save( $mensagem )
        {
            //valida os campos obrigatórios antes
            if( $mensagem->idadocao <> "" and $mensagem->mensagem <> "" and $mensagem->remetente <> "" and $mensagem->datahora <> "")
            {
                $st = Conn::getConn()->prepare("insert into mensagens(idadocao, mensagem, remetente, datahora) values(?,?,?,?)");
                $st->bindParam(1, $mensagem->idadocao);
                $st->bindParam(2, $mensagem->mensagem);
                $st->bindParam(3, $mensagem->remetente);
                $st->bindParam(4, $mensagem->datahora);
                return $st->execute();
            }
            else
                return false;
        }

        //retorna todas as mensagens
        public function all() 
        {
            return Conn::getConn()->query("SELECT * FROM Mensagens")->fetchAll(PDO::FETCH_ASSOC);
        }

        //retorna todas as mensagens
        public function findByAdocao($id) 
        {
            $st = Conn::getConn()->prepare(" SELECT * FROM mensagens WHERE idadocao=? ");
            $st->bindParam(1, $id);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }
    }