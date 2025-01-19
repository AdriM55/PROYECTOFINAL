-- Crear la base de datos solo si no existe
CREATE DATABASE IF NOT EXISTS cine_db;
USE cine_db;

-- Tabla de Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabla de Películas
CREATE TABLE IF NOT EXISTS peliculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    duracion INT NOT NULL,               -- Duración en minutos
    categoria VARCHAR(100) NOT NULL,     -- Categoría de la película
    sinopsis TEXT NOT NULL,              -- Sinopsis de la película
    director VARCHAR(100) NOT NULL,      -- Director de la película
    trailer VARCHAR(255)                 -- Enlace al tráiler (puede ser una URL)
);

-- Inserción de las películas
INSERT INTO peliculas (titulo, duracion, categoria, sinopsis, director, trailer)
VALUES 
    ('American Beauty', 122, 'Drama', 'Un hombre de mediana edad atraviesa una crisis emocional mientras lidia con su familia disfuncional.', 'Sam Mendes', 'https://www.youtube.com/watch?v=GqXz2NeSpY8&pp=ygUiYW1lcmljYW4gYmVhdXR5IHRyYWlsZXIgY2FzdGVsbGFubw%3D%3D'),
    ('Haz lo que Debas', 124, 'Drama', 'Un chico enfrenta la vida en un barrio de Brooklyn mientras lidia con las expectativas sociales y familiares.', 'Spike Lee', 'https://www.youtube.com/watch?v=J3Ialftq3IY&pp=ygUjaGF6IGxvIHF1ZSBkZWJhcyB0cmFpbGVyIGNhc3RlbGxhbm8%3D'),
    ('La Haine', 98, 'Drama', 'Tras disturbios en los suburbios de París, tres jóvenes enfrentan su día a día marcado por la violencia y el odio.', 'Mathieu Kassovitz', 'https://www.youtube.com/watch?v=-HJrtVbsKOE&pp=ygUbbGEgaGFpbmUgdHJhaWxlciBjYXN0ZWxsYW5v'),
    ('El Secreto de sus Ojos', 129, 'Drama', 'Un oficial de justicia intenta resolver un caso sin resolver mientras enfrenta su amor no correspondido.', 'Juan José Campanella', 'https://www.youtube.com/watch?v=hKa8U-8vsfU&pp=ygUpZWwgc2VjcmV0byBkZSBzdXMgb2pvcyB0cmFpbGVyIGNhc3RlbGxhbm8%3D'),
    ('12 hombres sin piedad', 96, 'Drama', 'Un joven está acusado de asesinato y un jurado debe determinar su destino, pero uno de los miembros tiene dudas sobre la culpabilidad.', 'Sidney Lumet', 'https://www.youtube.com/watch?v=hiyJZP-MlxM&pp=ygUoMTIgaG9tYnJlcyBzaW4gcGllZGFkIHRyYWlsZXIgY2FzdGVsbGFubw%3D%3D'),
    ('Oppenheimer', 180, 'Biografía', 'La historia del hombre que estuvo al frente del desarrollo de la bomba atómica durante la Segunda Guerra Mundial.', 'Christopher Nolan', 'https://www.youtube.com/watch?v=JpUd4BS7yI0&pp=ygUeb3BwZW5oZWltZXIgdHJhaWxlciBjYXN0ZWxsYW5v'),
    ('Hereditary', 127, 'Terror', 'La muerte de la abuela desata una serie de eventos extraños y aterradores en la vida de una familia.', 'Ari Aster', 'https://www.youtube.com/watch?v=7jMdzpZgqb4&pp=ygUdaGVyZWRpdGFyeSB0cmFpbGVyIGNhc3RlbGxhbm8%3D'),
    ('Gran Torino', 116, 'Drama', 'Un hombre mayor, veterano de la guerra de Corea, enfrenta sus prejuicios mientras forma una amistad con su vecino inmigrante.', 'Clint Eastwood', 'https://www.youtube.com/watch?v=U_bZWFLTp-c&pp=ygUeZ3JhbiB0b3Jpbm8gdHJhaWxlciBjYXN0ZWxsYW5v'),
    ('La Vida de Brian', 94, 'Comedia', 'Un hombre llamado Brian vive situaciones absurdas debido a su coincidencia de nacimiento con Jesús.', 'Terry Jones', 'https://www.youtube.com/watch?v=0-E6bKzb1lw&pp=ygUjbGEgdmlkYSBkZSBicmlhbiB0cmFpbGVyIGNhc3RlbGxhbm8%3D'),
    ('La La Land', 128, 'Musical', 'Un músico y una actriz se enfrentan a las dificultades de la vida mientras persiguen sus sueños en Hollywood.', 'Damien Chazelle', 'https://www.youtube.com/watch?v=nRayUjXIDdQ&pp=ygUebGEgbGEgbGFtbmQgdHJhaWxlciBjYXN0ZWxsYW5v'),
    ('Prisioneros', 153, 'Suspenso', 'Tras el secuestro de su hija, un hombre toma la justicia por su cuenta y se enfrenta a decisiones difíciles.', 'Denis Villeneuve', 'https://www.youtube.com/watch?v=AiN1ahjVb3Y&pp=ygUecHJpc2lvbmVyb3MgdHJhaWxlciBjYXN0ZWxsYW5v'),
    ('Requiem for a Dream', 102, 'Drama', 'La vida de cuatro personajes se ve arruinada por el consumo de drogas y la búsqueda de sus sueños.', 'Darren Aronofsky', 'https://www.youtube.com/watch?v=WshCt8UqWGI&pp=ygUmcmVxdWllbSBmb3IgYSBkcmVhbSB0cmFpbGVyIGNhc3RlbGxhbm8%3D'),
    ('Taxi Driver', 114, 'Drama', 'Un veterano de Vietnam que trabaja como taxista comienza a perder el control y desata su ira contra la corrupción en la sociedad.', 'Martin Scorsese', 'https://www.youtube.com/watch?v=f-WNguoS_QA&pp=ygUedGF4aSBkcml2ZXIgdHJhaWxlciBjYXN0ZWxsYW5v'),
    ('Eyes Wide Shut', 159, 'Drama', 'Un hombre se ve envuelto en una serie de eventos misteriosos y peligrosos relacionados con una sociedad secreta.', 'Stanley Kubrick', 'https://www.youtube.com/watch?v=5fJLqVlWQlI&pp=ygUhZXllcyB3aWRlIHNodXQgdHJhaWxlciBjYXN0ZWxsYW5v'),
    ('La Naranja Mecánica', 136, 'Ciencia Ficción', 'Un joven violento se somete a un tratamiento experimental para cambiar su naturaleza, pero las consecuencias son devastadoras.', 'Stanley Kubrick', 'https://www.youtube.com/watch?v=MmUhbSZMjOY&pp=ygUmbGEgbmFyYW5qYSBtZWNhbmljYSB0cmFpbGVyIGNhc3RlbGxhbm8%3D'),
    ('GoodFellas', 146, 'Crimen', 'La historia de un hombre que sube de rango en el crimen organizado y las dificultades de vivir en ese mundo.', 'Martin Scorsese', 'https://www.youtube.com/watch?v=Shj-QWYDn_M&pp=ygUdZ29vZGZlbGxhcyB0cmFpbGVyIGNhc3RlbGxhbm8%3D'),
    ('Ciudad de Dios', 130, 'Crimen', 'Un joven en una favela de Brasil se ve atrapado en el mundo del crimen y la violencia mientras busca escapar.', 'Fernando Meirelles', 'https://www.youtube.com/watch?v=63kaF1n5sz0&pp=ygUWY2l1ZGFkIGRlIGRpb3MgdHJhaWxlcg%3D%3D'),
    ('Instinto Básico', 125, 'Thriller', 'Johnny Boz, antiguo cantante de rock, aparece brutalmente asesinado en su cama. La última vez que se le vio estaba con su novia, Catherine Tramell, una atractiva escritora de novelas de intriga. El agente Nick Curran, debe vigilar a Catherine.', 'Paul Verhoeven', 'https://www.youtube.com/watch?v=k1NvSo6YorI&pp=ygUjaW5zdGludG8gYsOhc2ljbyB0cmFpbGVyIGNhc3RlbGxhbm8%3D'),
    ('Babylon', 182, 'Drama', 'Ambientada en Los Ángeles durante los años 20, cuenta una historia de ambición y excesos desmesurados que recorre la ascensión y caída de múltiples personajes durante una época de desenfrenada decadencia y depravación en los albores de Hollywood.', 'Damien Chazelle', 'https://www.youtube.com/watch?v=gBil8RpweBE&pp=ygUaYmFieWxvbiB0cmFpbGVyIGNhc3RlbGxhbm8%3D'),
    ('Memento', 113, 'Suspenso', 'Un hombre con amnesia a corto plazo usa tatuajes y notas para resolver el asesinato de su esposa.', 'Christopher Nolan', 'https://www.youtube.com/watch?v=mV9l1enMqvk&pp=ygUPbWVtZW50byB0cmFpbGVy');

-- Tabla de Horarios
CREATE TABLE IF NOT EXISTS horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelicula_id INT NOT NULL,
    horario TIME NOT NULL,
    FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE
);

-- Tabla de Reservas
CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    horario_id INT NOT NULL,
    asiento VARCHAR(10) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (horario_id) REFERENCES horarios(id) ON DELETE CASCADE
);
