DELIMITER $

CREATE TRIGGER Animais_delete before delete 
on Animais
FOR EACH ROW
BEGIN
    delete from imagens_animais where id_animal = old.id;
    
END$

DELIMITER ;

DELIMITER $
CREATE TRIGGER Denuncias_delete before delete 
on Denuncias
FOR EACH ROW
BEGIN
    delete from Imagens_denuncias where id_denuncia = old.id;
    
END$
DELIMITER ;