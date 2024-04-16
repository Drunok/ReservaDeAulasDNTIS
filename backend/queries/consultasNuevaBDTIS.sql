///Tabla Docente

INSERT INTO docente(
	nombredocente, correoelectronico, numcelular)
	VALUES ('Leticia Blanco', 'leticiablanco.c@fcyt.umss.edu.bo', '70707070');

SELECT * FROM docente;

///Tabla Materia

INSERT INTO materia(
	iddocente, nombremateria)
	VALUES (1, 'Algoritmos Avanzados');
INSERT INTO materia(
	iddocente, nombremateria)
	VALUES (1, 'Taller de Ingenieria de Software');
INSERT INTO materia(
	iddocente, nombremateria)
	VALUES (1, 'Introduccion a la programacion');
INSERT INTO materia(
	iddocente, nombremateria)
	VALUES (1, 'Elementos de programacion y Estructura de Datos');
INSERT INTO materia(
	iddocente, nombremateria)
	VALUES (1, 'Arquitectura de Computadoras I');


SELECT * FROM materia;

///Tabla motivo

INSERT INTO motivo(
	idmateria, motivosolicitud)
	VALUES (1, 'Primer Parcial');
INSERT INTO motivo(
	idmateria, motivosolicitud)
	VALUES (1, 'Segundo Parcial');
INSERT INTO motivo(
	idmateria, motivosolicitud)
	VALUES (1, 'Examen Final');
INSERT INTO motivo(
	idmateria, motivosolicitud)
	VALUES (1, 'Segunda Instancia');
	
SELECT * FROM motivo;

/// Tabla ubicacion

INSERT INTO ubicacion(
	nombreubicacion)
	VALUES ('Tercer piso edificio academico II');
	
SELECT * FROM ubicacion;

/// Tabla ambiente

INSERT INTO ambiente(
	idubicacion, nombreambiente, capacidadambiente)
	VALUES (3, 'Auditorio', 500);
	
SELECT * FROM ambiente;


/// Tabla periodo_academico_disponible

INSERT INTO periodo_academico_disponible(
	idambiente, horadisponibleinicial, horadisponiblefinal, fechadisponible, estadisponible)
	VALUES (1, '08:15:00', '9:45:00', '2024-04-19', true);
SELECT * FROM periodo_academico_disponible;



