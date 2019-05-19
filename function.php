<?php
function fun($num){
    return pow(cosh($num), 2);
}
$result = array();

for ($i = -10; $i <= 10; $i += 0.1){
    //array_push($result, array($i, pow($i,2), pow($i,4), fun($i)));
    array_push($result, array($i,1, fun($i)));
}
$result = json_encode($result);
file_put_contents("data",$result);

?>