<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class ImagemDenuncia
    {
        public function save( $nameImage, $id_denuncia )
        {
            if($id_denuncia <> "")
            {
                $st = Conn::getConn()->prepare("call inserir_imagens_denuncias(?,?)");
                $st->bindParam(1, $id_denuncia);
                $st->bindParam(2, $nameImage);
                return $st->execute();
            }
            return false;
        }

        public function trash($id) 
        {
           $imagem = Conn::getConn()->query("select nome_imagem from Imagens_Denuncias where id=".$id)->fetch(PDO::FETCH_ASSOC);
           return (Conn::getConn()->query("delete from Imagens_Denuncias where id=".$id) == true) and (unlink("./app/client/assets/imagens/denunciadas/".$imagem["nome_imagem"]));
        }
    }