<?php
set_time_limit(0);
function sigm($num){
    return 1/(1+pow(exp(1), -1*$num));
}
function calc($correct, $hiddenLayer, $output){
    foreach($correct as $in){
        echo "Expected f(".$in[0].") = ".$in[2]." ";
        /*
        $res = sigm($in[0]*$hiddenLayer[0][0]+$in[1]*$hiddenLayer[0][1]+$in[2]*$hiddenLayer[0][2])*$output[0];
        $res +=sigm($in[0]*$hiddenLayer[1][0]+$in[1]*$hiddenLayer[1][1]+$in[2]*$hiddenLayer[1][2])*$output[1];
        */
        $res = array();
        for ($i = 0; $i < sizeof($hiddenLayer); $i++){
            array_push($res, sigm($in[0]*$hiddenLayer[$i][0]));
        }
        array_push($res, 1);
        $result = 0;
        for ($i = 0; $i < sizeof($hiddenLayer); $i++){
            $result += $res[$i]*$output[$i];
        }
        //var_dump($res);

        //$res = sigm($in[0]*$hiddenLayer[0][0])*$output[0] + sigm($in[0]*$hiddenLayer[1][0])*$output[1];
        //$res = sigm($res);
        /*
        if ($res < 0.5){
            $realRes = 0;
        } else {
            $realRes = 1;
        }
        */
        echo " get ".$result."<br>";
    }
}
function myRand(){
    $u = mt_rand()/mt_getrandmax(); 
    $v = mt_rand()/mt_getrandmax(); 
    return sqrt(-2 * log($u)) * cos(2 * M_PI * $v);
}
$era = 1000;
$learningRate = 0.000001;

$correct = file_get_contents("data");
$correct = json_decode($correct, true);
/*
$correct = array(
    array(0, 0, 0, 0),
    array(0, 0, 1, 0),
    array(0, 1, 0, 1),
    array(0, 1, 1, 1),
    array(1, 0, 0, 1),
    array(1, 0, 1, 1),
    array(1, 1, 0, 1),
    array(1, 1, 1, 0)
);
$correct = array(
    array(1, 0, 1, 1)
);
*/
$output = array(
    myRand(), 
    myRand(),
    myRand(),
    myRand(),
    myRand(), 
    myRand(), 
    myRand(), 
    myRand(), 
    myRand(), 
    myRand(), 
    myRand(), 
    myRand(),
    myRand(), 
    myRand(),
    myRand(),  
    myRand()
); 



$hiddenLayer = array(
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand()),
    array(myRand(),myRand())   
); 
var_dump($output);
echo "<br>";
var_dump($hiddenLayer);
echo "<br>";
calc($correct, $hiddenLayer, $output);
echo "<br>";
for ($eraNum = 0; $eraNum < $era; $eraNum++ ){
    //echo "Current era - ".$eraNum."<br>";
    for($inputNum = 0; $inputNum <  sizeof($correct); $inputNum++){
        $input = $correct[$inputNum];
        $hiddenResult = array(0, 0); 
        for ($neuron = 0; $neuron < sizeof($hiddenLayer); $neuron++){
            $res = 0;
            for ($weightNum = 0; $weightNum < (sizeof($hiddenLayer[$neuron]) -1); $weightNum++){
                $res += $hiddenLayer[$neuron][$weightNum] * $input[$weightNum];
            }
            $res += $hiddenLayer[$neuron][2];
            array_push($hiddenResult, sigm($res)); //! сделать сигм от реза
        }
        array_push($hiddenResult, 1);
        $neuralResult = 0;
        for ($outWeight = 0; $outWeight < sizeof($output); $outWeight++){
            $neuralResult += $hiddenResult[$outWeight]*$output[$outWeight];
        }
        //$neuralResult = sigm($neuralResult); //! это убрали сигмоиду с результата
        //var_dump($neuralResult);
        $error = $neuralResult - $input[sizeof($input) - 1];
        $weightDelta = $error;// * $neuralResult * (1 - $neuralResult); //!
        for ($neuron = 0; $neuron < sizeof($output); $neuron++){
            //var_dump($output);
            $output[$neuron] = $output[$neuron] - $hiddenResult[$neuron] * $weightDelta * $learningRate;
            $neuronError = $output[$neuron] * $weightDelta;
            $neuronWeightDelta = $neuronError * $hiddenResult[$neuron] * (1 - $hiddenResult[$neuron]); //!
            /*
            echo "<br>!!!!!!!!!".$error." ".$neuralResult."!!!!!!!!!!<br>";
            echo " => "; 
            var_dump($output);
            echo "<br>";
            */
            if ($neuron != (sizeof($output) - 1)) {
            for($neuronWeightNum = 0; $neuronWeightNum < sizeof($hiddenLayer[$neuron]); $neuronWeightNum++){
                //echo $hiddenLayer[$neuron][$neuronWeightNum];
                //echo "(".$hiddenLayer[$neuron][$neuronWeightNum]." ".$input[$neuronWeightNum]." ".$neuronWeightDelta.")";
                $hiddenLayer[$neuron][$neuronWeightNum] = $hiddenLayer[$neuron][$neuronWeightNum] - $input[$neuronWeightNum]* $neuronWeightDelta * $learningRate;
                //echo " => ".$hiddenLayer[$neuron][$neuronWeightNum]."<br>";
            }
            }
            //echo "**********<br>";
        }
        /*
        var_dump($hiddenLayer);
        echo "!!!!".$inputNum."!!!!!"; 
        echo "<br>";
        */

    }
}
var_dump($output);
echo "<br>";
var_dump($hiddenLayer);
echo "<br>";
calc($correct, $hiddenLayer, $output);
?>