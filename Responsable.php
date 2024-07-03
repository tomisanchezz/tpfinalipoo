<?php
require_once 'Persona.php';
require_once 'database.php';

class Responsable extends Persona
{
    private $numeroEmpleado;
    private $numeroLicencia;

    public function __construct($nombre, $apellido, $dni, $telefono, $numeroEmpleado, $numeroLicencia)
    {
        parent::__construct($nombre, $apellido, $dni, $telefono);
        $this->numeroEmpleado = $numeroEmpleado;
        $this->numeroLicencia = $numeroLicencia;
    }

    // Métodos getter y setter para los atributos adicionales
    public function getNumeroEmpleado()
    {
        return $this->numeroEmpleado;
    }

    public function setNumeroEmpleado($numeroEmpleado)
    {
        $this->numeroEmpleado = $numeroEmpleado;
    }

    public function getNumeroLicencia()
    {
        return $this->numeroLicencia;
    }

    public function setNumeroLicencia($numeroLicencia)
    {
        $this->numeroLicencia = $numeroLicencia;
    }

    public function cargar($dni, $name, $apell, $tel, $numEmpleado = null, $numLicencia = null)
    {
        parent::cargar($dni, $name, $apell, $tel);
        $this->setNumeroEmpleado($numEmpleado);
        $this->setNumeroLicencia($numLicencia);
    }

    public function buscar($numEmpleado)
    {
        $base = new BaseDatos();
        $resp = false;
        $consultaResponsable = "SELECT * FROM responsable WHERE rnumeroempleado = " . $numEmpleado;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                if ($row2 = $base->Registro()) {
                    parent::buscar($row2['documento']); // buscamos por id en la tabla persona
                    $this->setNumeroEmpleado($row2['rnumeroempleado']);
                    $this->setNumeroLicencia($row2['rnumerolicencia']);
                    $resp = true;
                }
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }
        return $resp;
    }

    public function listar($condicion = "")
    {
        $arregloResponsable = null;
        $base = new BaseDatos();
        $consultaResponsable = "SELECT responsable.*, persona.nombre, persona.apellido FROM responsable 
                                INNER JOIN persona ON responsable.documento  = persona.documento";
        if ($condicion != "") {
            $consultaResponsable .= ' WHERE ' . $condicion;
        }
        $consultaResponsable .= " ORDER BY rnumeroempleado ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaResponsable)) {
                $arregloResponsable = array();
                while ($row2 = $base->Registro()) {
                    $obj = new Responsable($row2['nombre'], $row2['apellido'], $row2['documento'], $row2['telefono'], $row2['rnumeroempleado'], $row2['rnumerolicencia']);
                    array_push($arregloResponsable, $obj);
                }
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }
        return $arregloResponsable;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $resp = false;
        //parent::insertarPersona()
        $doc = $this->getDni();
    
        if (parent::insertar()) {
            $sql = "INSERT INTO responsable (rnumeroempleado, rnumerolicencia, documento) VALUES ('" . $this->getNumeroEmpleado() . "', '" . $this->getNumeroLicencia() . "', '" . $doc . "')";
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

    public function actualizar()
    {
        $base = new BaseDatos();
        $resp = false;

        // Actualizar persona usando el método del padre
        if (parent::actualizar()) {
            // Si la actualización en la tabla persona es exitosa, actualizar la tabla responsable
            $sql = "UPDATE responsable 
                    SET rnumerolicencia={$this->getNumeroLicencia()}, 
                    WHERE rnumeroempleado={$this->getNumeroEmpleado()}";
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

    public function eliminar()
    {
        $base = new BaseDatos();
        $resp = false;
        // Eliminar registros de la tabla viaje con el numero de empleado
        $sqlViaje = "DELETE FROM viaje WHERE rnumeroempleado={$this->getNumeroEmpleado()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sqlViaje)) {
                // Eliminar responsable con el numero de empleado
                $sqlResponsable = "DELETE FROM responsable WHERE rnumeroempleado={$this->getNumeroEmpleado()}";
                if ($base->Ejecutar($sqlResponsable)) {
                    // Eliminar registro de persona con el dni
                    $sqlPersona = "DELETE FROM persona WHERE documento='{$this->getDni()}'";
                    if ($base->Ejecutar($sqlPersona)) {
                        $resp = true;
                    } else {
                        $this->setError($base->getERROR());
                    }
                } else {
                    $this->setError($base->getERROR());
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
