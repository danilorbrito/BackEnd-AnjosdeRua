<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Mensagem {
        //recebe um array com os dados da mensagem que insere no banco
        public function save( $mensagem )
        {
            //valida os campos obrigatórios antes
            if( $mensagem->id_adocao <> "" and $mensagem->mensagem <> "" )
            {
                $st = Conn::getConn()->prepare("insert into mensagens_adocoes(id_adocao, mensagem, remetente, datahora) values(?,?,?,?)");
                $st->bindParam(1, $mensagem->id_adocao);
                $st->bindParam(2, $mensagem->mensagem);
                $st->bindParam(3, $mensagem->remetente);//não precisa ser verificado, a procedure vai colocar um valor padrão
                $st->bindParam(4, $mensagem->datahora);//não precisa ser verificado, a procedure vai colocar um valor padrão
                return $st->execute();
            }
            else
                return false;
        }

        //retorna todas as mensagens
        public function all() 
        {
            return Conn::getConn()->query("SELECT * FROM mensagens_adocoes")->fetchAll(PDO::FETCH_ASSOC);
        }

        //retorna todas as mensagens
        public function findByAdocao($id) 
        {
            $st = Conn::getConn()->prepare(" SELECT * FROM mensagens_adocoes WHERE id_adocao=? ");
            $st->bindParam(1, $id);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }
    }