<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use app\server\models\Associado;
    use app\server\models\Animal;
    use PDO;

    class Adocao {

        //recebe um array com os dados da adocao que insere no banco
        public function save( $adocao )
        {
            //valida os campos obrigatórios antes
            if( $adocao->animal->id <> "" and $adocao->associado->id <> "" )
            {
                $dtAtual = date("Y-m-d H:i:s");
                $st = Conn::getConn()->prepare("insert into adocoes(id_animal,id_associado,datahora) values(?,?,?)");
                $st->bindParam(1, $adocao->animal->id);
                $st->bindParam(2, $adocao->associado->id);
                $st->bindParam(3, $dtAtual);
                return $st->execute();
                //uma trigger vai setar o campo adotado do animal como true
            }
            else
                return false;
        }

        public function arrayAdocao($linha)
        {
            $assoc = new Associado();
            $animal = new Animal();

            $linha['associado'] = $assoc->find($linha['id_associado']);
            $linha['animal'] = $animal->find($linha['id_animal']);
            unset($linha['id_animal']);
            unset($linha['id_associado']);
            return $linha;
        }

        //retorna todas as adocoes
        public function all() 
        {
            $st = Conn::getConn()->query("select * from adocoes");
            $result = $st->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();
            if($result == true)
            {
                $retorno = array();
                foreach($result as $res)
                {
                    $retorno[] = self::arrayAdocao($res);
                }
                return $retorno;
            }
            return [];
        }

        //retorna adocao pelo id
        public function find($id) 
        {
            $st = Conn::getConn()->query("SELECT * FROM adocoes WHERE id=".$id);
            $result = $st->fetch(PDO::FETCH_ASSOC);
            $st->closeCursor();
            if ($result == true)
            {
                return self::arrayAdocao($result);
            }
            return false;
        }

        //retorna adocao pelo idassociado
        public function findByAssociado($id) 
        {
            $st = Conn::getConn()->query("select an.id, an.nome, an.descricao, an.raca, an.cor, an.idade, an.sexo, an.adotado 
                                            FROM animais an
                                            inner join adocoes ad on an.id = ad.id_animal
                                            WHERE id_associado=".$id);
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        //atualiza dados da adocao 
        public function update( $adocao )
        {
            //valida os campos obrigatórios antes
            if( $adocao->id <> "" and $adocao->animal->id <> "" and $adocao->associado->id <> "" and $adocao->data <> "" )
            {
                $st = Conn::getConn()->prepare("UPDATE adocoes SET id_animal=?, id_associado=?, datahora=? WHERE id=?");
                $st->bindParam(1, $adocao->animal->id);
                $st->bindParam(2, $adocao->associado->id);
                $st->bindParam(3, $adocao->data);
                $st->bindParam(4, $adocao->id);
                if($st->rowCount() > 0)
                    return true;
                return false;
            }
            else
                return false;
        }

        //deleta adocao pelo id
        public function trash( $id )
        {
            $st = Conn::getConn()->prepare("DELETE FROM adocoes WHERE id=?");
            $st->bindParam(1, $id);
            $st->execute();
            if($st->rowCount() > 0)
                return true;
            return false;
            //uma trigger vai setar o campo adotado do animal como false
        }
    }