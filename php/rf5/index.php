<?php

$r_ip = $_SERVER['REMOTE_ADDR'];
$pool_pdm = array("sp-fbp-vau03","sp-fbp-vau04");
$pool_bln = array("sb-fbp-vau03","sb-fbp-vau04");

function rr($pool, $place) {
  if(!file_exists($place)) { file_put_contents($place,0); }
  $all = count($pool) - 1;
  $last = file_get_contents($place);
  #echo "<br>". $place . " " . $last . "<br>";
  if ($last == $all) {
    $host = $pool[0];
    file_put_contents($place, 0);
  }
  else {
    {
      $host = $pool[$last+1];
      file_put_contents($place, $last+1);
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

header('Location: '. "http://".$redir."/mediacentera");


 ?>
