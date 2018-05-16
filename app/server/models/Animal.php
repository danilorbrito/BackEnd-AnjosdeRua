<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Animal {

        //recebe um array com os dados do animal de insere no banco
        public function save( $animal )
        {
            //valida os campos obrigatórios antes
            if( $animal->descricao <> "" and $animal->cor <> "" and $animal->sexo <> "" )
            {
                $st = Conn::getConn()->prepare("call inserir_animais(?,?,?,?,?,?,?)");//essa procedure adiciona o nome da imagem do animal na tabela imagens_animais
                $st->bindParam(1, $animal->nome);//não precisa ser verificado, caso nulo a procedure colocara um valor padrao                                     
                $st->bindParam(2, $animal->descricao);
                $st->bindParam(3, $animal->raca);//não precisa ser verificado, caso nulo a procedure colocara um valor padrao
                $st->bindParam(4, $animal->cor);
                $st->bindParam(5, $animal->idade);//não precisa ser verificado, caso nulo a procedure colocara um valor padrao
                $st->bindParam(6, $animal->sexo);
                $st->bindParam(7, $animal->adotado);//não precisa ser verificado, caso nulo a procedure colocara um valor padrao
                $st->execute();
                return $st->fetch(PDO::FETCH_ASSOC);
            }
            else
                return false;
        }

        //retorna todos os animais
        public function all() 
        {
            return Conn::getConn()->query("select * from animais")->fetchAll(PDO::FETCH_ASSOC);
        }

        //retorna animal pelo id
        public function find($id) 
        {
            return Conn::getConn()->query("select * from animais where id=".$id)->fetch(PDO::FETCH_ASSOC);
            
        }

        //atualiza dados do animal 
        public function update( $animal )
        {
            //valida os campos obrigatórios antes
            if( $animal->id <> "" and $animal->nome <> "" and $animal->descricao <> "" and $animal->raca <> "" and $animal->cor <> "" and $animal->idade <> "" and $animal->sexo <> "" )
            {
                $st = Conn::getConn()->prepare(" UPDATE animais SET nome=?, descricao=?, raca=?, cor=?, idade=?, sexo=?, adotado=? WHERE id=? ");

                $st->bindParam(1, $animal->nome);
                $st->bindParam(2, $animal->descricao);
                $st->bindParam(3, $animal->raca);
                $st->bindParam(4, $animal->cor);
                $st->bindParam(5, $animal->idade);
                $st->bindParam(6, $animal->sexo);
                $st->bindParam(7, $animal->adotado);
                $st->bindParam(8, $animal->id);
                return $st->execute();
            }
            else
                return false;
        }

        //deleta animal pelo id
        public function trash( $id )
        {
            $imagens = Conn::getConn()->query("select nome_imagem from Imagens where flag='animal' and id_foreign=".$id)->fetchAll(PDO::FETCH_ASSOC);
            if (Conn::getConn()->query("delete from animais where id=".$id) == true)//tem uma trigger no mysql que remove os registro 
            {                                                                       //na tabela imagens_animais relacinado ao id do animal
                foreach ($imagens as $img)
                {
                    unlink("./app/client/assets/imagens/animais/".$img["nome_imagem"]);
                }
                return true;
            }
            
            return false;
        }

        public function filtro( $params ) {
            $st = Conn::getConn()->prepare("call filtro_animais(?,?,?,?)");
            $st->bindParam(1, $params->cor);
            $st->bindParam(2, $params->idademin);
            $st->bindParam(3, $params->idademax);
            $st->bindParam(4, $params->sexo);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

    }