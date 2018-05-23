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

        public function find($id)
        {
            return Conn::getConn()->query("select * from imagens where flag='animal' and id_foreign=".$id)->fetchAll(PDO::FETCH_ASSOC);
        }

		public function all()
        {
            return Conn::getConn()->query("select * from imagens where flag='animal'")->fetchAll(PDO::FETCH_ASSOC);
        }
		
        public function trash($id) 
        {
            $imagem = Conn::getConn()->query("select nome_imagem from imagens where id=".$id)->fetch(PDO::FETCH_ASSOC);
            $st = Conn::getConn()->prepare("delete from imagens where id=?");
            $st->bindParam(1, $id);
            $st->execute();
            if ($st->rowCount() > 0)
            {
                unlink("./app/client/assets/imagens/animais/".$imagem["nome_imagem"]);
                return true;
            }
            return false;
        }
    }