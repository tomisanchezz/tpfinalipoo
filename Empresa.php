<?php
require_once 'database.php';

class Empresa {
    private $nombre;
    private $direccion;
    private $idEmpresa;
    private $error;

    public function __construct($nombre, $direccion, $idEmpresa = null) {
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->idEmpresa = $idEmpresa;
        $this->error = '';
    }

    // Métodos getter y setter
    public function getIdEmpresa() {
        return $this->idEmpresa;
    }

    public function setIdEmpresa($idEmpresa) {
        $this->idEmpresa = $idEmpresa;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function getError() {
        return $this->error;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function cargar($id, $name, $direc){
        $this->setIdEmpresa($id);
        $this->setNombre($name);        
        $this->setDireccion($direc);
    }

	//Funcion para realizar Consultas
	public function buscar($id){
        $base = new BaseDatos();
        $consultaEmpresa = "Select * FROM empresa WHERE idempresa =" . $id;
        $resp = false;
        if($base->Iniciar()){
            //Si se pudo conectar la BD, se realiza la consulta
            if($base->Ejecutar($consultaEmpresa)){
                if($row2 = $base->Registro()){
                    $this->cargar($id, $row2['enombre'], $row2['edireccion']);//Con los [] accedemos a la columna
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

    public function listar($condicion=""){
        $arregloEmpresa = null;
        $base = new BaseDatos();
        $consultaEmpresas = "Select * from empresa ";
        if ($condicion != ""){
            $consultaEmpresas = $consultaEmpresas . ' where ' . $condicion;
        }
        $consultaEmpresas .= " order by idempresa ";

        if($base->Iniciar()){
            if($base->Ejecutar($consultaEmpresas)){
                $arregloEmpresa = array();
                while ($row2 = $base->Registro()){

                    $IdEmpresa = $row2['idempresa'];
                    $Nombre = $row2['enombre'];
                    $Direccion = $row2['edireccion'];

                    $empresa = new Empresa($Nombre,$Direccion,null);
                    $empresa->cargar($IdEmpresa,$Nombre,$Direccion);
                    array_push($arregloEmpresa,$empresa);
                }
            } else {
                $this->setError($base->getERROR());
            }
         } else {
            $this->setError($base->getERROR());
         }
         return $arregloEmpresa;
    }

	 // Método para insertar una nueva empresa en la base de datos
     public function insertarEmpresa() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "INSERT INTO empresa (enombre, edireccion) VALUES ('{$this->getNombre()}', '{$this->getDireccion()}')";
        if ($base->Iniciar()) {
            if ($id = $base->devuelveIDInsercion($sql)) {
                $this->setIdEmpresa($id);
                $resp = true;
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }
        return $resp;
    }

    public function actualizarEmpresa($id) {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE empresa SET enombre='{$this->getNombre()}', edireccion='{$this->getDireccion()}' WHERE idempresa={$id}";
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

    public function eliminarEmpresa($id) {
        $base = new BaseDatos();
        $resp = false;
        $sql = "DELETE FROM empresa WHERE idempresa={$id}";
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