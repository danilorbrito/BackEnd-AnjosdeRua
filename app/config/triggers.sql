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