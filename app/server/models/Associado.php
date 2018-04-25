<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Associado {

        //recebe um array com os dados do associado que insere no banco
        public function save( $associado )
        {
            //valida os campos obrigatórios antes
            if( $associado->nome <> "" and $associado->sexo <> "" and $associado->email <> "" and $associado->pass)
            {
                $st = Conn::getConn()->prepare("insert into associados(nome,sexo,email,pass) values(?,?,?,?)");
                $st->bindParam(1, $associado->nome);
                $st->bindParam(2, $animal->sexo);
                $st->bindParam(3, $animal->email);
                $st->bindParam(4, $animal->pass);
                return $st->execute();
            }
            else
                return false;
        }

        //retorna todos os associados
        public function all() 
        {
            return Conn::getConn()->query("SELECT * FROM Associados")->fetchAll(PDO::FETCH_ASSOC);
        }

        //retorna associado pelo id
        public function find($id) 
        {
            $st = Conn::getConn()->prepare(" SELECT * FROM Associados WHERE id=? ");
            $st->bindParam(1, $id);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        //atualiza dados do associado 
        public function update( $associado )
        {
            //valida os campos obrigatórios antes
            if( $associado->id <> "" and $associado->nome <> "" and $associado->sexo <> "" and $associado->email <> "" and $associado->pass <> "")
            {
                $st = Conn::getConn()->prepare(" UPDATE Associados SET nome=?, sexo=?, email=?, pass=? WHERE id=? ");
                $st->bindParam(1, $associado->nome);
                $st->bindParam(2, $associado->sexo);
                $st->bindParam(3, $associado->email);
                $st->bindParam(4, $associado->pass);
                $st->bindParam(5, $associado->id);
                return $st->execute();
            }
            else
                return false;
        }

        //deleta associado pelo id
        public function trash( $id )
        {
            $st = Conn::getConn()->prepare(" DELETE FROM Associados WHERE id=? ");
            $st->bindParam(1, $id);
            return $st->execute();
        }

    }