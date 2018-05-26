<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class ListaDeEspera
    {
        public function save( $interessado )
        {
            if($interessado->nome <> "" and $interessado->email <> "" and $interessado->telefone <> "" and $interessado->descricao_animal <> "")
            {
                $st = Conn::getConn()->prepare('insert into lista_de_espera(nome,email,telefone,descricao_animal) values(?,?,?,?)');
                $st->bindParam(1, $interessado->nome);
                $st->bindParam(2, $interessado->email);
                $st->bindParam(3, $interessado->telefone);
                $st->bindParam(4, $interessado->descricao_animal);
                return $st->execute();
            }
            return false;
        }

        public function all()
        {
            return Conn::getConn()->query('select * from lista_de_espera')->fetchAll(PDO::FETCH_ASSOC);
        }

        public function find( $id )
        {
            return Conn::getConn()->query('select * from lista_de_espera where id='.$id)->fetch(PDO::FETCH_ASSOC);
        }

        public function update( $interessado )
        {
            if($interessado->id <> "" and $interessado->nome <> "" and $interessado->email <> "" and $interessado->telefone <> "" and $interessado->descricao_animal <> "")
            {
                $st = Conn::getConn()->prepare('update lista_de_espera set nome=?, email=?, telefone=?, descricao_animal=? where id=?');
                $st->bindParam(1, $interessado->nome);
                $st->bindParam(2, $interessado->email);
                $st->bindParam(3, $interessado->telefone);
                $st->bindParam(4, $interessado->descricao_animal);
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