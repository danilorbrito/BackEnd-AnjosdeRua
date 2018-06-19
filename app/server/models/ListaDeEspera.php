<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use app\server\models\Animal;
    use PDO;

    class ListaDeEspera
    {
        public function save( $interessado )
        {
            if($interessado->id_animal <> "" and $interessado->nome <> "" and $interessado->email <> "" and $interessado->telefone <> "")
            {
                $st = Conn::getConn()->prepare('insert into lista_de_espera(id_animal,nome,email,telefone) values(?,?,?,?)');
                $st->bindParam(1, $interessado->id_animal);
                $st->bindParam(2, $interessado->nome);
                $st->bindParam(3, $interessado->email);
                $st->bindParam(4, $interessado->telefone);
                return $st->execute();
            }
            return false;
        }

        public function arrayListEsp( $linha )
        {
            $animal = new Animal();
            $linha['animal'] = $animal->findById($linha['id_animal']);
            unset($linha['id_animal']);
            return $linha;
        }

        public function all()
        {
            $st = Conn::getConn()->query('select * from lista_de_espera');
            $result = $st->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();
            if($result == true)
            {
                $retorno = array();
                foreach($result as $res)
                {
                    $retorno[] = $this->arrayListEsp($res);
                }
                return $retorno;
            }
            return [];
        }

        public function find( $id )
        {
            $st = Conn::getConn()->query('select * from lista_de_espera where id='.$id);
            $result = $st->fetch(PDO::FETCH_ASSOC);
            $st->closeCursor();
            if ($result == true)
            {
                return $this->arrayListEsp($result);
            }
            return false;
        }

        public function update( $interessado )
        {
            if($interessado->id_animal <> "" and $interessado->nome <> "" and $interessado->email <> "" and $interessado->telefone <> "")
            {
                $st = Conn::getConn()->prepare('update lista_de_espera set id_animal=?, nome=?, email=?, telefone=? where id=?');
                $st->bindParam(1, $interessado->id_animal);
                $st->bindParam(2, $interessado->nome);
                $st->bindParam(3, $interessado->email);
                $st->bindParam(4, $interessado->telefone);
                $st->bindParam(5, $interessado->id);
                $st->execute();
                if($st->rowCount() > 0)
                    return true;
                return false;
            }
            return false;
        }

        public function trash( $id )
        {
            $st = Conn::getConn()->prepare('delete from lista_de_espera where id=?');
            $st->bindParam(1, $id);
            $st->execute();
            if($st->rowCount() > 0)
                return true;
            return false;
        }
    }