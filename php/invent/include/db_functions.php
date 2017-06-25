<?php

  function gen_uuid() {
      return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
          // 32 bits for "time_low"
          mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

          // 16 bits for "time_mid"
          mt_rand( 0, 0xffff ),

          // 16 bits for "time_hi_and_version",
          // four most significant bits holds version number 4
          mt_rand( 0, 0x0fff ) | 0x4000,

          // 16 bits, 8 bits for "clk_seq_hi_res",
          // 8 bits for "clk_seq_low",
          // two most significant bits holds zero and one for variant DCE1.1
          mt_rand( 0, 0x3fff ) | 0x8000,

          // 48 bits for "node"
          mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
      );
  }


  function kw($jahr) // Gibt die Anzahl der Kalenderwochen eines gegebenen Jahrs (Format YYYY) zurück
  {
  $letzteKW = date("W",strtotime("31.12.".$jahr));
  $anzahlKW = ($letzteKW == 1) ? 52 : $letzteKW;
  return $anzahlKW;
  }

  function aktuelle_kw()
  {
    $kw = 0;
    $kw = date('W', time());
    return $kw;
  }

  function aktuelles_jahr()
  {
    $year = date("Y");
    return $year;
  }

  function sqlite_connect($sqlite_db)
  {
    try {
      $con = new SQLite3($sqlite_db);
      $con->busyTimeout(5000);
    }
    catch (Exception $exception) {
      echo "Konnte die Datenbank " . $sqlite_db . " nicht öffnen, lede eine neue an!";
    }

    return $con;
  }

	function db_connect($server, $port, $db, $user, $passwd)
	{

		$con = new mysqli($server, $user, $passwd, $db, $port);
		if ($con->connect_errno)
		{
    		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;		}


		return $con;
	}

	function db_disconnect($con)
	{
		if ($con->close())
		{


		}

	}

	function db_select($con, $stm)
	{
		$results = null;


		return $results;

	}

	function db_update($con, $stm)
	{

	}
?>
