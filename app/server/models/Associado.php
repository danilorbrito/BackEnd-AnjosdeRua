<?php namespace app\server\models;

    use app\server\controllers\Conn;
    use PDO;

    class Associado {

        //recebe um array com os dados do associado que insere no banco
        public function save( $associado )
        {
            //valida os campos obrigatórios antes
            if($associado->nome <> "" and $associado->sexo <> "" and $associado->pass <> "" and
               $associado->endereco->logradouro <> "" and $associado->endereco->bairro <> "" and $associado->endereco->cep <> "" and
               $associado->endereco->cidade <> "" and $associado->endereco->estado <> "")
            {
                
                $hashMd5 = md5($associado->pass);
                $st = Conn::getConn()->prepare("call inserir_associados(?,?,?,?,?,?,?,?,?,?,?)");
                $st->bindParam(1, $associado->nome);
                $st->bindParam(2, $associado->sexo);
                $st->bindParam(3, $associado->email);//não precisa ser verificado, caso nulo a procedure colocara um valor padrao
                $st->bindParam(4, $hashMd5);
                $st->bindParam(5, $associado->endereco->logradouro);
                $st->bindParam(6, $associado->endereco->numero);//não precisa ser verificado, caso nulo a procedure colocara um valor padrao
                $st->bindParam(7, $associado->endereco->complemento);//não precisa ser verificado, caso nulo a procedure colocara um valor padrao
                $st->bindParam(8, $associado->endereco->bairro);
                $st->bindParam(9, $associado->endereco->cep);
                $st->bindParam(10, $associado->endereco->cidade);
                $st->bindParam(11, $associado->endereco->estado);
                
                if($st->execute() == true)
                {               
                    $id =  $st->fetch(PDO::FETCH_ASSOC);
                    $id = $id["id"];                
                    $st->closeCursor();

                    foreach($associado->telefones as $tel) self::inserirTel($id, $tel->numero, $tel->tipo); 

                    return true;
                }
                return false;
            }
            return false;
        }

        public function inserirTel($id, $numero, $tipo)
        {
            $st02 = Conn::getConn()->prepare("call inserir_telefones(?,?,?)");
            $st02->bindParam(1, $id);
            $st02->bindParam(2, $numero);
            $st02->bindParam(3, $tipo);
            $st02->execute();
        }

        public function buscarTel($id_associado)
        {
            $st = Conn::getConn()->query("select id, numero, tipo from telefones where id_associado=".$id_associado);
            return $st->fetchAll(PDO::FETCH_ASSOC);
        }

        public function buscarEnd($id_associado)
        {
            $st = Conn::getConn()->query("select id, logradouro, numero, complemento, bairro, cep, cidade, estado from enderecos where id_associado=".$id_associado);
            return $st->fetch(PDO::FETCH_ASSOC);
        }

        //retorna todos os associados
        public function all() 
        {
            $st = Conn::getConn()->query("select * from associados");
            $result = $st->fetchAll(PDO::FETCH_ASSOC);
            $st->closeCursor();
            if($result == true)
            {
                $retorno = array();
                foreach($result as $res)
                {
                    $res['endereco'] = self::buscarEnd($res['id']);
                    $res['telefones'] = self::buscarTel($res['id']);
                    unset($res['pass']);
                    $retorno[] = $res;
                }
                return $retorno;
            }
            return [];
        }

        //retorna associado pelo id
        public function find($id) 
        {
            $st = Conn::getConn()->query("select * from associados where id=".$id);
            $result = $st->fetch(PDO::FETCH_ASSOC);
            $st->closeCursor();
            if($result == true)
            {   
                $result['endereco'] = self::buscarEnd($id);
                $result['telefones'] = self::buscarTel($id);
                unset($result['pass']);
                return $result;
            }
            return false;
        }

        //atualiza dados do associado 
        public function update( $associado )
        {   
            
            //valida os campos obrigatórios antes
            if($associado->id <> "" and $associado->nome <> "" and $associado->sexo <> "" and $associado->endereco->id <> "" and
               $associado->endereco->logradouro <> "" and $associado->endereco->bairro <> "" and $associado->endereco->cep <> "" and
               $associado->endereco->cidade <> "" and $associado->endereco->estado <> "")
            {
                $hashMd5 = md5($associado->pass);
                $st = Conn::getConn()->prepare("call update_associados(?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $st->bindParam(1, $associado->id);
                $st->bindParam(2, $associado->nome);
                $st->bindParam(3, $associado->sexo);
                $st->bindParam(4, $associado->email);
                $st->bindParam(5, $hashMd5);//não preisa ser verificado, a procedure vai consevar a mesma caso o campo pass esteja vazio
                $st->bindParam(6, $associado->endereco->id);
                $st->bindParam(7, $associado->endereco->logradouro);
                $st->bindParam(8, $associado->endereco->numero);
                $st->bindParam(9, $associado->endereco->complemento);
                $st->bindParam(10, $associado->endereco->bairro);
                $st->bindParam(11, $associado->endereco->cep);
                $st->bindParam(12, $associado->endereco->cidade);
                $st->bindParam(13, $associado->endereco->estado);
                
                if($st->execute() == true)
                { 
                    foreach($associado->telefones as $tel)
                    {
                        if($tel->id <> "")
                        {
                            if($tel->numero <> "")
                            {
                                $st02 = Conn::getConn()->prepare("update telefones set numero=?, tipo=? where id=?");
                                $st02->bindParam(1, $tel->numero);
                                $st02->bindParam(2, $tel->tipo);
                                $st02->bindParam(3, $tel->id);
                                $st02->execute();
                            }
                            else
                                $st02 = Conn::getConn()->query("delete from telefones where id=".$tel->id);
                        }
                        else
                            self::inserirTel($associado->id, $tel->numero, $tel->tipo);
                    }

                    return true;
                }
                return false;
            }
            
            return false;
        }

        //deleta associado pelo id
        public function trash( $id )
        {
            $st = Conn::getConn()->prepare("DELETE FROM associados WHERE id=?");
            $st->bindParam(1, $id);
            $st->execute();
            if($st->rowCount() > 0)
                return true;
            return false;
        }

    }