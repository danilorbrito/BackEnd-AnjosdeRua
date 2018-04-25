<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Adocao {

        //recebe um array com os dados da adocao que insere no banco
        public function save( $adocao )
        {
            //valida os campos obrigatórios antes
            if( $adocao->idanimal <> "" and $adocao->idassociado <> "" and $adocao->data <> "")
            {
                $st = Conn::getConn()->prepare("insert into adocoes(idanimal,idassociado,data) values(?,?,?)");
                $st->bindParam(1, $adocao->idanimal);
                $st->bindParam(2, $adocao->idassociado);
                $st->bindParam(3, $adocao->data);
                return $st->execute();
            }
            else
                return false;
        }

        //retorna todas as adocoes
        public function all() 
        {
            return Conn::getConn()->query("SELECT * FROM Adocoes")->fetchAll(PDO::FETCH_ASSOC);
        }

        //retorna adocao pelo id
        public function find($id) 
        {
            $st = Conn::getConn()->prepare(" SELECT * FROM Adocoes WHERE id=? ");
            $st->bindParam(1, $id);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        //retorna adocao pelo idassociado
        public function findByAssociado($id) 
        {
            $st = Conn::getConn()->prepare(" SELECT * FROM Adocoes WHERE idassociado=? ");
            $st->bindParam(1, $id);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        //atualiza dados da adocao 
        public function update( $adocao )
        {
            //valida os campos obrigatórios antes
            if( $adocao->id <> "" and $adocao->idanimal <> "" and $adocao->idassociado <> "" and $adocao->data <> "" )
            {
                $st = Conn::getConn()->prepare(" UPDATE Adocoes SET idanimal=?, idassociado=?, data=? WHERE id=? ");
                $st->bindParam(1, $adocao->idanimal);
                $st->bindParam(2, $adocao->idassociado);
                $st->bindParam(3, $adocao->data);
                $st->bindParam(4, $adocao->id);
                return $st->execute();
            }
            else
                return false;
        }

        //deleta adocao pelo id
        public function trash( $id )
        {
            $st = Conn::getConn()->prepare(" DELETE FROM Adocoes WHERE id=? ");
            $st->bindParam(1, $id);
            return $st->execute();
        }
    }