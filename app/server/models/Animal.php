<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use app\server\models\ImagemAnimal;
    use PDO;

    class Animal {

        //recebe um objeto com os dados do animal de insere no banco
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
            return Conn::getConn()->query("select * from animais where adotado=0")->fetchAll(PDO::FETCH_ASSOC);
        }

		//retorna animal pelo $ID
        public function findById($id) 
        {
            return Conn::getConn()->query("select * from animais where id=".$id)->fetch(PDO::FETCH_ASSOC);
        }
		
        //retorna animal pelo $nome
        public function find($nome) 
        {
            return Conn::getConn()->query("select * from animais where adotado=0 and nome like '".$nome."%' limit 5 ")->fetchAll(PDO::FETCH_ASSOC);
        }

        //atualiza dados do animal 
        public function update( $animal )
        {
            //valida os campos obrigatórios antes
            if( $animal->id <> "" and $animal->nome <> "" and $animal->descricao <> "" and $animal->raca <> "" and $animal->cor <> "" and $animal->idade <> "" and $animal->sexo <> "" )
            {
                $st = Conn::getConn()->prepare("UPDATE animais SET nome=?, descricao=?, raca=?, cor=?, idade=?, sexo=?, adotado=? WHERE id=?");

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
            $imagens = Conn::getConn()->query("select nome_imagem from imagens where flag='animal' and id_foreign=".$id)->fetchAll(PDO::FETCH_ASSOC);
            $st = Conn::getConn()->prepare("call delete_animais(?)");
            $st->bindParam(1, $id);
            $st->execute();
            if ($st->rowCount() > 0)//tem uma trigger no mysql que remove os registro na tabela imagens_animais relacinado ao id do animal
            {                                                                       
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
            $result = $st->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();

            if($st->rowCount() > 0)
            {
                $imgAnimal = new ImagemAnimal();

                $return = array();

                foreach($result as $res)
                {
                    $img = $imgAnimal->find( $res['id'] );
                    isset($img[0]['nome_imagem']) ? $res['imagem'] = $img[0]['nome_imagem'] : $res['imagem'] = "";
                    $return[] = $res;
                }
                return $return;
                
            }

            return [];
        }

    }