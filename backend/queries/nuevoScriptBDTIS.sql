CREATE TABLE docente (
  iddocente SERIAL  NOT NULL ,
  nombredocente VARCHAR(20)    ,
  correoelectronico VARCHAR(45)    ,
  numcelular VARCHAR(20)      ,
PRIMARY KEY(iddocente));



CREATE TABLE ubicacion (
  idubicacion SERIAL  NOT NULL ,
  nombreubicacion VARCHAR(45)      ,
PRIMARY KEY(idubicacion));



CREATE TABLE administrador (
  idadministrador SERIAL  NOT NULL ,
  nombreadministrador INTEGER      ,
PRIMARY KEY(idadministrador));



CREATE TABLE ambiente (
  idambiente SERIAL  NOT NULL ,
  idubicacion INTEGER   NOT NULL ,
  nombreambiente VARCHAR(20)    ,
  capacidadambiente INTEGER      ,
PRIMARY KEY(idambiente),
  FOREIGN KEY(idubicacion)
    REFERENCES ubicacion(idubicacion));



CREATE TABLE materia (
  idmateria SERIAL  NOT NULL ,
  iddocente INTEGER   NOT NULL ,
  nombremateria VARCHAR(255)      ,
PRIMARY KEY(idmateria),
  FOREIGN KEY(iddocente)
    REFERENCES docente(iddocente));



CREATE TABLE motivo (
  idmotivo SERIAL  NOT NULL ,
  idmateria INTEGER   NOT NULL ,
  motivosolicitud VARCHAR(45)      ,
PRIMARY KEY(idmotivo),
  FOREIGN KEY(idmateria)
    REFERENCES materia(idmateria));



CREATE TABLE docente_motivo (
  iddocentemotivo SERIAL  NOT NULL ,
  idmotivo INTEGER   NOT NULL ,
  iddocente INTEGER   NOT NULL   ,
PRIMARY KEY(iddocentemotivo),
  FOREIGN KEY(idmotivo)
    REFERENCES motivo(idmotivo),
  FOREIGN KEY(iddocente)
    REFERENCES docente(iddocente));



CREATE TABLE periodo_academico_disponible (
  idperiodoacademicodisponible SERIAL  NOT NULL ,
  idambiente INTEGER   NOT NULL ,
  horadisponibleinicial TIME    ,
  horadisponiblefinal TIME    ,
  fechadisponible DATE    ,
  estadisponible BOOL      ,
PRIMARY KEY(idperiodoacademicodisponible),
  FOREIGN KEY(idambiente)
    REFERENCES ambiente(idambiente));



CREATE TABLE solicitud (
  idsolicitud SERIAL  NOT NULL ,
  idambiente INTEGER   NOT NULL ,
  capacidadsolicitud INTEGER    ,
  fechasolicitud DATE    ,
  horainicialsolicitud TIME    ,
  horafinalsolicitud TIME    ,
  revisionestapendiente BOOL    ,
  solicitudfueaceptada BOOL    ,
  esurgente BOOL    ,
  bitacorafechasolicitud DATE      ,
PRIMARY KEY(idsolicitud),
  FOREIGN KEY(idambiente)
    REFERENCES ambiente(idambiente));



CREATE TABLE solicitud_docente (
  idsolicituddocente SERIAL  NOT NULL ,
  idsolicitud INTEGER   NOT NULL ,
  iddocentemotivo INTEGER   NOT NULL   ,
PRIMARY KEY(idsolicituddocente, idsolicitud),
  FOREIGN KEY(idsolicitud)
    REFERENCES solicitud(idsolicitud),
  FOREIGN KEY(iddocentemotivo)
    REFERENCES docente_motivo(iddocentemotivo));



CREATE TABLE solicitud_especial (
  idsolicitudespecial SERIAL  NOT NULL ,
  idsolicitud INTEGER   NOT NULL ,
  idadministrador INTEGER   NOT NULL ,
  iddocentemotivo INTEGER   NOT NULL ,
  motivosolicitudespecial VARCHAR(45)      ,
PRIMARY KEY(idsolicitudespecial, idsolicitud, idadministrador),
  FOREIGN KEY(idsolicitud)
    REFERENCES solicitud(idsolicitud),
  FOREIGN KEY(idadministrador)
    REFERENCES administrador(idadministrador),
  FOREIGN KEY(iddocentemotivo)
    REFERENCES docente_motivo(iddocentemotivo));



CREATE TABLE respuesta_solicitud (
  idrespuestasolicitud SERIAL  NOT NULL ,
  idsolicitud INTEGER   NOT NULL ,
  motivodenoreserva VARCHAR(45)    ,
  fecharevision DATE      ,
PRIMARY KEY(idrespuestasolicitud, idsolicitud),
  FOREIGN KEY(idsolicitud)
    REFERENCES solicitud(idsolicitud));



