DELIMITER $

CREATE TRIGGER Animais_delete before delete 
on Animais
FOR EACH ROW
BEGIN
    delete from imagens_animais where id_animal = old.id;
    
END$

DELIMITER ;