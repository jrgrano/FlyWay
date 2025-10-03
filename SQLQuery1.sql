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
	Post_Data DATETIME NOT NULL,
	Post_Tag VARCHAR (30) NOT NULL,
	Post_Titulo VARCHAR (45) NOT NULL,
	Post_Foto  VARBINARY(MAX) NOT NULL,
	Post_Texto VARCHAR (800) NOT NULL,
	Post_Usuario_KEY INT NOT NULL,
	FOREIGN KEY (Post_Usuario_KEY) REFERENCES Usuarios(Usuario_ID)
);

CREATE TABLE Comentarios
(	
	Comentario_ID int identity (1,1) PRIMARY KEY,
	Comentario_Texto VARCHAR (200),
	Comentario_Usuario_KEY INT NOT NULL,
	Comentario_post_KEY INT NOT NULL,
	FOREIGN KEY (Comentario_Usuario_KEY) REFERENCES Usuarios(Usuario_ID),
	FOREIGN KEY (Comentario_post_KEY) REFERENCES Posts(Post_ID)
)

ALTER TABLE Usuarios
ADD CONSTRAINT CHK_Usuario_Telefone
CHECK (
    Usuario_Telefone LIKE '(__) ____-____'
    OR
    Usuario_Telefone LIKE '(__) _____-____' 
);

SELECT * FROM Usuarios;
SELECT * FROM Posts;
SELECT * FROM Comentarios;

SELECT TOP 1
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
	p.Post_Foto,
    p.Post_Texto,
    p.Post_Tag,
    p.Post_Data,
    u.Usuario_Nome,
	u.Usuario_img_Perfil
FROM Posts p
INNER JOIN Usuarios u ON p.Post_Usuario_KEY = u.Usuario_ID
ORDER BY p.Post_Data DESC
OFFSET 1 ROWS      
FETCH NEXT 1 ROWS ONLY;

TRUNCATE TABLE Posts;

SELECT TOP 3 * FROM Comentarios;

SELECT TOP 3
    c.Comentario_ID,
    c.Comentario_Texto,
    u.Usuario_Nome,
    u.Usuario_img_Perfil
FROM Comentarios c
INNER JOIN Usuarios u 
    ON c.Comentario_Usuario_KEY = u.Usuario_ID
WHERE c.Comentario_post_KEY = 9
ORDER BY c.Comentario_ID DESC;

$slq_Comentario_1 = "
            SELECT TOP 3
              c.Comentario_ID,
              c.Comentario_Texto,
              u.Usuario_Nome,
              u.Usuario_img_Perfil
            FROM Comentarios c
            INNER JOIN Usuarios u 
            ON c.Comentario_Usuario_KEY = u.Usuario_ID
            WHERE c.Comentario_post_KEY = @PostID 
            ORDER BY c.Comentario_ID DESC;
           ";


		   SELECT 
            c.Comentario_ID,
            c.Comentario_Texto,
            u.Usuario_Nome,
            u.Usuario_img_Perfil
            FROM Comentarios c
            INNER JOIN Usuarios u 
            ON c.Comentario_Usuario_KEY = u.Usuario_ID
            WHERE c.Comentario_post_KEY = 9
            ORDER BY c.Comentario_ID DESC
            OFFSET 0 ROWS
            FETCH NEXT 3 ROWS ONLY;
