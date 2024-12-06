<?php
class DB
{
    var $a_conexion;
    var $a_servidor;
    var $a_user;
    var $a_pwd;
    var $a_baseDatos;
    var $a_bloqRegistros;
    var $a_numeRegistros;

    function __construct()
    {
        $this->a_servidor = "localhost";
        $this->a_user = "root";
        $this->a_pwd = "";
        $this->a_baseDatos = "perlux";
    }

    function open()
    {
        $this->a_conexion = mysqli_connect($this->a_servidor, $this->a_user, $this->a_pwd, $this->a_baseDatos);
        if (!$this->a_conexion) {
            die("Error al conectar con la base de datos: " . mysqli_connect_error());
        }
    }

    function close()
    {
        mysqli_close($this->a_conexion);
    }

    function query($query)
    {
        $this->open();
        $this->a_bloqRegistros = mysqli_query($this->a_conexion, $query);
        if (!$this->a_bloqRegistros) {
            die("Error en la consulta: " . mysqli_error($this->a_conexion));
        }
        $this->a_numeRegistros = mysqli_num_rows($this->a_bloqRegistros);
    }

    function getRecord($query)
    {
        $this->open();
        $this->a_bloqRegistros = mysqli_query($this->a_conexion, $query);
        if (!$this->a_bloqRegistros) {
            die("Error en la consulta: " . mysqli_error($this->a_conexion));
        }
        $this->a_numeRegistros = mysqli_num_rows($this->a_bloqRegistros);
        return mysqli_fetch_object($this->a_bloqRegistros);
    }
}
// Crear la instancia global de la base de datos
$db = new Db();
