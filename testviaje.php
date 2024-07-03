<?php
require_once 'database.php';
require_once 'Empresa.php';
require_once 'Responsable.php';
require_once 'Pasajero.php';
require_once 'Viaje.php';

// Valida $numero sea un número
function validarNumeros($numero) {
    return is_numeric($numero);
}

function gestionarEmpresa() {
    echo "Seleccione una acción:\n";
    echo "1. Cargar Empresa\n";
    echo "2. Modificar Empresa\n";
    echo "3. Eliminar Empresa\n";
    $accion = trim(fgets(STDIN));

    switch($accion) {
        case 1:
            echo "Ingrese nombre de la empresa: ";
            $nombre = trim(fgets(STDIN));
            echo "Ingrese dirección de la empresa: ";
            $direccion = trim(fgets(STDIN));
            $empresa = new Empresa("", $nombre, $direccion); // Inicializar con nombre y dirección
            $empresa->cargar(0, $nombre, $direccion); // Utilizar la función cargar
            if ($empresa->insertarEmpresa()) {
                echo "Empresa guardada exitosamente\n";
            } else {
                echo "Error al guardar la empresa\n";
            }
            break;
        case 2:
            echo "Ingrese ID de la empresa a modificar: ";
            $id = trim(fgets(STDIN));
            if (!validarNumeros($id)) {
                echo "ID debe ser numérico\n";
                return;
            }
            $empresa = new Empresa($id, "", ""); // Inicializar con el ID de la empresa
            if ($empresa->buscar($id)) {
                echo "Ingrese nuevo nombre de la empresa: ";
                $nombre = trim(fgets(STDIN));
                echo "Ingrese nueva dirección de la empresa: ";
                $direccion = trim(fgets(STDIN));
                $empresa->cargar($id, $nombre, $direccion);
                if ($empresa->actualizarEmpresa($id)) {
                    echo "Empresa actualizada exitosamente\n";
                } else {
                    echo "Error al actualizar la empresa\n";
                }
            } else {
                echo "No se encontró una empresa con el ID proporcionado\n";
            }
            break;
        case 3:
            echo "Ingrese ID de la empresa a eliminar: ";
            $id = trim(fgets(STDIN));
            if (!validarNumeros($id)) {
                echo "ID debe ser numérico\n";
                return;
            }
            $empresa = new Empresa($id, "", ""); // Inicializar con el ID de la empresa
            if ($empresa->buscar($id)) {
                if ($empresa->eliminarEmpresa($id)) {
                    echo "Empresa eliminada exitosamente\n";
                } else {
                    echo "Error al eliminar la empresa\n";
                }
            } else {
                echo "No se encontró una empresa con el ID proporcionado\n";
            }
            break;
        default:
            echo "Acción no válida\n";
    }
}



function gestionarResponsable() {
    echo "Seleccione una acción:\n";
    echo "1. Cargar Responsable\n";
    echo "2. Modificar Responsable\n";
    echo "3. Eliminar Responsable\n";
    $accion = trim(fgets(STDIN));

    switch($accion) {
        case 1:
            echo "Ingrese número de licencia del responsable: ";
            $numeroLicencia = trim(fgets(STDIN));
            if (!validarNumeros($numeroLicencia)) {
                echo "Número de licencia debe ser numérico\n";
                return;
            }
            echo "Ingrese nombre del responsable: ";
            $nombre = trim(fgets(STDIN));
            echo "Ingrese apellido del responsable: ";
            $apellido = trim(fgets(STDIN));
            echo "Ingrese número empleado del responsable: ";
            $numeroEmpleado = trim(fgets(STDIN));
            if (!validarNumeros($numeroEmpleado)) {
                echo "Número de empleado debe ser numérico\n";
                return;
            }
            echo "Ingrese dni del responsable: ";
            $dni = trim(fgets(STDIN));
            if (!validarNumeros($dni)) {
                echo "DNI debe ser numérico\n";
                return;
            }
            echo "Ingrese teléfono del responsable: ";
            $telefono = trim(fgets(STDIN));
            if (!validarNumeros($telefono)) {
                echo "Teléfono debe ser numérico\n";
                return;
            }
            $responsable = new Responsable($nombre, $apellido, $dni, $telefono, $numeroEmpleado, $numeroLicencia);
            if ($responsable->insertar()) {
                echo "Responsable guardado exitosamente\n";
            } else {
                echo "Error al guardar el responsable\n";
            }
            break;
        case 2:
            echo "Ingrese número de licencia del responsable a modificar: ";
            $numeroLicencia = trim(fgets(STDIN));
            if (!validarNumeros($numeroLicencia)) {
                echo "Número de licencia debe ser numérico\n";
                return;
            }
            $responsable = new Responsable("", "", "", "", 0, $numeroLicencia); // Inicializar con el número de licencia
            if ($responsable->buscar($numeroLicencia)) {
                echo "Ingrese nuevo nombre del responsable (actual: {$responsable->getNombre()}): ";
                $nombre = trim(fgets(STDIN));
                echo "Ingrese nuevo apellido del responsable (actual: {$responsable->getApellido()}): ";
                $apellido = trim(fgets(STDIN));
                echo "Ingrese nuevo número empleado del responsable (actual: {$responsable->getNumeroEmpleado()}): ";
                $numeroEmpleado = trim(fgets(STDIN));
                if (!validarNumeros($numeroEmpleado)) {
                    echo "Número de empleado debe ser numérico\n";
                    return;
                }
                echo "Ingrese nuevo número de licencia del responsable (actual: {$responsable->getNumeroLicencia()}): ";
                $nuevoNumeroLicencia = trim(fgets(STDIN));
                if (!validarNumeros($nuevoNumeroLicencia)) {
                    echo "Número de licencia debe ser numérico\n";
                    return;
                }
                echo "Ingrese nuevo dni del responsable (actual: {$responsable->getDni()}): ";
                $dni = trim(fgets(STDIN));
                if (!validarNumeros($dni)) {
                    echo "DNI debe ser numérico\n";
                    return;
                }
                echo "Ingrese nuevo teléfono del responsable (actual: {$responsable->getTelefono()}): ";
                $telefono = trim(fgets(STDIN));
                $responsable->cargar($numeroLicencia, $nombre, $apellido, $dni, $telefono, $numeroEmpleado, $nuevoNumeroLicencia);
                if ($responsable->actualizar()) {
                    echo "Responsable actualizado exitosamente\n";
                } else {
                    echo "Error al actualizar el responsable\n";
                }
            } else {
                echo "No se encontró un responsable con el número de licencia proporcionado\n";
            }
            break;
        case 3:
            echo "Ingrese número de licencia del responsable a eliminar: ";
            $numeroLicencia = trim(fgets(STDIN));
            if (!validarNumeros($numeroLicencia)) {
                echo "Número de licencia debe ser numérico\n";
                return;
            }
            $responsable = new Responsable("", "", "", "", 0, $numeroLicencia); // Inicializar con el número de licencia
            if ($responsable->buscar($numeroLicencia)) {
                if ($responsable->eliminar()) {
                    echo "Responsable eliminado exitosamente\n";
                } else {
                    echo "Error al eliminar el responsable\n";
                }
            } else {
                echo "No se encontró un responsable con el número de licencia proporcionado\n";
            }
            break;
        default:
            echo "Acción no válida\n";
    }
}






function gestionarPasajero() {
    echo "Seleccione una acción:\n";
    echo "1. Cargar Pasajero\n";
    echo "2. Modificar Pasajero\n";
    echo "3. Eliminar Pasajero\n";
    $accion = trim(fgets(STDIN));

    switch($accion) {
        case 1:
            echo "Ingrese nombre del pasajero: ";
            $nombre = trim(fgets(STDIN));
            echo "Ingrese apellido del pasajero: ";
            $apellido = trim(fgets(STDIN));
            echo "Ingrese documento del pasajero: ";
            $documento = trim(fgets(STDIN));
            if (!validarNumeros($documento)) {
                echo "Documento debe ser numérico\n";
                return;
            }
            echo "Ingrese teléfono del pasajero: ";
            $telefono = trim(fgets(STDIN));
            if (!validarNumeros($telefono)) {
                echo "Teléfono debe ser numérico\n";
                return;
            }
            echo "Ingrese ID del viaje: ";
            $idViaje = trim(fgets(STDIN));
            if (!validarNumeros($idViaje)) {
                echo "ID del viaje debe ser numérico\n";
                return;
            }
            // Verifica si el viaje existe:
            $viaje = new Viaje($idViaje,"","","","","");
            if ($viaje->buscar($idViaje)) {
                $pasajero = new Pasajero("", "","", "","","");
                $pasajero->cargar($nombre, $apellido, $documento, $telefono, $idViaje);
                if ($pasajero->insertar($idViaje)) {
                    echo "Pasajero guardado exitosamente\n";
                } else {
                    echo "Error al guardar el pasajero\n";
                }
            } else {
                echo "No se encontró un viaje con el ID proporcionado\n";
            }
            break;
        case 2:
            echo "Ingrese id del pasajero a modificar: ";
            $id = trim(fgets(STDIN));
            if (!validarNumeros($id)) {
                echo "Documento debe ser numérico\n";
                return;
            }
            // Verifica si el pasajero existe:
            $pasajero = new Pasajero("", "","", "","",$id);
            if ($pasajero->buscar($id)) {
                echo "Ingrese nuevo nombre del pasajero: ";
                $nombre = trim(fgets(STDIN));
                echo "Ingrese nuevo apellido del pasajero: ";
                $apellido = trim(fgets(STDIN));
                echo "Ingrese nuevo teléfono del pasajero: ";
                $telefono = trim(fgets(STDIN));
                if (!validarNumeros($telefono)) {
                    echo "Teléfono debe ser numérico\n";
                    return;
                }
                // Obtén el idViaje del objeto Empresa
                $empresa = $pasajero->getobjViaje();
                $idViaje = $empresa->getIdViaje();
                $pasajero->cargar($nombre, $apellido, $id, $telefono, $idViaje);
                if ($pasajero->actualizar()) {
                    echo "Pasajero actualizado exitosamente\n";
                } else {
                    echo "Error al actualizar el pasajero\n";
                }
            } else {
                echo "No se encontró un pasajero con el documento proporcionado\n";
            }
            break;
        case 3:
            echo "Ingrese id del pasajero a eliminar: ";
            $id = trim(fgets(STDIN));
            if (!validarNumeros($id)) {
                echo "Documento debe ser numérico\n";
                return;
            }
            // Verifica si el pasajero existe:
            $pasajero = new Pasajero("", "", "", "","",$id);
            if ($pasajero->buscar($id)) {
                if ($pasajero->eliminar()) {
                    echo "Pasajero eliminado exitosamente\n";
                } else {
                    echo "Error al eliminar el pasajero\n";
                }
            } else {
                echo "No se encontró un pasajero con el documento proporcionado\n";
            }
            break;
        default:
            echo "Acción no válida\n";
    }
}



function gestionarViaje() {
    echo "Seleccione una acción:\n";
    echo "1. Cargar Viaje\n";
    echo "2. Modificar Viaje\n";
    echo "3. Eliminar Viaje\n";
    $accion = trim(fgets(STDIN));

    switch($accion) {
        case 1:
            echo "Ingrese destino del viaje: ";
            $destino = trim(fgets(STDIN));
            echo "Ingrese cantidad máxima de pasajeros: ";
            $cantMaxPasajeros = trim(fgets(STDIN));
            if (!validarNumeros($cantMaxPasajeros)) {
                echo "Cantidad máxima de pasajeros debe ser numérica\n";
                return;
            }
            echo "Ingrese importe del viaje: ";
            $importe = trim(fgets(STDIN));
            if (!validarNumeros($importe)) {
                echo "Importe debe ser numérico\n";
                return;
            }
            echo "Ingrese número de Empleado del responsable del viaje: ";
            $numeroEmpleado = trim(fgets(STDIN));
            if (!validarNumeros($numeroEmpleado)) {
                echo "Número de Empleado debe ser numérico\n";
                return;
            }
            echo "Ingrese el ID de la empresa: ";
            $idEmpresa = trim(fgets(STDIN));
            if (!validarNumeros($idEmpresa)) {
                echo "ID de la empresa debe ser numérico\n";
                return;
            }
        
            // Buscar el responsable por número de empleado
            $responsable = new Responsable("", "", 0, 0, $numeroEmpleado, null); // Inicializar con el número de empleado
            if ($responsable->buscar($numeroEmpleado)) {
                // Crear objeto Empresa
                $empresa = new Empresa("", "", $idEmpresa);
                if ($empresa->buscar($idEmpresa)) {
                    $viaje = new Viaje();
                    $viaje->cargar(0, $destino, $cantMaxPasajeros, $empresa, $responsable, $importe);
                    if ($viaje->insertarViaje()) {
                        echo "Viaje guardado exitosamente\n";
                    } else {
                        echo "Error al guardar el viaje: " . $viaje->getError() . "\n";
                    }
                } else {
                    echo "Empresa no encontrada\n";
                }
            } else {
                echo "Responsable no encontrado\n";
            }
            break;
        case 2:
            echo "Ingrese ID del viaje a modificar: ";
            $idViaje = trim(fgets(STDIN));
            if (!validarNumeros($idViaje)) {
                echo "ID del viaje debe ser numérico\n";
                return;
            }
            // Verificar si el viaje existe
            $viaje = new Viaje($idViaje, null, "", 0, 0, 0); // Inicializar con el ID del viaje
            if ($viaje->buscar($idViaje)) {
                echo "Ingrese nuevo destino del viaje: ";
                $destino = trim(fgets(STDIN));
                echo "Ingrese nueva cantidad máxima de pasajeros: ";
                $cantMaxPasajeros = trim(fgets(STDIN));
                if (!validarNumeros($cantMaxPasajeros)) {
                    echo "Cantidad máxima de pasajeros debe ser numérica\n";
                    return;
                }
                echo "Ingrese nuevo importe del viaje: ";
                $importe = trim(fgets(STDIN));
                if (!validarNumeros($importe)) {
                    echo "Importe debe ser numérico\n";
                    return;
                }
                echo "Ingrese nuevo número de licencia del responsable del viaje: ";
                $numeroLicencia = trim(fgets(STDIN));
                if (!validarNumeros($numeroLicencia)) {
                    echo "Número de licencia debe ser numérico\n";
                    return;
                }
                echo "Ingrese el nuevo ID de la empresa: ";
                $idEmpresa = trim(fgets(STDIN));
                if (!validarNumeros($idEmpresa)) {
                    echo "ID de la empresa debe ser numérico\n";
                    return;
                }

                // Buscar el responsable por número de licencia
                $responsable = new Responsable("", "", "", "", 0, $numeroLicencia); // Inicializar con el número de licencia
                if ($responsable->buscar($numeroLicencia)) {
                    $viaje->cargar($idViaje, $responsable, $destino, $cantMaxPasajeros, $importe, $idEmpresa);
                    if ($viaje->actualizarViaje()) {
                        echo "Viaje actualizado exitosamente\n";
                    } else {
                        echo "Error al actualizar el viaje: " . $viaje->getError() . "\n";
                    }
                } else {
                    echo "Responsable no encontrado\n";
                }
            } else {
                echo "No se encontró un viaje con el ID proporcionado\n";
            }
            break;
        case 3:
            echo "Ingrese ID del viaje a eliminar: ";
            $idViaje = trim(fgets(STDIN));
            if (!validarNumeros($idViaje)) {
                echo "ID del viaje debe ser numérico\n";
                return;
            }
            // Verificar si el viaje existe
            $viaje = new Viaje($idViaje, null, "", 0, 0, 0); // Inicializar con el ID del viaje
            if ($viaje->buscar($idViaje)) {
                if ($viaje->eliminarViaje()) {
                    echo "Viaje eliminado exitosamente\n";
                } else {
                    echo "Error al eliminar el viaje: " . $viaje->getError() . "\n";
                }
            } else {
                echo "No se encontró un viaje con el ID proporcionado\n";
            }
            break;
        default:
            echo "Acción no válida\n";
    }
}


function gestionarPersona() {
    echo "Seleccione una acción:\n";
    echo "1. Cargar Persona\n";
    echo "2. Modificar Persona\n";
    echo "3. Eliminar Persona\n";
    $accion = trim(fgets(STDIN));

    switch($accion) {
        case 1:
            echo "Ingrese nombre de la persona: ";
            $nombre = trim(fgets(STDIN));
            echo "Ingrese apellido de la persona: ";
            $apellido = trim(fgets(STDIN));
            echo "Ingrese documento de la persona: ";
            $documento = trim(fgets(STDIN));
            if (!validarNumeros($documento)) {
                echo "Documento debe ser numérico\n";
                return;
            }
            echo "Ingrese teléfono de la persona: ";
            $telefono = trim(fgets(STDIN));
            if (!validarNumeros($telefono)) {
                echo "Teléfono debe ser numérico\n";
                return;
            }
            $persona = new Persona("","","","");
            $persona->cargar($nombre, $apellido, $documento, $telefono);
            if ($persona->insertar()) {
                echo "Persona guardada exitosamente\n";
            } else {
                echo "Error al guardar la persona\n";
            }
            break;
        case 2:
            echo "Ingrese documento de la persona a modificar: ";
            $documento = trim(fgets(STDIN));
            if (!validarNumeros($documento)) {
                echo "Documento debe ser numérico\n";
                return;
            }
            // Verificar si la persona existe
            $persona = new Persona("", "", $documento, "");
            if ($persona->buscar($documento)) {
                echo "Ingrese nuevo nombre de la persona: ";
                $nombre = trim(fgets(STDIN));
                echo "Ingrese nuevo apellido de la persona: ";
                $apellido = trim(fgets(STDIN));
                echo "Ingrese nuevo teléfono de la persona: ";
                $telefono = trim(fgets(STDIN));
                if (!validarNumeros($telefono)) {
                    echo "Teléfono debe ser numérico\n";
                    return;
                }
                $persona->cargar($nombre, $apellido, $documento, $telefono);
                if ($persona->actualizar()) {
                    echo "Persona actualizada exitosamente\n";
                } else {
                    echo "Error al actualizar la persona\n";
                }
            } else {
                echo "No se encontró una persona con el documento proporcionado\n";
            }
            break;
        case 3:
            echo "Ingrese documento de la persona a eliminar: ";
            $documento = trim(fgets(STDIN));
            if (!validarNumeros($documento)) {
                echo "Documento debe ser numérico\n";
                return;
            }
            // Verificar si la persona existe
            $persona = new Persona("", "", $documento, "");
            if ($persona->buscar($documento)) {
                if ($persona->eliminar()) {
                    echo "Persona eliminada exitosamente\n";
                } else {
                    echo "Error al eliminar la persona\n";
                }
            } else {
                echo "No se encontró una persona con el documento proporcionado\n";
            }
            break;
        default:
            echo "Acción no válida\n";
    }
}


do {
    echo "Seleccione una opción:\n";
    echo "1. Gestionar Empresa\n";
    echo "2. Gestionar Responsable\n";
    echo "3. Gestionar Pasajero\n";
    echo "4. Gestionar Viaje\n";
    echo "5. Gestionar Persona\n";
    $opcion = trim(fgets(STDIN));

    switch($opcion) {
        case 1:
            gestionarEmpresa();
            break;
        case 2:
            gestionarResponsable();
            break;
        case 3:
            gestionarPasajero();
            break;
        case 4:
            gestionarViaje();
            break;
        case 5:
            gestionarPersona();
            break;
        default:
            echo "Opción no válida\n";
    }

    echo "¿Desea continuar gestionando datos? (s/n): ";
    $cortar = trim(fgets(STDIN));
} while (strtolower($cortar) === 's');
?>