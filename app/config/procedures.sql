/* Procedure Animais */
DELIMITER $$
CREATE PROCEDURE inserir_animais(IN nome VARCHAR(30),
                                 IN descricao TEXT,
                               	 IN raca VARCHAR(35),
                               	 IN cor VARCHAR(25),
                               	 IN idade INTEGER,
                               	 IN sexo VARCHAR(5),
                                 IN imagem TEXT)
BEGIN

  SET @var_nome = nome;
  SET @var_descricao = descricao;
  SET @var_raca = raca;
  SET @var_cor = cor;
  SET @var_idade = idade;
  SET @var_sexo = sexo;
  SET @var_imagem = imagem;
  SET @var_id_animal = "";

  IF (nome = "") THEN SET @var_nome = "Sem Nome"; END IF;
  IF(raca = "") THEN SET @var_raca = "SRD"; END IF;
  IF (idade = "") THEN SET @var_idade = NULL; END IF;

  INSERT INTO Animais(nome, descricao, raca, cor, idade, sexo)
  VALUES(@var_nome, @var_descricao, @var_raca, @var_cor, @var_idade, @var_sexo);

  SELECT LAST_INSERT_ID() INTO @var_id_animal;

  INSERT INTO Imagens_Animais(id_animal, nome_imagem)
  VALUES(@var_id_animal, @var_imagem);

  COMMIT WORK;

END $$
DELIMITER ;

/* Procedure Imagens_Animais (VINCULADA AOS ANIMAIS) */
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
DELIMITER ;

/* Procedure Selecionar todos animais*/
DELIMITER $$
CREATE PROCEDURE todos_animais()
BEGIN

  select 
    a.*,
    i.nome_imagem
  from 
    animais a
  inner join imagens_animais i on a.id = i.id_animal;

END $$
DELIMITER ;

/* Procedure Selecionar animal por ID*/
DELIMITER $$
CREATE PROCEDURE selecionar_animal(IN id INTEGER)
BEGIN

  select 
    a.*,
    i.nome_imagem
  from 
    animais a
  inner join imagens_animais i on a.id = i.id_animal
  where a.id = id;

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
                                   IN descricao_local VARCHAR(150),
                                   IN dt_denuncia DATETIME,
                                   IN nome_imagem VARCHAR(50))
BEGIN

  SET @var_delator = delator;
  SET @var_dt_denuncia = dt_denuncia;
  SET @var_id_denuncia = 0;  

  IF(delator = "") THEN SET @var_delator = "Anônimo"; END IF;
  IF(dt_denuncia = "") THEN SET @var_dt_denuncia = now(); END IF;

  INSERT INTO Denuncias(descricao, delator, descricao_local, dt_denuncia)
  VALUES(descricao, @var_delator, descricao_local, dt_denuncia);

  SELECT LAST_INSERT_ID() INTO @var_id_denuncia;

  INSERT INTO Imagens_Denuncias(id_denuncia, nome_imagem)
  VALUES(@var_id_denuncia, nome_imagem);

  COMMIT WORk;

END $$
DELIMITER ;

/* Procedure Imagens_Denuncias (VINCULADA AS DENUNCIAS) */
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
DELIMITER ;

/* Procedure Selecionar todas as denuncias*/
DELIMITER $$
CREATE PROCEDURE todas_denuncias()
BEGIN

  select 
    d.*,
    i.nome_imagem
  from 
    Denuncias d
  inner join Imagens_Denuncias i on d.id = i.id_denuncia;

END $$
DELIMITER ;

/* Procedure Selecionar denuncia por id*/
DELIMITER $$
CREATE PROCEDURE selecionar_denuncia(IN id INTEGER)
BEGIN

  select 
    d.*,
    i.nome_imagem
  from 
    Denuncias d
  inner join Imagens_Denuncias i on d.id = i.id_denuncia
  where d.id = id;

END $$
DELIMITER ;
