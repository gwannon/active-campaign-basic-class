<?php

die;


include_once(dirname(__FILE__)."/config.php");



for ($i = 1; $i <= 11400; $i++) {
  echo "Borrando ".$i."\n";
  $res = curlCallDelete("/accounts/".$i);
}