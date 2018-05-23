<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Mensagem {
        //recebe um array com os dados da mensagem que insere no banco
        public function save( $mensagem )
        {
            //valida os campos obrigatórios antes
            if( $mensagem->id_adocao <> "" and $mensagem->mensagem <> "" )
            {
                $st = Conn::getConn()->prepare("call inserir_mesagens_adocao(?,?,?,?)");
                $st->bindParam(1, $mensagem->id_adocao);
                $st->bindParam(2, $mensagem->mensagem);
                $st->bindParam(3, $mensagem->remetente);//não precisa ser verificado, a procedure vai colocar um valor padrão
                $st->bindParam(4, $mensagem->datahora);//não precisa ser verificado, a procedure vai colocar um valor padrão
                return $st->execute();
            }
            else
                return false;
        }

        //retorna todas as mensagens
        public function all() 
        {
            return Conn::getConn()->query("SELECT * FROM mensagens_adocoes")->fetchAll(PDO::FETCH_ASSOC);
        }

        //retorna todas as mensagens
        public function findByAdocao($id) 
        {
            $st = Conn::getConn()->prepare("SELECT * FROM mensagens_adocoes WHERE id_adocao=?");
            $st->bindParam(1, $id);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        //modifica o status da mensagem
        public function modifyStatus( $obj )
        {   
            if($obj->id_adocao <> "")
            {
                $st = Conn::getConn()->prepare("update mensagens_adocoes set lida=1 where id_adocao=?");
                $st->bindParam(1, $obj->id_adocao);
                return $st->execute();
            }
            return false;
        }

        public function messagesForAdmin( $id_adocao ) {
            return Conn::getConn()->query("call select_mensagens_admin(".$id_adocao.")")->fetch(PDO::FETCH_ASSOC);
        }

        public function messagesForAssociado( $id_associado ) {
            $st = Conn::getConn()->prepare("call select_mensagens_assoc(?)");
            $st->bindParam(1, $id_associado);
            $st->execute();
            if($st->rowCount() > 0)
            {
                $retorno = array();
                foreach($st->fetchAll(PDO::FETCH_ASSOC) as $item)
                    if($item['total'] > 0) $retorno[] = $item;
                
                return $retorno;
            }
            return [];
        }
    }