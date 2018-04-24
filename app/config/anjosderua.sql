
create table admin(
	id int primary key auto_increment,
	nome varchar(100),
	login varchar(10),
	pass varchar(10)
);


create table animais(
	id int primary key auto_increment,
	nome varchar(100),
	descricao varchar(300),
	raca varchar(50),
	cor varchar(30),
	idade int,
	sexo varchar(1),
	imagem varchar(200)
);

create table associados(
	id int primary key auto_increment,
	nome varchar(100),
	sexo varchar(1),
	email varchar(100),
	pass varchar(10)
);






create table enderecos(
	id int primary key auto_increment,
	idassociado int,
	logradouro varchar(50),
	numero varchar(5),
	complemento varchar(50),
	bairro varchar(50),
	cep varchar(50),
	cidade varchar(50),
	estado varchar(2)
);

ALTER TABLE `enderecos` ADD CONSTRAINT `fk_endereco_associado` 
FOREIGN KEY ( `idassociado` ) REFERENCES `associados`( `id` );





create table telefones(
	id int primary key auto_increment,
	idassociado int,
	numero varchar(50)
);
ALTER TABLE `telefones` ADD CONSTRAINT `fk_telefone_associado` 
FOREIGN KEY ( `idassociado` ) REFERENCES `associados`( `id` );



create table adocoes(
	id int primary key auto_increment,
	idanimal int,
	idassociado int,
	data varchar(10)
);
ALTER TABLE `adocoes` ADD CONSTRAINT `fk_adocao_animal` 
FOREIGN KEY ( `idanimal` ) REFERENCES `animais`( `id` );

ALTER TABLE `adocoes` ADD CONSTRAINT `fk_adocao_associado` 
FOREIGN KEY ( `idassociado` ) REFERENCES `idassociados`( `id` );



create table mensagens(
	id int primary key auto_increment,
	idadocao int,
	mensagem varchar(100),
	datahora varchar(20),
	remetente varchar(50)
);
ALTER TABLE `mensagens` ADD CONSTRAINT `fk_mensagem_adocao` 
FOREIGN KEY ( `idadocao` ) REFERENCES `adocoes`( `id` );



create table denuncias(
	id int primary key auto_increment,
	descricao varchar(200),
	delator varchar(100),
	datahora varchar(20),
	descricaolocal varchar(300)
);
