CREATE DATABASE Db_Usuarios_FlyWay;
USE Db_Usuarios_FlyWay;

CREATE TABLE Usuarios
(
	Usuario_ID int identity (1,1) PRIMARY KEY,
	Usuario_Email VARCHAR(350) NOT NULL,
	Usuario_Nome VARCHAR(40) NOT NULL,
	Usuario_Telefone VARCHAR(20) NOT NULL,
	Usuario_Senha VARCHAR(40 )NOT NULL
);

ALTER TABLE Usuarios
ADD CONSTRAINT CHK_Usuario_Telefone
CHECK (
    Usuario_Telefone LIKE '(__) ____-____'   -- Telefone fixo
    OR
    Usuario_Telefone LIKE '(__) _____-____'  -- Celular
);

SELECT * FROM Usuarios;
