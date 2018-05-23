/* Procedure Animais */
DELIMITER $$
CREATE PROCEDURE inserir_animais(IN nome VARCHAR(30),
                                 IN descricao TEXT,
                               	 IN raca VARCHAR(35),
                               	 IN cor VARCHAR(25),
                               	 IN idade INTEGER,
                               	 IN sexo VARCHAR(5),
                                 IN adotado VARCHAR(5))
BEGIN

  SET @var_nome = nome;
  SET @var_descricao = descricao;
  SET @var_raca = raca;
  SET @var_cor = cor;
  SET @var_idade = idade;
  SET @var_sexo = sexo;
  SET @var_adotado = adotado;
  SET @var_id_animal = 0;

  IF (nome = "") THEN SET @var_nome = "Sem Nome"; END IF;
  IF(raca = "") THEN SET @var_raca = "SRD"; END IF;
  IF (idade = "") THEN SET @var_idade = NULL; END IF;
  IF (adotado = "") THEN SET @var_adotado = 0; END IF;

  INSERT INTO animais(nome, descricao, raca, cor, idade, sexo, adotado)
  VALUES(@var_nome, @var_descricao, @var_raca, @var_cor, @var_idade, @var_sexo, @var_adotado);

  SELECT LAST_INSERT_ID() INTO @var_id_animal;

  SELECT * from animais where id = @var_id_animal;

  COMMIT WORK;

END $$
DELIMITER ;

/* Procedure Selecionar animais por filtro*/
DELIMITER $$
CREATE PROCEDURE filtro_animais(IN cor VARCHAR(25),
                               IN idademin INTEGER,
                               IN idademax INTEGER,
                               IN sexo VARCHAR(5))
BEGIN

  select 
    a.*,
    i.nome_imagem
  from 
    animais a
  inner join imagens_animais i on a.id = i.id_animal
  where a.cor = cor and (a.idade between idademin and idademax) and a.sexo = sexo;

END $$
DELIMITER ;

/* Procedure Denuncias */
DELIMITER $$
CREATE PROCEDURE inserir_denuncias(IN descricao TEXT,
                                   IN delator VARCHAR(75),
                                   IN descricao_local VARCHAR(150))
BEGIN

  SET @var_delator = delator;
  SET @var_id_denuncia = 0;  

  IF(delator = "") THEN SET @var_delator = "Anônimo"; END IF;

  INSERT INTO denuncias(descricao, delator, descricao_local)
  VALUES(descricao, @var_delator, descricao_local);

  SELECT LAST_INSERT_ID() INTO @var_id_denuncia;

  SELECT * from denuncias where id = @var_id_denuncia;

  COMMIT WORK;

END $$
DELIMITER ;

/* Procedure Imagens */
DELIMITER $$
CREATE PROCEDURE inserir_imagens(IN nome_imagem TEXT,
                                 IN id_foreign INTEGER,
                                 IN flag VARCHAR(15))
BEGIN

  INSERT INTO imagens(nome_imagem, id_foreign, flag)
  VALUES(nome_imagem, id_foreign, flag);

  COMMIT WORK;

END $$
DELIMITER ;

/* Procedure Associados */
DELIMITER $$
CREATE PROCEDURE inserir_associados(IN nome VARCHAR(80),
                                    IN sexo VARCHAR(10),
                                    IN email VARCHAR(70),
                                    IN pass VARCHAR(50),
                                    IN logradouro VARCHAR(150),
                                    IN numero VARCHAR(50),
                                    IN complemento VARCHAR(150),
                                    IN bairro VARCHAR(100),
                                    IN cep VARCHAR(10),
                                    IN cidade VARCHAR(100),
                                    IN estado CHAR(2))
BEGIN

  SET @var_email = email;
  SET @var_numero = numero;
  SET @var_complemento = complemento;
  SET @var_id_associado = 0;

  IF(email = "") THEN SET @var_email = NULL; END IF;
  IF(numero = "") THEN SET @var_numero = "S/n"; END IF;
  IF(complemento = "") THEN SET @var_complemento = NULL; END IF;

  INSERT INTO associados(nome, sexo, email, pass)
  VALUES(nome, sexo, @var_email, pass);

  SELECT LAST_INSERT_ID() INTO @var_id_associado;

  INSERT INTO enderecos(id_associado, logradouro, numero, complemento, bairro, cep, cidade, estado)
  VALUES(@var_id_associado, logradouro, @var_numero, @var_complemento, bairro, cep, cidade, estado);

  SELECT id from associados WHERE id = @var_id_associado;

  COMMIT WORK;

END $$
DELIMITER ;

/* Procedure Telefones */
DELIMITER $$
CREATE PROCEDURE inserir_telefones(IN id_associado INTEGER,
                                   IN numero VARCHAR(15),
                                   IN tipo VARCHAR(15))
BEGIN

  INSERT INTO telefones(id_associado, numero, tipo)
  VALUES(id_associado, numero, tipo);

  COMMIT WORK;

END $$
DELIMITER ;

/* Procedure atualizar Associados */
DELIMITER $$
CREATE PROCEDURE update_associados(IN id_associado INTEGER,
                                    IN nome VARCHAR(80),
                                    IN sexo VARCHAR(10),
                                    IN email VARCHAR(70),
                                    IN pass VARCHAR(50),
                                    IN id_endereco INTEGER,
                                    IN logradouro VARCHAR(150),
                                    IN numero VARCHAR(50),
                                    IN complemento VARCHAR(150),
                                    IN bairro VARCHAR(100),
                                    IN cep VARCHAR(10),
                                    IN cidade VARCHAR(100),
                                    IN estado CHAR(2))
BEGIN

  IF(pass = "d41d8cd98f00b204e9800998ecf8427e" or pass = "") THEN
    UPDATE associados SET nome=nome, sexo=sexo, email=email WHERE id=id_associado;  
  ELSE    
    UPDATE associados SET nome=nome, sexo=sexo, email=email, pass=pass WHERE id=id_associado;
  END IF;

  UPDATE enderecos SET logradouro=logradouro, numero=numero, complemento=complemento, bairro=bairro, cep=cep, cidade=cidade, estado=estado WHERE id = id_endereco; 

  COMMIT WORK;

END $$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE delete_animais(IN id_animal INTEGER)
BEGIN

  update animais set adotado=0 where id = id_animal;
  delete from animais where id = id_animal;

  COMMIT WORK;
END $$
DELIMITER ;

/* Procedure Mensagens */
DELIMITER $$
CREATE PROCEDURE inserir_mesagens_adocao(IN id_adocao INTEGER,
                                   IN mensagem TEXT,
                                   IN remetente VARCHAR(100),
                                   IN datahora datetime)
BEGIN
  SET @var_remetente = remetente;
  SET @var_dthora = datahora;
  IF(remetente = "") THEN SET @var_remetente = "admin"; END IF;
  IF(datahora = "") THEN SET @var_dthora = NOW(); END IF;

  INSERT INTO mensagens_adocoes(id_adocao, mensagem, remetente, datahora)
  VALUES(id_adocao, mensagem, @var_remetente, @var_dthora);

  COMMIT WORK;

END $$
DELIMITER ;