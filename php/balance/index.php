
<?php

if ($_GET['q'] == "admin") {

        print "admin";

} else {


$r_ip = $_SERVER['REMOTE_ADDR'];
$pool_pdm = array("sp-fbp-vau03","sp-fbp-vau04");
$pool_bln = array("sb-fbp-vau03","sb-fbp-vau04");

function rr($pool, $place) {
  $conn = new mysqli("localhost", "bal", "bal", "balance");
  // Check connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }


  $result = $conn->query("SELECT pool FROM standort where ort = '". $place ."'");
  if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $last = $row['pool'];
    }
  }

  $all = count($pool) - 1;

  #echo "<br>". $place . " " . $last . "<br>";
  if ($last == $all) {
    $host = $pool[$last];
    $sql = "UPDATE standort SET pool='0' WHERE ort='".$place."'";
    $conn->query($sql);
  }
  else {
    {
      $host = $pool[$last];
      $p = $last+1;
      $sql = "UPDATE standort SET pool='$p' WHERE ort='".$place."'";
      $conn->query($sql);
    }
  }
  return $host;
}


if (substr($r_ip,0,3) == "192"){
 $redir =  rr($pool_pdm, "pdm");

}
elseif (substr($r_ip,0,3) == "172") {
  $redir =  rr($pool_bln, "bln");
}

        if ($_GET['q'] == "") { $path = "mediacentera"; }
        else { $path = $_GET['q']; }        
        header('Location: '. "http://".$redir."/".$path);
}

 ?>
