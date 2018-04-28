<?php

require_once("Connection.php");
require_once("common_libs.php");

class Actividad{
  //TODO GIO Checar tabla
  private static $table="ACTIVIDAD";
  
  
  
  public static function catalogo(){    
    
    $result = false;
  
    //TOOO GIO Checar campos
    $sql = "SELECT ACT_ID, ACT_NOMBRE, ACT_FECHA, ACT_HORA FROM ". self::$table ." ;";
    
    
    $connection = Connection::connect();
    
    $stmt = $connection->prepare( $sql );
    
    $stmt->execute();
    
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = null;
    
    Connection::disconnect($connection);
  
    if (count($rows) > 0) {
      for( $index = 0; $index < sizeof($rows); $index++ ){
        $result[] = array(
          "id" => ($rows[$index]["ACT_ID"] == null? '':$rows[$index]["ACT_ID"] ),
          "nombre" => ($rows[$index]["ACT_NOMBRE"] == null? '':$rows[$index]["ACT_NOMBRE"] ),
          "fecha" => (($rows[$index]["ACT_FECHA"] == null || strcmp($rows[$index]["ACT_FECHA"], '0000-00-00') == 0)? '': getDateNormal($rows[$index]["ACT_FECHA"] )),
          "hora" => ($rows[$index]["ACT_HORA"] == null? '':getTimehhmm($rows[$index]["ACT_HORA"] ))
        );      
      };
    
    
    }
  
    //$resultado = false;
    //if( $success > 0 ){
    //  $resultado = true;
    //}
  
    return $result;
    //return $resultado;     
  }
  
  public static function busqueda($params){
    $nombre = $params['nombre'];
    $fecha = $params['fecha'];
    $result = false;
  
    if( $nombre != "" || $fecha != "" ){
  
      //TOOO GIO Checar campos
      $sql = "SELECT ACT_ID, ACT_NOMBRE, ACT_FECHA, ACT_HORA FROM ". self::$table ." WHERE ";
      
//       $parametros=array();
      
      if( $nombre != "" ){
//         $sql .= " ( ACT_NOMBRE LIKE ? )";
//         $parametros[]='%'.$nombre.'%';
        $sql .= " ( ACT_NOMBRE LIKE '%".$nombre."%'  )";
      }
      
      
      if( $fecha != "" ){
        if( $nombre != "" ){
          $sql .= " AND ";
        }
//         $sql .= " ACT_FECHA = ? ";
//         $parametros[]=getDateSQL($fecha);
        $sql .= " ACT_FECHA = '".getDateSQL($fecha)."' ";

      }
  
      //$sql = "SELECT ACT_ID, ACT_NOMBRE, ACT_FECHA, ACT_HORA FROM ACTIVIDAD WHERE ACT_NOMBRE LIKE '%".$nombre."%' "; 
      
      $connection = Connection::connect();
      
      $stmt = $connection->prepare( $sql );
      
      $stmt->execute( $parametros );
      
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      
      if (count($rows) > 0) {
        for( $index = 0; $index < sizeof($rows); $index++ ){
          $result[] = array(
            "id" => ($rows[$index]["ACT_ID"] == null? '':$rows[$index]["ACT_ID"] ),
            "nombre" => ($rows[$index]["ACT_NOMBRE"] == null? '':$rows[$index]["ACT_NOMBRE"] ),
            "fecha" => (($rows[$index]["ACT_FECHA"] == null || strcmp($rows[$index]["ACT_FECHA"], '0000-00-00') == 0)? '': getDateNormal($rows[$index]["ACT_FECHA"] )),
            "hora" => ($rows[$index]["ACT_HORA"] == null? '':getTimehhmm($rows[$index]["ACT_HORA"] ))
          );      
        };
      
      
      }
      
      $stmt = null;
      
      Connection::disconnect($connection);
    
      //$resultado = false;
      //if( $success > 0 ){
      //  $resultado = true;
      //}
    }
  
    //return "".$sql."--".$nombre."-".$fecha."-";
  
    return $result;
    //return $resultado;     
  }
  
  
  public static function crear($params){
    $connection;
    $stmt;
      
    $success = false;
    $fecha = getDateSQL($params['fecha']);
  
    
    try{
      //TOOO GIO Checar campos
      $sql = "INSERT INTO ". self::$table ." (ACT_ID, ACT_NOMBRE, ACT_FECHA, ACT_HORA, ACT_ESPACIO) ".
             "VALUES ( NULL, ?, ?, ?, ? );";
      
      $connection = Connection::connect();
      $stmt = $connection->prepare( $sql );
      $stmt->execute( array(
        
         $params['nombre'],
         $fecha,
         $params['hora'],
         $params['espacio']
         
      ));
      
      //$rows = $stmt->fetch(PDO::FETCH_ASSOC);
      
      $id = $connection->lastInsertId();
      
      $success = array( "id"=> $id );
      
      //$success = $stmt->rowCount();
      
    }catch( Exception $e ){
      error_log( "Actividad/crear: ".$e->getMessage() );
      if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        $success = -1;
      }else{
        $success = false;
      } 
    }finally{
      try{
        $stmt = null;
        Connection::disconnect($connection);
      }catch( Exception $e2 ){
        error_log( "Actividad/crear(finally): ".$e2->getMessage() );
      }
    }
  
    
  
    //$resultado = false;
    //if( $success > 0 ){
    //  $resultado = true;
    //}
  
    return $success;
    //return $resultado;     
  }
  
  
  public static function info($params){
    
    $result = false;
  
    //TOOO GIO Checar campos
    //$sql = "SELECT INV_FOTO, INV_BIOGRAFIA FROM INVITADO WHERE INV_ID = ?";
    $sql = "SELECT ACT_NOMBRE, ACT_FECHA, ACT_HORA, ACT_ESPACIO FROM ". self::$table ." WHERE ACT_ID = ? ;";
    
    
    $connection = Connection::connect();
    
    $stmt = $connection->prepare( $sql );
    
    $stmt->execute(array(
      $params['id']
    ));
    
    $rows = $stmt->fetch(PDO::FETCH_ASSOC);
    $filas = $stmt->rowCount();
    
    $stmt = null;
    
    Connection::disconnect($connection);
  
    if( $filas == 1 ){
    
      $result = array(
        "nombre" => ($rows["ACT_NOMBRE"] == null? '':$rows["ACT_NOMBRE"] ),
        "fecha" => (($rows["ACT_FECHA"] == null || strcmp($rows["ACT_FECHA"], '0000-00-00') == 0)? '': getDateNormal($rows["ACT_FECHA"] )),
        "hora" => ($rows["ACT_HORA"] == null? '':getTimehhmm($rows["ACT_HORA"] )),
        "espacio" => ($rows["ACT_ESPACIO"] == null? '':$rows["ACT_ESPACIO"] )
      );
    
    }
  
    //$resultado = false;
    //if( $success > 0 ){
    //  $resultado = true;
    //}
  
    return $result;
    //return $resultado;     
  }
  
  public static function modificar($params){
    $stmt;
    $connection;
  
    $success = false;
    $fecha = getDateSQL($params['fecha']);
  
    try{
      $connection = Connection::connect();
      
      $sql = "SELECT 1 FROM ". self::$table ." WHERE ACT_ID = ? ;";
      $stmt = $connection->prepare( $sql );
      $stmt->execute( array(
         $params['id']
      ));
      
      $rows = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if( $rows > 0 ){
        //realiza el update
        $sql = "UPDATE ". self::$table ." SET ACT_NOMBRE = ?, ACT_FECHA = ?, ACT_HORA = ?, ACT_ESPACIO = ? WHERE ACT_ID = ? ;";
        $stmt = $connection->prepare( $sql );
        $stmt->execute( array(
        
          $params['nombre'],
          $fecha,
          $params['hora'],
          $params['espacio'],
          $params['id']
        ));
      
        //$rows = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $success = 1;
        
        
      }
      
    }catch( Exception $e ){
      error_log( "Actividad/modificar: ".$e->getMessage() );
      if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
        $success = -1;
      }else{
        $success = false;
      }
    }finally{
      try{
        $stmt = null;
        Connection::disconnect($connection);
      }catch( Exception $e2 ){
        error_log( "Actividad/modificar(finally): ".$e2->getMessage() );
      }
    }
  
    //$resultado = false;
    //if( $success > 0 ){
    //  $resultado = true;
    //}
  
    return $success;
    //return $resultado;     
  }
  
  
  public static function eliminar($params){
    $result = false;
  
    //TOOO GIO Checar campos
    $sql = "SELECT AIN_ACTIVIDAD FROM ACTIVIDADINVITADO WHERE AIN_ACTIVIDAD=?;";

    $connection = Connection::connect();

    $stmt = $connection->prepare( $sql );
    $stmt->execute(array($params["id"]));

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Connection::disconnect($connection);

    if(count($rows)==0){
      $sql = "DELETE FROM ". self::$table ." WHERE ACT_ID=?;";

      $connection = Connection::connect();

      $stmt = $connection->prepare( $sql );
      $stmt->execute(array($params["id"]));


    Connection::disconnect($connection);

      $result=true;   
    }

    return $result;

  }
 
  
  

}

// print_r( User::login('gio', 'gio') );


?>
