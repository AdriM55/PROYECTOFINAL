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

CREATE TABLE IF NOT EXISTS peliculas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    portada VARCHAR(255) NOT NULL,        -- Ruta de la imagen de la película
    duracion INT NOT NULL,               -- Duración en minutos
    categoria VARCHAR(100) NOT NULL,     -- Categoría de la película
    sinopsis TEXT NOT NULL,              -- Sinopsis de la película
    director VARCHAR(100) NOT NULL,      -- Director de la película
    trailer VARCHAR(255)                -- Enlace al tráiler (puede ser una URL)
);


-- Inserción de las películas
INSERT INTO peliculas (titulo, portada, precio, duracion, categoria, sinopsis, director, trailer)
VALUES 
    ('American Beauty', 'portadas/american_beauty.jpg', 10, 122, 'Drama', 'Un hombre de mediana edad atraviesa una crisis emocional mientras lidia con su familia disfuncional.', 'Sam Mendes', 'https://www.youtube.com/embed/GqXz2NeSpY8?si=AzUxVyZUy9tWnI9o'),
    ('Haz lo que Debas', 'portadas/haz_lo_que_debas.jpg', 10, 124, 'Drama', 'Un chico enfrenta la vida en un barrio de Brooklyn mientras lidia con las expectativas sociales y familiares.', 'Spike Lee', 'https://www.youtube.com/embed/J3Ialftq3IY?si=cy1zbL3KMZ2wXbMP'),
    ('La Haine', 'portadas/la_haine.jpg', 10, 98, 'Drama', 'Tras disturbios en los suburbios de París, tres jóvenes enfrentan su día a día marcado por la violencia y el odio.', 'Mathieu Kassovitz', 'https://www.youtube.com/embed/OfE0o9B3dhI?si=LclQkcGHmFqyPGHG'),
    ('El Secreto de sus Ojos', 'portadas/el_secreto_de_sus_ojos.jpg', 10, 129, 'Drama', 'Un oficial de justicia intenta resolver un caso sin resolver mientras enfrenta su amor no correspondido.', 'Juan José Campanella', 'https://www.youtube.com/embed/hKa8U-8vsfU?si=zwzHID5S3l_h7gZB'),
    ('12 hombres sin piedad', 'portadas/12_hombres_sin_piedad.jpg', 10, 96, 'Drama', 'Un joven está acusado de asesinato y un jurado debe determinar su destino, pero uno de los miembros tiene dudas sobre la culpabilidad.', 'Sidney Lumet', 'https://www.youtube.com/embed/hiyJZP-MlxM?si=9DA-KuGkErS-yNtp'),
    ('Oppenheimer', 'portadas/oppenheimer.jpg', 10, 180, 'Biografía', 'La historia del hombre que estuvo al frente del desarrollo de la bomba atómica durante la Segunda Guerra Mundial.', 'Christopher Nolan', 'https://www.youtube.com/embed/yLYbOe914ZU?si=8j7ivHIKklQBhp3J'),
    ('Hereditary', 'portadas/hereditary.jpg', 10, 127, 'Terror', 'La muerte de la abuela desata una serie de eventos extraños y aterradores en la vida de una familia.', 'Ari Aster', 'https://www.youtube.com/embed/7jMdzpZgqb4?si=w-lqxsUs41e3nyRG'),
    ('Gran Torino', 'portadas/gran_torino.jpg', 10, 116, 'Drama', 'Un hombre mayor, veterano de la guerra de Corea, enfrenta sus prejuicios mientras forma una amistad con su vecino inmigrante.', 'Clint Eastwood', 'https://www.youtube.com/embed/U_bZWFLTp-c?si=4AOqHuc9csJNp1FQ'),
    ('La Vida de Brian', 'portadas/la_vida_de_brian.jpg', 10, 94, 'Comedia', 'Un hombre llamado Brian vive situaciones absurdas debido a su coincidencia de nacimiento con Jesús.', 'Terry Jones', 'https://www.youtube.com/embed/0-E6bKzb1lw?si=rPbBPJF9yp5bpvyR'),
    ('La La Land', 'portadas/la_la_land.jpg', 10, 128, 'Musical', 'Un músico y una actriz se enfrentan a las dificultades de la vida mientras persiguen sus sueños en Hollywood.', 'Damien Chazelle', 'https://www.youtube.com/embed/KtqSUk0kwJs?si=-UxQqp3JGu8URhWX'),
    ('Prisioneros', 'portadas/prisioneros.jpg', 10, 153, 'Suspenso', 'Tras el secuestro de su hija, un hombre toma la justicia por su cuenta y se enfrenta a decisiones difíciles.', 'Denis Villeneuve', 'https://www.youtube.com/embed/KjUcl8eYxxQ?si=-fT9eaPEJhmuCSt7'),
    ('Requiem for a Dream', 'portadas/requiem_for_a_dream.jpg', 10, 102, 'Drama', 'La vida de cuatro personajes se ve arruinada por el consumo de drogas y la búsqueda de sus sueños.', 'Darren Aronofsky', 'https://www.youtube.com/embed/WshCt8UqWGI?si=74lvA-bg0YEOY2ep'),
    ('Taxi Driver', 'portadas/taxi_driver.jpg', 10, 114, 'Drama', 'Un veterano de Vietnam que trabaja como taxista comienza a perder el control y desata su ira contra la corrupción en la sociedad.', 'Martin Scorsese', 'https://www.youtube.com/embed/nfC-RpJF5F0?si=kxt1tudp727voQSl'),
    ('Eyes Wide Shut', 'portadas/eyes_wide_shut.jpg', 10, 159, 'Drama', 'Un hombre se ve envuelto en una serie de eventos misteriosos y peligrosos relacionados con una sociedad secreta.', 'Stanley Kubrick', 'https://www.youtube.com/embed/5fJLqVlWQlI?si=tJA2hfGtuuViRpDU'),
    ('La Naranja Mecánica', 'portadas/la_naranja_mecanica.jpg', 10, 136, 'Ciencia Ficción', 'Un joven violento se somete a un tratamiento experimental para cambiar su naturaleza, pero las consecuencias son devastadoras.', 'Stanley Kubrick', 'https://www.youtube.com/embed/NPinel6R-_Y?si=SGXnHx6xZB9a97i9'),
    ('GoodFellas', 'portadas/goodfellas.jpg', 10, 146, 'Crimen', 'La historia de un hombre que sube de rango en el crimen organizado y las dificultades de vivir en ese mundo.', 'Martin Scorsese', 'https://www.youtube.com/embed/Shj-QWYDn_M?si=-PLOYCOfWs-f0Cl7'),
    ('Ciudad de Dios', 'portadas/ciudad_de_dios.jpg', 10, 130, 'Crimen', 'Un joven en una favela de Brasil se ve atrapado en el mundo del crimen y la violencia mientras busca escapar.', 'Fernando Meirelles', 'https://www.youtube.com/embed/JYKs35P2fZw?si=s1KVSlcHLjsLEM3r'),
    ('Instinto Básico', 'portadas/instinto_basico.jpg', 10, 125, 'Thriller', 'Johnny Boz, antiguo cantante de rock, aparece brutalmente asesinado en su cama. La última vez que se le vio estaba con su novia, Catherine Tramell, una atractiva escritora de novelas de intriga. El agente Nick Curran, debe vigilar a Catherine.', 'Paul Verhoeven', 'https://www.youtube.com/embed/k1NvSo6YorI?si=cbnrLZGnfkEttPIT'),
    ('Babylon', 'portadas/babylon.jpg', 10, 182, 'Drama', 'Ambientada en Los Ángeles durante los años 20, cuenta una historia de ambición y excesos desmesurados que recorre la ascensión y caída de múltiples personajes durante una época de desenfrenada decadencia y depravación en los albores de Hollywood.', 'Damien Chazelle', 'https://www.youtube.com/embed/gBil8RpweBE?si=HNrycfHWD06mKMdv'),
    ('Memento', 'portadas/memento.jpg', 10, 113, 'Suspenso', 'Un hombre con amnesia a corto plazo usa tatuajes y notas para resolver el asesinato de su esposa.', 'Christopher Nolan', 'https://www.youtube.com/embed/mV9l1enMqvk?si=0_apTV6QqX6-coPr');

-- Tabla de Horarios
CREATE TABLE IF NOT EXISTS horarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pelicula_id INT NOT NULL,
    horario TIME NOT NULL,
    FOREIGN KEY (pelicula_id) REFERENCES peliculas(id) ON DELETE CASCADE
);

-- Inserción de horarios para las películas con variaciones
INSERT INTO horarios (pelicula_id, horario)
VALUES
    (1, '15:00:00'), (1, '18:00:00'), (1, '21:00:00'), -- "American Beauty" (3 horarios)
    (2, '15:30:00'), (2, '19:00:00'),                 -- "Haz lo que Debas" (2 horarios)
    (3, '15:45:00'), (3, '17:45:00'), (3, '20:00:00'), (3, '22:00:00'), (3, '00:00:00'), -- "La Haine" (5 horarios)
    (4, '16:00:00'), (4, '18:30:00'), (4, '21:15:00'), -- "El Secreto de sus Ojos" (3 horarios)
    (5, '15:15:00'), (5, '17:45:00'), (5, '20:15:00'), (5, '22:45:00'), -- "12 hombres sin piedad" (4 horarios)
    (6, '16:30:00'), (6, '19:30:00'), (6, '22:30:00'), (6, '01:30:00'), -- "Oppenheimer" (4 horarios)
    (7, '17:00:00'), (7, '19:45:00'), (7, '22:15:00'), -- "Hereditary" (3 horarios)
    (8, '15:00:00'), (8, '18:00:00'), (8, '21:00:00'), -- "Gran Torino" (3 horarios)
    (9, '16:45:00'), (9, '19:15:00'),                 -- "La Vida de Brian" (2 horarios)
    (10, '15:30:00'), (10, '18:30:00'), (10, '21:30:00'), (10, '23:59:00'), -- "La La Land" (4 horarios)
    (11, '16:00:00'), (11, '18:45:00'), (11, '21:30:00'), (11, '00:15:00'), -- "Prisioneros" (4 horarios)
    (12, '17:15:00'), (12, '20:00:00'), (12, '22:45:00'), -- "Requiem for a Dream" (3 horarios)
    (13, '15:45:00'), (13, '18:15:00'), (13, '20:45:00'), (13, '23:15:00'), (13, '01:45:00'), -- "Taxi Driver" (5 horarios)
    (14, '16:00:00'), (14, '18:45:00'), (14, '21:15:00'), -- "Eyes Wide Shut" (3 horarios)
    (15, '15:30:00'), (15, '18:00:00'), (15, '20:30:00'), (15, '23:00:00'), -- "La Naranja Mecánica" (4 horarios)
    (16, '16:15:00'), (16, '18:45:00'), (16, '21:15:00'), -- "GoodFellas" (3 horarios)
    (17, '15:00:00'), (17, '17:45:00'), (17, '20:15:00'), (17, '22:45:00'), -- "Ciudad de Dios" (4 horarios)
    (18, '16:30:00'), (18, '19:00:00'), (18, '21:30:00'), -- "Instinto Básico" (3 horarios)
    (19, '15:15:00'), (19, '18:00:00'), (19, '20:45:00'), (19, '23:30:00'), (19, '02:15:00'), -- "Babylon" (5 horarios)
    (20, '17:00:00'), (20, '19:30:00'), (20, '22:00:00'); -- "Memento" (3 horarios)

-- Tabla de Reservas
CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    horario_id INT NOT NULL,
    asiento VARCHAR(10) NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (horario_id) REFERENCES horarios(id) ON DELETE CASCADE
);
