DELIMITER $
CREATE TRIGGER animais_delete before delete 
on animais
FOR EACH ROW
BEGIN

    delete from imagens where id_foreign = old.id and flag="animal";
    delete from adocoes where id_animal = old.id;

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
    update animais set adotado=1 where id = new.id_animal;
END$
DELIMITER ;

DELIMITER $
CREATE TRIGGER adocao_delete before delete 
on adocoes
FOR EACH ROW
BEGIN
    set @var_animal_adotado = 0;
    
    delete from mensagens_adocoes where id_adocao = old.id;
    select adotado into @var_animal_adotado from animais where id = old.id_animal;

    if(@var_animal_adotado = 1) then
        update animais set adotado=0 where id = old.id_animal;
    end if;

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