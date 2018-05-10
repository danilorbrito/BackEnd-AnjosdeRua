<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Denuncia {

        //recebe um array com os dados da denuncia de insere no banco
        public function save( $nameImagem, $denuncia )
        {
            $denuncia = (object) $denuncia;

            //valida os campos obrigatórios antes
            if( $denuncia->descricao <> "" and $denuncia->descricao_local <> "" and $nameImagem <> "")
            {
                $dateTime = Date("Y-m-d H:i:s");
                $st = Conn::getConn()->prepare("call inserir_denuncias(?,?,?,?,?)");
                $st->bindParam(1, $denuncia->descricao);
                $st->bindParam(2, $denuncia->delator);//não precisa de verificação pois a procedure registrará Anônimo caso essa variável esteja vazia
                $st->bindParam(3, $denuncia->descricao_local);
                $st->bindParam(4, $dateTime);
                $st->bindParam(5, $nameImagem);
                return $st->execute();
            }
            else
                return false;
        }

        //retorna todas as denuncias
        public function all() 
        {
            return Conn::getConn()->query("call todas_denuncias()")->fetchAll(PDO::FETCH_ASSOC);
        }

        //retorna denuncia pelo id
        public function find($id) 
        {
            $st = Conn::getConn()->prepare("call selecionar_denuncia(?)");
            $st->bindParam(1, $id);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        //deleta denuncia pelo id
        public function trash( $id )
        {
            $imagens = Conn::getConn()->query("select nome_imagem from Imagens_Denuncias where id_denuncia=".$id)->fetchAll(PDO::FETCH_ASSOC);
            if (Conn::getConn()->query("delete from Denuncias where id=".$id) == true)//tem uma trigger no mysql que remove os registro 
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