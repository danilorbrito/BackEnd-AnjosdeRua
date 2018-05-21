<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class AcaoPromovida 
    {
        public function save( $acao )
        {
            if($acao->titulo <> "" and $acao->texto <> "")
            {
                $st = Conn::getConn()->prepare("insert into acoes_promovidas(titulo, texto) values(?,?)");
                $st->bindParam(1, $acao->titulo);
                $st->bindParam(2, $acao->texto);
                return $st->execute();
            }
            return false;
        }

        public function all()
        {
            return Conn::getConn()->query("select * from acoes_promovidas")->fetchAll(PDO::FETCH_ASSOC);
        }

        public function find( $id )
        {
            return Conn::getConn()->query("select * from acoes_promovidas where id=".$id)->fetch(PDO::FETCH_ASSOC);
        }

        public function update( $acao )
        {
            if($acao->titulo <> "" and $acao->texto <> "" and $acao->id <> "")
            {
                $st = Conn::getConn()->prepare("update acoes_promovidas set titulo=?, texto=? where id=?");
                $st->bindParam(1, $acao->titulo);
                $st->bindParam(2, $acao->texto);
                $st->bindParam(3, $acao->id);
                $st->execute();
                return $st->execute();   
            } 

            return false;
        }

        public function trash( $id )
        {
            $st = Conn::getConn()->prepare("delete from acoes_promovidas where id=?");
            $st->bindParam(1, $id);
            $st->execute();
            if($st->rowCount() > 0)
                return true;
            return false;
        }
    }