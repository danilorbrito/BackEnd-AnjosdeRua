<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class AcaoPromovida 
    {
        public function save( $acao )
        {
            if($acao->titulo <> "" and $acao->texto <> "")
            {
                $st = Conn::getConn()->prepare("insert into Acoes_Promovidas(titulo, texto) values(?,?)");
                $st->bindParam(1, $acao->titulo);
                $st->bindParam(2, $acao->texto);
                return $st->execute();
            }
            return false;
        }

        public function all()
        {
            return Conn::getConn()->query("select * from Acoes_Promovidas")->fetchAll(PDO::FETCH_ASSOC);
        }

        public function find( $id )
        {
            return Conn::getConn()->query("select * from Acoes_Promovidas where id=".$id)->fetch(PDO::FETCH_ASSOC);
        }

        public function update( $acao )
        {
            if($acao->titulo <> "" and $acao->texto <> "" and $acao->id <> "")
            {
                $st = Conn::getConn()->prepare("update Acoes_Promovidas set titulo=?, texto=? where id=?");
                $st->bindParam(1, $acao->titulo);
                $st->bindParam(2, $acao->texto);
                $st->bindParam(3, $acao->id);
                $st->execute();
                if($st->rowCount() > 0)
                    return true;
                return false;   
            } 

            return false;
        }

        public function trash( $id )
        {
            $st = Conn::getConn()->prepare("delete from Acoes_Promovidas where id=?");
            $st->bindParam(1, $id);
            $st->execute();
            if($st->rowCount() > 0)
                return true;
            return false;
        }
    }