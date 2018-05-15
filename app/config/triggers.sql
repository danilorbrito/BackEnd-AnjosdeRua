DELIMITER $

CREATE TRIGGER Animais_delete before delete 
on Animais
FOR EACH ROW
BEGIN
    delete from Imagens where id_foreign = old.id and flag="animal";
    
END$

DELIMITER ;

DELIMITER $
CREATE TRIGGER Denuncias_delete before delete 
on Denuncias
FOR EACH ROW
BEGIN
    delete from Imagens where id_foreign = old.id and flag="denunciada";
    
END$
DELIMITER ;

DELIMITER $
CREATE TRIGGER Adocao_insere after insert 
on Adocoes
FOR EACH ROW
BEGIN
    update Animais set adotado="true" where id = new.id_animal;
END$
DELIMITER ;

DELIMITER $
CREATE TRIGGER Adocao_delete before delete 
on Adocoes
FOR EACH ROW
BEGIN
    delete from Mensagens_Adocoes where id_adocao = old.id;
    update Animais set adotado="false" where id = old.id_animal;
END$
DELIMITER ;

DELIMITER $
CREATE TRIGGER Associado_delete before delete 
on Associados
FOR EACH ROW
BEGIN
    delete from Adocoes where id_associado = old.id;
    delete from Enderecos where id_associado = old.id;
    delete from Telefones where id_associado = old.id;
    
END$
DELIMITER ;