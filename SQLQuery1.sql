CREATE DATABASE Db_FlyWay;
USE Db_FlyWay;

CREATE TABLE Usuarios
(
	Usuario_ID int identity (1,1) PRIMARY KEY,
	Usuario_Email VARCHAR(350) NOT NULL,
	Usuario_Nome VARCHAR(40) NOT NULL,
	Usuario_Telefone VARCHAR(20) NOT NULL,
	Usuario_Senha VARCHAR(40 )NOT NULL,
	Usuario_img_Perfil VARBINARY(MAX)
);
GO

CREATE TABLE Posts
(
	Post_ID int identity (1,1) PRIMARY KEY,
	Post_Data DATE NOT NULL,
	Post_Tag VARCHAR (30) NOT NULL,
	Post_Titulo VARCHAR (45) NOT NULL,
	Post_Foto  VARBINARY(MAX) NOT NULL,
	Post_Texto VARCHAR (800) NOT NULL,
	Post_Usuario_KEY INT NOT NULL,
	FOREIGN KEY (Post_Usuario_KEY) REFERENCES Usuarios(Usuario_ID)
);

ALTER TABLE Usuarios
ADD CONSTRAINT CHK_Usuario_Telefone
CHECK (
    Usuario_Telefone LIKE '(__) ____-____'   -- Telefone fixo
    OR
    Usuario_Telefone LIKE '(__) _____-____'  -- Celular
);

SELECT * FROM Usuarios;
SELECT * FROM Posts;

SELECT TOP 3
    p.Post_ID,
    p.Post_Titulo,
	p.Post_Foto,
    p.Post_Texto,
    p.Post_Tag,
    p.Post_Data,
    u.Usuario_Nome,
	u.Usuario_img_Perfil
FROM Posts p
INNER JOIN Usuarios u ON p.Post_Usuario_KEY = u.Usuario_ID
ORDER BY p.Post_Data DESC;

SELECT 
    p.Post_ID,
    p.Post_Titulo,
    p.Post_Texto,
    p.Post_Tag,
    p.Post_Data,
    u.Usuario_Nome
FROM Posts p
INNER JOIN Usuarios u ON p.Post_Usuario_KEY = u.Usuario_ID
ORDER BY p.Post_Data DESC
OFFSET 3 ROWS      -- pula os 3 primeiros (top 3)
FETCH NEXT 100 ROWS ONLY; -- pega os próximos 100 posts (ajuste conforme necessidade)