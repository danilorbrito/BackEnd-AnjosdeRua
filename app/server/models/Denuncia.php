<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Denuncia {

        //recebe um array com os dados da denuncia de insere no banco
        public function save( $denuncia )
        {
            //valida os campos obrigatórios antes
            if( $denuncia->descricao <> "" and $denuncia->delator <> "" and $denuncia->datahora <> "" and $denuncia->descricaolocal <> "" )
            {
                $st = Conn::getConn()->prepare("insert into denuncias(descricao,delator,datahora,descricaolocal) values(?,?,?,?)");
                $st->bindParam(1, $denuncia->descricao);
                $st->bindParam(2, $denuncia->delator);
                $st->bindParam(3, $denuncia->datahora);
                $st->bindParam(4, $denuncia->descricaolocal);
                return $st->execute();
            }
            else
                return false;
        }

        //retorna todas as denuncias
        public function all() 
        {
            return Conn::getConn()->query("SELECT * FROM Denuncias")->fetchAll(PDO::FETCH_ASSOC);
        }

        //retorna denuncia pelo id
        public function find($id) 
        {
            $st = Conn::getConn()->prepare(" SELECT * FROM Denuncias WHERE id=? ");
            $st->bindParam(1, $id);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        //deleta denuncia pelo id
        public function trash( $id )
        {
            $st = Conn::getConn()->prepare(" DELETE FROM Denuncias WHERE id=? ");
            $st->bindParam(1, $id);
            return $st->execute();
        }
    }