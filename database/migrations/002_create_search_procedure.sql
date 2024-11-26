DELIMITER $$

CREATE PROCEDURE BuscarRecetas (
    IN p_categoria VARCHAR(50),
    IN p_ingredientes TEXT
)
BEGIN
    SELECT id, nombre, ingredientes, categoria, imagen, fecha_creacion
    FROM recetas
    WHERE (categoria = p_categoria OR p_categoria IS NULL)
    AND (ingredientes LIKE CONCAT('%', p_ingredientes, '%') OR p_ingredientes IS NULL);
END $$

DELIMITER ;