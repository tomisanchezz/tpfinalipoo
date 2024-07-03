<?php
require_once 'database.php';

class Persona {
    protected $nombre;
    protected $apellido;
    protected $dni;
    protected $telefono;
    private $error;

    public function __construct($nombre, $apellido, $dni, $telefono) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
        $this->telefono = $telefono;
        $this->error = '';
    }

    // Métodos getter y setter 
    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function getDni() {
        return $this->dni;
    }

    public function setDni($dni) {
        $this->dni = $dni;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function getError() {
        return $this->error;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function cargar($dni, $name, $apell,$tel){
        $this->setDni($dni);
        $this->setNombre($name);        
        $this->setApellido($apell);
        $this->setTelefono($tel);
    }

	//Funcion para realizar Consultas
	public function buscar($dni){
        $base = new BaseDatos();
        $consultaPersona = "SELECT * FROM persona WHERE documento =".$dni;
        $resp = false;
        if($base->Iniciar()){
            //Si se pudo conectar la BD, se realiza la consulta
            if($base->Ejecutar($consultaPersona)){
                if($row2 = $base->Registro()){
                    $this->setDni($dni);
                    $this->setNombre($row2['nombre']);
                    $this->setApellido($row2['apellido']);
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

    public  function listar($condicion=""){
        $arregloPersona = null;
        $base=new BaseDatos();
        $consultaPersonas="SELECT * FROM persona ";
        if ($condicion!=""){
            $consultaPersonas=$consultaPersonas.' WHERE '.$condicion;
        }
        $consultaPersonas.=" order by idpersonas ";
        
        if($base->Iniciar()){
            if($base->Ejecutar($consultaPersonas)){
                $arregloPersona= array();
                while($row2=$base->Registro()){

                    $NroDoc=$row2['documento'];
                    $Nombre=$row2['nombre'];
                    $Apellido=$row2['apellido'];
                    $telefono=$row2['telefono'];
                    $perso = new Persona($Nombre,$Apellido,$NroDoc,$telefono);
                    $perso->cargar($NroDoc,$Nombre,$Apellido,$telefono);
                    array_push($arregloPersona,$perso);
                }
             }    else {
                     $this->setError($base->getERROR());
            }
         }    else {
                 $this->setError($base->getERROR());
         }
         return $arregloPersona;
    }

    // Método para insertar persona en la base de datos
    public function insertar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "INSERT INTO persona (nombre, apellido, documento, telefono) 
                VALUES ('{$this->getNombre()}', '{$this->getApellido()}', '{$this->getDni()}', '{$this->getTelefono()}')";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }
        return $resp;
    }

    // Método para actualizar persona en la base de datos
    public function actualizar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE persona 
                SET nombre='{$this->getNombre()}', apellido='{$this->getApellido()}', telefono='{$this->getTelefono()}' 
                WHERE documento='{$this->getDni()}'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }
        return $resp;
    }

    // Método para eliminar persona de la base de datos
    public function eliminar() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE FROM persona WHERE documento='{$this->getDni()}'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
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
