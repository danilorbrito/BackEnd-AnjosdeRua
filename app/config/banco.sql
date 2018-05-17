/*

BANCO DE DADOS: anjos_de_rua

TABELAS:

Admin
Animais
Imagens_Animais (VINCULADA AOS ANIMAIS)
Associados
Enderecos
Telefones
Adocoes
Mensagens_Adocoes (VINCULADA AS ADOCOES)
Denuncias
Imagens_Denuncias (VINCULADA AS DENUNCIAS)

*/

/*CRIAÇÃO DO BANCO*/
CREATE DATABASE IF NOT EXISTS anjos_de_rua 
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

/*BANCO EM USO*/
USE anjos_de_rua;

/* TABELA ADMIN */
CREATE TABLE admin(
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	nome VARCHAR(75) NOT NULL,
	login VARCHAR(50) NOT NULL,
	pass VARCHAR(50) NOT NULL,
	CONSTRAINT pk_01_admin PRIMARY KEY (id)
);


/* TABELA ANIMAIS */
CREATE TABLE animais(
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	nome VARCHAR(30) DEFAULT 'Sem Nome',
	descricao TEXT NOT NULL,
	raca VARCHAR(35) DEFAULT 'SRD',
	cor VARCHAR(25) NOT NULL,
	idade INTEGER,
	sexo VARCHAR(5) NOT NULL,
	adotado BOOLEAN NOT NULL,
	CONSTRAINT pk_01_animais PRIMARY KEY (id)
);

/* TABELA ASSOCIADOS */
CREATE TABLE associados(
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	nome VARCHAR(80) NOT NULL,
	sexo VARCHAR(10) NOT NULL,
	email VARCHAR(70),
	pass VARCHAR(50) NOT NULL,
	CONSTRAINT pk_01_associados PRIMARY KEY (id)
);

/* TABELA ENDERECOS */
CREATE TABLE enderecos(
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	id_associado INTEGER UNSIGNED NOT NULL,
	logradouro VARCHAR(150) NOT NULL,
	numero VARCHAR(50) DEFAULT 'S/n',
	complemento VARCHAR(150),
	bairro VARCHAR(100) NOT NULL,
	cep VARCHAR(10) NOT NULL,
	cidade VARCHAR(100) NOT NULL,
	estado CHAR(2) NOT NULL,
	CONSTRAINT pk_01_enderecos PRIMARY KEY (id),
	CONSTRAINT fk_01_enderecos_associados FOREIGN KEY (id_associado) REFERENCES associados (id)
);

/* TABELA TELEFONES */
CREATE TABLE telefones(
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	id_associado INTEGER UNSIGNED NOT NULL,
	numero VARCHAR(15) NOT NULL,
	tipo VARCHAR(15) NOT NULL,
	CONSTRAINT pk_01_telefones PRIMARY KEY (id),
	CONSTRAINT fk_01_telefones_associados FOREIGN KEY (id_associado) REFERENCES associados (id)
);

/* TABELA ADOCOES */
CREATE TABLE adocoes(
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	id_animal INTEGER UNSIGNED NOT NULL,
	id_associado INTEGER UNSIGNED NOT NULL,
	datahora DATETIME NOT NULL DEFAULT NOW(),
	CONSTRAINT pk_01_adocoes PRIMARY KEY (id),
	CONSTRAINT fk_01_adocoes_animais FOREIGN KEY (id_animal) REFERENCES animais (id),
	CONSTRAINT fk_02_adocoes_associados FOREIGN KEY (id_associado) REFERENCES associados (id)
);

/* TABELA MENSAGENS_ADOCOES (VINCULADA AS ADOCOES) */
CREATE TABLE mensagens_adocoes(
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	id_adocao INTEGER UNSIGNED NOT NULL,
	mensagem TEXT NOT NULL,
	remetente VARCHAR(22) NOT NULL DEFAULT "Anjos De Rua - Adoções",
	datahora DATETIME NOT NULL DEFAULT NOW(),
	CONSTRAINT pk_01_mensagensAdocoes PRIMARY KEY (id),
	CONSTRAINT fk_01_mensagensAdocoes_adocoes FOREIGN KEY (id_adocao) REFERENCES adocoes (id)
);

/* TABELA DENUNCIAS */
CREATE TABLE denuncias(
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	descricao TEXT NOT NULL,
	delator VARCHAR(75),
	descricao_local VARCHAR(150) NOT NULL,
	dt_denuncia DATETIME NOT NULL DEFAULT NOW(),
	CONSTRAINT pk_01_denuncias PRIMARY KEY (id)
);

/*Tabela de imagens para animais e também para animais denunciados*/
CREATE TABLE imagens(
  id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  nome_imagem TEXT NOT NULL,
  id_foreign INTEGER NOT NULL,
  flag VARCHAR(15) NOT NULL,
  CONSTRAINT pk_imagem PRIMARY KEY (id)
);

/*tabela para ações promovidas pela instituição*/
CREATE TABLE acoes_promovidas(
	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	titulo VARCHAR(100),
	texto LONGTEXT,
	CONSTRAINT pk_acoes PRIMARY KEY(id)
);
