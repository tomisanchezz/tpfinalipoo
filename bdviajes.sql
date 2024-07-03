CREATE DATABASE bdviajes;

USE bdviajes;

CREATE TABLE persona (
    documento varchar(15) PRIMARY KEY,
    nombre varchar(150),
    apellido varchar(150),
    telefono varchar(15) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE empresa (
    idempresa bigint AUTO_INCREMENT,
    enombre varchar(150),
    edireccion varchar(150),
    PRIMARY KEY (idempresa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE responsable (
    rnumeroempleado bigint AUTO_INCREMENT,
    rnumerolicencia bigint,
    nombre varchar(150),
    apellido varchar(150),
    documento varchar(15),
    telefono varchar(15),
    PRIMARY KEY (rnumeroempleado),
    FOREIGN KEY (documento) REFERENCES persona (documento)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE viaje (
    idviaje bigint AUTO_INCREMENT, 
    vdestino varchar(150),
    vcantmaxpasajeros int,
    idempresa bigint,
    rnumeroempleado bigint,
    vimporte float,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa (idempresa),
    FOREIGN KEY (rnumeroempleado) REFERENCES responsable (rnumeroempleado)
    ON UPDATE CASCADE
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE pasajero (
    documento varchar(15),
    nombre varchar(150),
    apellido varchar(150),
    telefono varchar(15),
    idviaje bigint,
    PRIMARY KEY (documento, idviaje),
    FOREIGN KEY (documento) REFERENCES persona (documento)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
    FOREIGN KEY (idviaje) REFERENCES viaje (idviaje)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
