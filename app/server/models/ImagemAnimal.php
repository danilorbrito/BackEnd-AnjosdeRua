<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class ImagemAnimal
    {
        public function save( $nameImage, $id_animal )
        {
            if($id_animal <> "")
            {
                $st = Conn::getConn()->prepare("call inserir_imagens_animais(?,?)");
                $st->bindParam(1, $id_animal);
                $st->bindParam(2, $nameImage);
                return $st->execute();
            }
            return false;
        }

        public function trash($id) 
        {
           $imagem = Conn::getConn()->query("select nome_imagem from imagens_animais where id=".$id)->fetch(PDO::FETCH_ASSOC);
           return (Conn::getConn()->query("delete from imagens_animais where id=".$id) == true) and (unlink("./app/client/assets/imagens/animais/".$imagem["nome_imagem"]));
        }
    }