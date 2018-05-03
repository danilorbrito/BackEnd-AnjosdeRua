<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Animal {

        //recebe um array com os dados do animal de insere no banco
        public function save( $nameImage, $animal )
        {
            $animal = (object) $animal;

            //valida os campos obrigatórios antes
            if( $animal->nome <> "" and $animal->descricao <> "" and $animal->raca <> "" and $animal->cor <> "" and $animal->idade <> "" and $animal->sexo <> "" and $nameImage <> "" )
            {
                $st = Conn::getConn()->prepare("INSERT INTO animais VALUES (NULL, ?, ?, ?, ?, ?, ?, ?)");
                $st->bindParam(1, $animal->nome);
                $st->bindParam(2, $animal->descricao);
                $st->bindParam(3, $animal->raca);
                $st->bindParam(4, $animal->cor);
                $st->bindParam(5, $animal->idade);
                $st->bindParam(6, $animal->sexo);
                $st->bindParam(7, $nameImage);
                return $st->execute();
            }
            else
                return false;
        }

        //retorna todos os animais
        public function all() 
        {
            return Conn::getConn()->query("SELECT * FROM animais")->fetchAll(PDO::FETCH_ASSOC);
        }

        //retorna animal pelo id
        public function find($id) 
        {
            $st = Conn::getConn()->prepare(" SELECT * FROM animais WHERE id=? ");
            $st->bindParam(1, $id);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        //atualiza dados do animal 
        public function update( $animal )
        {
            //valida os campos obrigatórios antes
            if( $animal->id <> "" and $animal->nome <> "" and $animal->descricao <> "" and $animal->raca <> "" and $animal->cor <> "" and $animal->idade <> "" and $animal->sexo <> "" and $animal->imagem <> "" )
            {
                $st = Conn::getConn()->prepare(" UPDATE animais SET nome=?, descricao=?, raca=?, cor=?, idade=?, sexo=?, imagem=? WHERE id=? ");

                $st->bindParam(1, $animal->nome);
                $st->bindParam(2, $animal->descricao);
                $st->bindParam(3, $animal->raca);
                $st->bindParam(4, $animal->cor);
                $st->bindParam(5, $animal->idade);
                $st->bindParam(6, $animal->sexo);
                $st->bindParam(7, $animal->imagem);
                $st->bindParam(8, $animal->id);
                return $st->execute();
            }
            else
                return false;
        }

        //deleta animal pelo id
        public function trash( $id )
        {
            $imagem = Conn::getConn()->query("select imagem from animais where id=".$id)->fetch(PDO::FETCH_ASSOC);
            return (Conn::getConn()->query("delete from animais where id=".$id) == true) and (unlink("./app/client/assets/imagens/animais/".$imagem['imagem']));
        }

        public function filtro( $params ) {
            $st = Conn::getConn()->prepare("SELECT * FROM animais WHERE cor=? and (idade between ? and ?) and sexo=?");
            $st->bindParam(1, $params->cor);
            $st->bindParam(2, $params->idademin);
            $st->bindParam(3, $params->idademax);
            $st->bindParam(4, $params->sexo);
            $st->execute();
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

    }