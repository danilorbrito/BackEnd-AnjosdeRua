<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class ImagemAnimal
    {
        public function save( $nameImage, $id_animal )
        {
            if($id_animal <> "")
            {   
                $flag = "animal";
                $st = Conn::getConn()->prepare("call inserir_imagens(?,?,?)");
                $st->bindParam(1, $nameImage);
                $st->bindParam(2, $id_animal);
                $st->bindParam(3, $flag);
                return $st->execute();
            }
            return false;
        }

        public function all()
        {
            return Conn::getConn()->query("select * from imagens where flag='animal'")->fetchAll(PDO::FETCH_ASSOC);
        }

        public function trash($id) 
        {
           $imagem = Conn::getConn()->query("select nome_imagem from imagens where id=".$id)->fetch(PDO::FETCH_ASSOC);
           return (Conn::getConn()->query("delete from imagens where id=".$id) == true) and (unlink("./app/client/assets/imagens/animais/".$imagem["nome_imagem"]));
        }
    }