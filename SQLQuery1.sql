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
);

CREATE TABLE Likes
(
	Like_ID int identity (1,1) PRIMARY KEY,
	Like_UP Bit,
	Like_Donw Bit,
	Like_Usuario_KEY INT NOT NULL,
	Like_Post_KEY INT NOT NULL,
	FOREIGN KEY (Like_Usuario_KEY) REFERENCES Usuarios(Usuario_ID),
	FOREIGN KEY (Like_Post_KEY) REFERENCES Posts(Post_ID)
);

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
SELECT * FROM Likes;


DELETE FROM Likes;
DELETE FROM Comentarios;
DELETE FROM Posts;
DELETE FROM Usuarios;


SELECT 
    Post_ID,
    Post_Titulo,
    COUNT(Likes.Like_ID) AS TotalLikes
FROM Posts
LEFT JOIN Likes ON Posts.Post_ID = Likes.Like_Post_KEY
WHERE Likes.Like_UP = 1
  AND Posts.Post_ID = 1 -- Colocar o Id
GROUP BY Post_ID, Post_Titulo;

SELECT 
    p.Post_ID,
    p.Post_Titulo,
    p.Post_Foto,
    p.Post_Texto,
	p.Post_Data,
    u.Usuario_Nome,
    u.Usuario_img_Perfil,
    ISNULL(SUM(CAST(l.Like_UP AS INT)), 0) AS TotalLikes,
    ISNULL(SUM(CAST(l.Like_Donw AS INT)), 0) AS TotalDislikes
FROM Posts p
INNER JOIN Usuarios u ON p.Post_Usuario_KEY = u.Usuario_ID
LEFT JOIN Likes l ON p.Post_ID = l.Like_Post_KEY
GROUP BY 
    p.Post_ID, 
    p.Post_Titulo, 
    p.Post_Foto, 
    p.Post_Texto, 
    u.Usuario_Nome, 
    u.Usuario_img_Perfil
ORDER BY p.Post_Data DESC
OFFSET 1 ROWS  -- aqui você coloca o offset desejado
FETCH NEXT 1 ROW ONLY;