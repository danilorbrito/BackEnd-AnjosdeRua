<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class ImagemDenuncia
    {
        public function save( $nameImage, $id_denuncia )
        {
            if($id_denuncia <> "")
            {
                $flag = "denunciada";
                $st = Conn::getConn()->prepare("call inserir_imagens(?,?,?)");
                $st->bindParam(1, $nameImage);
                $st->bindParam(2, $id_denuncia);
                $st->bindParam(3, $flag);
                return $st->execute();
            }
            return false;
        }

        public function all() 
        {
            return Conn::getConn()->query("select * from imagens where flag='denunciada'")->fetchAll(PDO::FETCH_ASSOC);
        }

        public function trash($id) 
        {
           $imagem = Conn::getConn()->query("select nome_imagem from imagens where id=".$id)->fetch(PDO::FETCH_ASSOC);
           return (Conn::getConn()->query("delete from imagens where id=".$id) == true) and (unlink("./app/client/assets/imagens/denunciadas/".$imagem["nome_imagem"]));
        }
    }