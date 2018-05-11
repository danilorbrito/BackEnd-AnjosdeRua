/* Procedure Animais */
DELIMITER $$
CREATE PROCEDURE inserir_animais(IN nome VARCHAR(30),
                                 IN descricao TEXT,
                               	 IN raca VARCHAR(35),
                               	 IN cor VARCHAR(25),
                               	 IN idade INTEGER,
                               	 IN sexo VARCHAR(5))
BEGIN

  SET @var_nome = nome;
  SET @var_descricao = descricao;
  SET @var_raca = raca;
  SET @var_cor = cor;
  SET @var_idade = idade;
  SET @var_sexo = sexo;
  SET @var_id_animal = 0;

  IF (nome = "") THEN SET @var_nome = "Sem Nome"; END IF;
  IF(raca = "") THEN SET @var_raca = "SRD"; END IF;
  IF (idade = "") THEN SET @var_idade = NULL; END IF;

  INSERT INTO Animais(nome, descricao, raca, cor, idade, sexo)
  VALUES(@var_nome, @var_descricao, @var_raca, @var_cor, @var_idade, @var_sexo);

  SELECT LAST_INSERT_ID() INTO @var_id_animal;

  SELECT * from animais where id = @var_id_animal;

  COMMIT WORK;

END $$
DELIMITER ;

/* Procedure Imagens_Animais (VINCULADA AOS ANIMAIS) 
DELIMITER $$
CREATE PROCEDURE inserir_imagens_animais(IN id_animal INTEGER,
                                         IN nome_imagem TEXT)
BEGIN

  SET @var_id_animal = id_animal;
  SET @var_nome_imagem = nome_imagem;

  INSERT INTO Imagens_Animais(id_animal, nome_imagem)
  VALUES(@var_id_animal, @var_nome_imagem);

  COMMIT WORK;

END $$
DELIMITER ; */

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

  INSERT INTO Denuncias(descricao, delator, descricao_local)
  VALUES(descricao, @var_delator, descricao_local);

  SELECT LAST_INSERT_ID() INTO @var_id_denuncia;

  SELECT * from Denuncias where id = @var_id_denuncia;

  COMMIT WORK;

END $$
DELIMITER ;

/* Procedure Imagens_Denuncias (VINCULADA AS DENUNCIAS) 
DELIMITER $$
CREATE PROCEDURE inserir_imagens_denuncias(IN id_denuncia INTEGER,
                                           IN nome_imagem TEXT)
BEGIN

  SET @var_id_denuncia = id_denuncia;
  SET @var_nome_imagem = nome_imagem;

  INSERT INTO Imagens_Denuncias(id_denuncia, nome_imagem)
  VALUES(@var_id_denuncia, @var_nome_imagem);

  COMMIT WORK;

END $$
DELIMITER ; */

/* Procedure Imagens */
DELIMITER $$
CREATE PROCEDURE inserir_imagens(IN nome_imagem TEXT,
                                 IN id_foreign INTEGER,
                                 IN flag VARCHAR(15))
BEGIN

  INSERT INTO Imagens(nome_imagem, id_foreign, flag)
  VALUES(nome_imagem, id_foreign, flag);

  COMMIT WORK;

END $$
DELIMITER ;
