<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Denuncia {

        public function save($denuncia)
        {
            if ($denuncia->descricao <> "" and $denuncia->descricao_local <> "")
            {
                $st = Conn::getConn()->prepare("call inserir_denuncias(?,?,?)");
                $st->bindParam(1, $denuncia->descricao);
                $st->bindParam(2, $denuncia->delator);//não precisa ser verificado, caso nulo a procedure colocara um valor padrao
                $st->bindParam(3, $denuncia->descricao_local);
                $st->execute();
                return $st->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        }

        public function all()
        {
            return Conn::getConn()->query("select * from denuncias")->fetchAll(PDO::FETCH_ASSOC);
        }

        public function find($id)
        {
            return Conn::getConn()->query("select * from denuncias where id=".$id)->fetch(PDO::FETCH_ASSOC);
        }

        public function trash( $id )
        {
            $imagens = Conn::getConn()->query("select nome_imagem from imagens where flag='denunciada' and id_foreign=".$id)->fetchAll(PDO::FETCH_ASSOC);
            if (Conn::getConn()->query("delete from denuncias where id=".$id) == true)//tem uma trigger no mysql que remove os registro 
            {                                                                       //na tabela imagens_denuncias relacinado ao id da denuncia
                foreach ($imagens as $img)
                {
                    unlink("./app/client/assets/imagens/denunciadas/".$img["nome_imagem"]);
                }
                return true;
            }
            
            return false;
        }
    }