DELIMITER $
CREATE TRIGGER animais_delete before delete 
on animais
FOR EACH ROW
BEGIN
    delete from imagens where id_foreign = old.id and flag="animal";
    
END$

DELIMITER ;

DELIMITER $
CREATE TRIGGER denuncias_delete before delete 
on denuncias
FOR EACH ROW
BEGIN
    delete from imagens where id_foreign = old.id and flag="denunciada";
    
END$
DELIMITER ;

DELIMITER $
CREATE TRIGGER adocao_insere after insert 
on adocoes
FOR EACH ROW
BEGIN
    update animais set adotado="true" where id = new.id_animal;
END$
DELIMITER ;

DELIMITER $
CREATE TRIGGER adocao_delete before delete 
on adocoes
FOR EACH ROW
BEGIN
    delete from mensagens_adocoes where id_adocao = old.id;
    update animais set adotado="false" where id = old.id_animal;
END$
DELIMITER ;

DELIMITER $
CREATE TRIGGER associado_delete before delete 
on Associados
FOR EACH ROW
BEGIN
    delete from adocoes where id_associado = old.id;
    delete from enderecos where id_associado = old.id;
    delete from telefones where id_associado = old.id;
    
END$
DELIMITER ;