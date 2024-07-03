<?php
require_once 'Persona.php';
require_once 'database.php';

class Pasajero extends Persona {
    private $objViaje;
    private $idPasajero;
    public function __construct($nombre, $apellido, $dni, $telefono,$objViaje,$idPasajero) {
        parent::__construct($nombre, $apellido, $dni, $telefono);
        $this->objViaje = $objViaje;
        $this->idPasajero = $idPasajero;
    }
    public function getobjViaje(){
        return $this->objViaje;
    }
    public function setobjViaje($objviaje){
        $this->objViaje = $objviaje;
    }
    public function getIdPasajero(){
        return $this->objViaje;
    }
    public function setIdpajsajero($idpasajero){
        $this->idPasajero = $idpasajero;
    }


  
    public function cargar($dni, $name, $apell,$tel, $idPasajero = null ,$objViaje = null) {
        parent::cargar($dni, $name, $apell,$tel);
        $this->setObjViaje($objViaje);
        $this->setIdpajsajero($idPasajero);
    }
    
    //crear objeto viaje. en ambos modulos.
    public function buscar($id) {
        $base = new BaseDatos();
        $consultaPasajero = "SELECT * FROM pasajero WHERE idpasajeros = " . $id;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                if ($row2 = $base->Registro()) {
                    parent::buscar($row2['pdocumento']);
                    $this->setIdpajsajero($id);
                    $this->setTelefono($row2['ptelefono']);
                    
                    $viaje = new Viaje();
                    if ($viaje->buscar($row2['idviaje'])) {
                        $this->setObjViaje($viaje);
                        $resp = true;
                    } else {
                        $this->setError("Error al buscar el viaje con id " . $row2['idviaje']);
                    }
                }
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }
        return $resp;
    }

    public function listar($condicion = "") {
        $arregloPasajero = null;
        $base = new BaseDatos();
        $consultaPasajero = "SELECT p.*, per.nombre, per.apellido 
                             FROM pasajero AS p 
                             INNER JOIN persona AS per ON p.pdocumento = per.documento";
        if ($condicion != "") {
            $consultaPasajero .= " WHERE " . $condicion;
        }
        $consultaPasajero .= " ORDER BY p.idpasajeros";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaPasajero)) {
                $arregloPasajero = array();
                while ($row2 = $base->Registro()) {
                    $obj = new Pasajero($row2['nombre'], $row2['apellido'], $row2['pdocumento'], $row2['ptelefono'], $row2['idviaje'], $row2['idpasajeros']);
                    array_push($arregloPasajero, $obj);
                }
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }
        return $arregloPasajero;
    }

    public function insertar() {
        $base = new BaseDatos();
        $resp = false;

        // Insertar en la tabla persona primero
        if (parent::insertar()) {
            $sql = "INSERT INTO pasajero (documento, nombre, apellido, telefono, idviaje) 
                    VALUES ('{$this->getDni()}', '{$this->getNombre()}', '{$this->getApellido()}', '{$this->getTelefono()}', {$this->getobjViaje()->getIdViaje()})";
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $resp = true;
                } else {
                    $this->setError($base->getERROR());
                }
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError("Error al insertar en la tabla persona.");
        }

        return $resp;
    }

    public function actualizar() {
        $base = new BaseDatos();
        $resp = false;
    
        // Actualizar persona usando el método del padre
        if (parent::actualizar()) {
            // Si la actualización en la tabla persona es exitosa, actualizar la tabla pasajero
            $sql = "UPDATE pasajero 
                    SET idviaje = '{$this->getobjViaje()->getIdViaje()}', idPasajero ='{$this->getIdPasajero()}' 
                    WHERE documento='{$this->getDni()}'";
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $resp = true;
                } else {
                    $this->setError($base->getError());
                }
            } else {
                $this->setError($base->getError());
            }
        } else {
            $this->setError("Error al actualizar persona.");
        }
    
        return $resp;
    }
    

    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;

        // Eliminar el registro de la tabla pasajero
        $sqlDeletePasajero = "DELETE FROM pasajero WHERE documento='{$this->getDni()}'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sqlDeletePasajero)) {
                // Usar el método del padre para eliminar la persona
                if (parent::eliminar()) {
                    $resp = true;
                } else {
                    $this->setError("Error al eliminar de la tabla persona.");
                }
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }

        return $resp;
    }
}
?>
