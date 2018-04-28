<?php


date_default_timezone_set('America/Mexico_City');
class Connection{
  //GIO
  public static $dbname="fimpro_visitantes";
  public static $usuario="developer";
  public static $password="developer";
  public static $server="localhost";

  static function connect(){

    try{
      $conexion = new PDO( 'mysql:dbname='.self::$dbname.';host='.self::$server, self::$usuario, self::$password,
      array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ));

    }catch( PDOException $e ){
      die( 'Error en conexion:'.$e->getMessage() );

    }

    return $conexion;


  }

  static function disconnect($conexion){
    $conexion = null;
  }

}

Connection::connect();


?>
