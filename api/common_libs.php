<?php

  /*
		Convierte una fecha con formato normal (dd/mm/aaaa), en uno de formato sql (aaaa/mm/dd)

			* date:	String, fecha con formato normal
	*/
	function getDateSQL($date){
		if($date){
			$arr=explode("/",$date);
			if(sizeof($arr)==1)
				$arr=explode("-",$date);
			$date=$arr[2]."-".$arr[1]."-".$arr[0];
		}

		return $date;
	}


	/*
		Convierte una fecha con formato sql (aaaa/mm/dd), en uno de formato normal (dd/mm/aaaa)

			* date:	String, fecha con formato sql
	*/
	function getDateNormal($date){
		if($date){
			$arr=explode("-",$date);
			if(sizeof($arr)==1)
				$arr=explode("/",$date);
			$date=$arr[2]."/".$arr[1]."/".$arr[0];
		}

		return $date;
	}
  
  /*
		Trunca una cadena de tiempo hh:mm:ss a formato hh:mm

			* date:	String, tiempo con formato hh:mm:ss
	*/
	function getTimehhmm($time){
    $result = "";
		
    if($time){
			$arr=explode(":",$time);
			if(sizeof($arr) >= 2){
			 $result=$arr[0].":".$arr[1];
      }
		}

		return $result;
	}

?>