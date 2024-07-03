<?php
class Viaje {
    private $idViaje;
    private $coleccionPasajeros;
    private $objResponsable;
    private $destino;
    private $cantMaxPasajeros;
    private $importe;
    private $objEmpresa;
    private $error;

    public function __construct($idViaje = 0, $objresponsable = null, $destino = "", $cantMaxPasajeros = 0, $importe = 0, $objEmpresa = null) {
        $this->idViaje = $idViaje;
        $this->coleccionPasajeros = array();
        $this->objResponsable = $objresponsable;
        $this->destino = $destino;
        $this->cantMaxPasajeros = $cantMaxPasajeros;
        $this->importe = $importe;
        $this->objEmpresa = $objEmpresa;
        $this->error = null;
    }

    // Getters y setters para error
    public function getError() {
        return $this->error;
    }

    public function setError($error) {
        $this->error = $error;
    }

    public function getColeccionPasajeros() {
        return $this->coleccionPasajeros;
    }

    public function setColeccionPasajeros($coleccionPasajeros) {
        $this->coleccionPasajeros = $coleccionPasajeros;
    }

    public function getObjResponsable() {
        return $this->objResponsable;
    }
    
    public function getDestino() {
        return $this->destino;
    }

    public function getCantMaxPasajeros() {
        return $this->cantMaxPasajeros;
    }

    public function getImporte() {
        return $this->importe;
    }

    public function getObjEmpresa() {
        return $this->objEmpresa;
    }

    public function getIdViaje() {
        return $this->idViaje;
    }

    public function setIdViaje($id) {
        $this->idViaje = $id;
    }

    public function setObjResponsable($responsable) {
        $this->objResponsable = $responsable;
    }

    public function setDestino($destino) {
        $this->destino = $destino;
    }

    public function setCantMaxPasajeros($cantMaxPasaj) {
        $this->cantMaxPasajeros = $cantMaxPasaj;
    }

    public function setImporte($importe) {
        $this->importe = $importe;
    }

    public function setObjEmpresa($objEmp) {
        $this->objEmpresa = $objEmp;
    }

    public function cargar($idViaje, $destino, $cantMaxPasaj, $objEmpresa, $objResponsable, $importe) {
        $this->setIdViaje($idViaje);
        $this->setDestino($destino);
        $this->setCantMaxPasajeros($cantMaxPasaj);
        $this->setObjEmpresa($objEmpresa);
        $this->setObjResponsable($objResponsable);
        $this->setImporte($importe);
    }

    public function buscar($id) {
        $base = new BaseDatos();
        $consultaViaje = "SELECT * FROM viaje WHERE idviaje = " . $id;
        $resp = false;
        if($base->Iniciar()){
            if($base->Ejecutar($consultaViaje)){
                if($row2 = $base->Registro()){
                    $this->setIdViaje($id);
                    $this->setDestino($row2['vdestino']);
                    $this->setCantMaxPasajeros($row2['vcantmaxpasajeros']);
                    
                    // Buscar y asignar el objeto Empresa
                    $objEmpresa = new Empresa("", "", $row2['idempresa']); // Crear objeto con atributos necesarios
                    $objEmpresa->buscar($row2['idempresa']);
                    $this->setObjEmpresa($objEmpresa);
                    
                    // Buscar y asignar el objeto Responsable
                    $objResponsable = new Responsable("", "", "", "", $row2['rnumeroempleado'], ""); // Crear objeto con atributos necesarios
                    if ($objResponsable->buscar($row2['rnumeroempleado'])) {
                        $this->setObjResponsable($objResponsable);
                    } else {
                        $this->setError("Este Responsable no ha sido encontrado.");
                    }
                    
                    $this->setImporte($row2['vimporte']);
                    
                    // Cargar la colección de pasajeros del viaje
                    $this->cargarPasajeros();
                    
                    $resp = true;
                } else {
                    $this->setError("No se encontró ningún viaje con el ID especificado.");
                }
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }
        return $resp;
    }

    private function cargarPasajeros() {
        $base = new BaseDatos();
        $consultaPasajeros = "SELECT p.pdocumento, per.nombre, per.apellido, p.ptelefono, p.idpasajeros, p.idviaje 
                              FROM pasajero AS p
                              INNER JOIN persona AS per ON p.pdocumento = per.documento
                              WHERE p.idviaje = " . $this->getIdViaje();
        
        if($base->Iniciar()){
            if($base->Ejecutar($consultaPasajeros)){
                $pasajeros = array();
                while($row = $base->Registro()){
                    $objPasajero = new Pasajero($row['nombre'], $row['apellido'], $row['pdocumento'], $row['ptelefono'], $row['idviaje'], $row['idpasajeros']);
                    array_push($pasajeros, $objPasajero);
                }
                $this->setColeccionPasajeros($pasajeros);
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }
    }

    public function listar($condicion = "") {
        $arregloViaje = null;
        $base = new BaseDatos();
        $consultaViaje = "SELECT * FROM viaje ";
        if ($condicion != "") {
            $consultaViaje .= ' WHERE ' . $condicion;
        }
        $consultaViaje .= " ORDER BY vdestino ";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaViaje)) {
                $arregloViaje = array();
                while ($row2 = $base->Registro()) {
                    $IdViaje = $row2['idviaje'];
                    $Destino = $row2['vdestino'];
                    $CantMaxPas = $row2['vcantmaxpasajeros'];
                    $IdEmpresa = $row2['idempresa'];
                    $RNumeroEmpleado = $row2['rnumeroempleado'];
                    $VImporte = $row2['vimporte'];
    
                    // Crear objetos Empresa y Responsable
                    $objEmpresa = new Empresa("", "", $IdEmpresa); // Crear objeto con atributos necesarios
                    $objEmpresa->buscar($IdEmpresa);
                    
                    $objResponsable = new Responsable("", "", "", "", $RNumeroEmpleado, ""); // Crear objeto con atributos necesarios
                    $objResponsable->buscar($RNumeroEmpleado);
    
                    $viaje = new Viaje($IdViaje, $objResponsable, $Destino, $CantMaxPas, $VImporte, $objEmpresa);
                    array_push($arregloViaje, $viaje);
                }
            } else {
                $this->setError($base->getERROR());
            }
        } else {
            $this->setError($base->getERROR());
        }
        return $arregloViaje;
    }
    

    public function insertarViaje() {
        $base = new BaseDatos();
    
        if ($this->getObjEmpresa() == null || $this->getObjResponsable() == null) {
            $this->setError("Responsable o Empresa no están correctamente configurados.");
            $resp = false;
        }
    
        $query = "INSERT INTO viaje (vdestino, vcantmaxpasajeros, idempresa, rnumeroempleado, vimporte) 
                  VALUES ('" . $this->getDestino() . "', " . $this->getCantMaxPasajeros() . ", " . $this->getObjEmpresa()->getIdEmpresa() . ", " . $this->getObjResponsable()->getNumeroEmpleado() . ", " . $this->getImporte() . ")";
    
        if ($base->Iniciar()) {
            $idInsercion = $base->devuelveIDInsercion($query);
            if ($idInsercion) {
                $this->setIdViaje($idInsercion);
                $resp = true;
            } else {
                $this->setError($base->getError());
                $resp = false;
            }
        } else {
            $this->setError($base->getError());
            $resp = false;
        }
        return $resp;
    }
    
    
    
    public function actualizarViaje() {
        $base = new BaseDatos();
        $resp = false;
        $sql = "UPDATE viaje SET vdestino='{$this->getDestino()}', vcantmaxpasajeros={$this->getCantMaxPasajeros()}, vimporte={$this->getImporte()}, 
                idempresa={$this->getObjEmpresa()->getIdEmpresa()}, rnumeroempleado={$this->getObjResponsable()->getNumeroEmpleado()} WHERE idviaje={$this->getIdViaje()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setError($base->getError());
            }
        } else {
            $this->setError($base->getError());
        }
        return $resp;
    }

    public function eliminarViaje() {
        $base = new BaseDatos();
        $resp = false;

        if ($base->Iniciar()) {
            // Eliminar personas relacionadas con los pasajeros de este viaje utilizando el documento
            $sqlPersonas = "DELETE FROM persona WHERE documento IN (SELECT documento FROM pasajero WHERE idviaje={$this->getIdViaje()})";
            if ($base->Ejecutar($sqlPersonas)) {
                // Eliminar pasajeros relacionados
                $sqlPasajeros = "DELETE FROM pasajero WHERE idviaje={$this->getIdViaje()}";
                if ($base->Ejecutar($sqlPasajeros)) {
                    // Eliminar responsable relacionado
                    $sqlResponsable = "DELETE FROM responsable WHERE rnumerolicencia IN (SELECT rnumerolicencia FROM viaje WHERE idviaje={$this->getIdViaje()})";
                    if ($base->Ejecutar($sqlResponsable)) {
                        // Si el responsable se elimina correctamente, eliminar el viaje
                        $sqlViaje = "DELETE FROM viaje WHERE idviaje={$this->getIdViaje()}";
                        if ($base->Ejecutar($sqlViaje)) {
                            $resp = true;
                        } else {
                            $this->setError($base->getError());
                        }
                    } else {
                        $this->setError($base->getError());
                    }
                } else {
                    $this->setError($base->getError());
                }
            } else {
                $this->setError($base->getError());
            }
        } else {
            $this->setError($base->getError());
        }

        return $resp;
    }
}
?>
