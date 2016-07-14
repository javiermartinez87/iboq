<?php

include($_SERVER ['DOCUMENT_ROOT'] . '/control/seguridad.php');

class Proyecto {

    private $id;
    private $nombre;
    private $partidas;
    private $fecha;
    private $pais;
    private $comunidad;
    private $ciudad;
    private $poblacion;
    private $tipologia;
    private $descripcion;
    private $lat;
    private $lon;

    public function __construct($nombre = '', $pais = '', $comunidad = '', $ciudad = '', $poblacion = '', $tipologia = '', $descripcion = '', $fecha = '', $lat = '', $lon = '') {
        $this->id = ('nuevo');
        $this->nombre = ($nombre);
        $this->pais = ($pais);
        $this->comunidad = ($comunidad);
        $this->ciudad = ($ciudad);
        $this->poblacion = ($poblacion);
        $this->tipologia = ($tipologia);
        $this->lat = ($lat);
        $this->lon = ($lon);
        $this->descripcion = ($descripcion);
        $this->fecha = ($fecha);
        $this->partidas = array();
    }

    public function carga_bd($id) {
        include($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');
        $query = $mysqli->query("select * from proyecto where id = '$id' limit 1");
        if ($query->num_rows == 1) {
            $datos = $query->fetch_assoc();
            $this->id = $datos['id'];
            $this->nombre = $datos['nombre'];
            $this->pais = $datos['pais'];
            $this->comunidad = $datos['comunidad'];
            $this->ciudad = $datos['ciudad'];
            $this->poblacion = $datos['poblacion'];
            $this->tipologia = $datos['tipologia'];
            $this->lat = $datos['lat'];
            $this->lon = $datos['lon'];
            $this->descripcion = $datos['descripcion'];
            $this->fecha = $datos['f_ejecucion'];
            $this->partidas = array();
        }
    }

    public function devuelve_datos() {
        $datos = array();
        $datos['id'] = $this->id ;
        $datos['nombre'] = $this->nombre;
         $datos['pais'] = $this->pais;
        $datos['comunidad'] = $this->comunidad;
        $datos['ciudad'] = $this->ciudad;
        $datos['poblacion'] = $this->poblacion;
        $datos['tipologia'] = $this->tipologia;
        $datos['lat'] = $this->lat;
        $datos['lon'] = $this->lon;
        $datos['descripcion'] = $this->descripcion;
        $datos['f_ejecucion'] = $this->fecha;
        return $datos;
    }

    public function add_partida($partida) {
        array_push($this->partidas, $partida);
    }

    public function guarda_bd() {
        include($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');
        $query = $mysqli->query("insert into proyecto(idusuario,nombre,descripcion,ciudad,poblacion,f_ejecucion,pais,comunidad,tipologia,lat,lon) "
                . "values('$_SESSION[idusuario]','" . ($this->nombre) . "','" . ($this->descripcion) . "'"
                . ",'" . ($this->ciudad) . "','" . ($this->poblacion) . "'"
                . ",'" . ($this->fecha) . "'"
                . ",'" . ($this->pais) . "'"
                . ",'" . ($this->comunidad) . "'"
                . ",'" . ($this->tipologia) . "'"
                . ",'" . ($this->lat) . "'"
                . ",'" . ($this->lon) . "'"
                . ")");
        if (!$query) {
            echo '<script>this.parent.error_modal("Error guardando el proyecto");</script>';
            exit;
        } else {
            $id = $mysqli->insert_id;
            $this->id = $id;
            foreach ($this->partidas as $partida) {
                $partida->guarda_bd($id);
            }
        }
    }

}

class Partida {

    private $codigo;
    private $nombre;
    private $descripcion;
    private $cantidad;
    private $presupuesto;

    public function __construct($codigo = '', $nombre = '', $descripcion = '', $cantidad = 0, $presupuesto = 0, $clasifi_real = '') {
        $this->codigo = $codigo;
        $this->nombre = ($nombre);
        $this->descripcion = ($descripcion);
        $this->cantidad = $cantidad;
        $this->presupuesto = $presupuesto;
        $this->clasifi_real = $clasifi_real;
    }

    public function guarda_bd($id_proyecto) {
        include($_SERVER ['DOCUMENT_ROOT'] . '/include/conectar.php');
        $query = $mysqli->query("insert into partida(id_proyecto,nombre,descripcion,cantidad,presupuesto) values('$id_proyecto','" . ($this->nombre) . "','" . ($this->descripcion) . "','$this->cantidad','$this->presupuesto')");
        if (!$query) {
            echo '<script>this.parent.error_modal("Error guardando las partidas");</script>';
            exit;
        }
        $id_partida = $mysqli->insert_id;      
        if ($this->clasifi_real != '') {                  
            $query = $mysqli->query("insert into clasifi_real(id_partida,clasificacion) values ('$id_partida','$this->clasifi_real')");
        }
    }

}
