<?php
set_time_limit(0);
function sigm($num){
    return 1/(1+pow(exp(1), -1*$num));
}
function calc($correct, $hiddenLayer, $output){
    foreach($correct as $in){
        echo "Expected ".$in[3]." ";
        $res = sigm($in[0]*$hiddenLayer[0][0]+$in[1]*$hiddenLayer[0][1]+$in[2]*$hiddenLayer[0][2])*$output[0];
        $res +=sigm($in[0]*$hiddenLayer[1][0]+$in[1]*$hiddenLayer[1][1]+$in[2]*$hiddenLayer[1][2])*$output[1];
        $res = sigm($res);
        
        if ($res < 0.5){
            $realRes = 0;
        } else {
            $realRes = 1;
        }
        echo " get ".$realRes." (".$res.")<br>";
    }
}
function myRand(){
    $u = mt_rand()/mt_getrandmax(); 
    $v = mt_rand()/mt_getrandmax(); 
    return sqrt(-2 * log($u)) * cos(2 * M_PI * $v) * 3;
}

$era = 10000;
$learningRate = 0.1;

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
/*
$correct = array(
    array(1, 0, 1, 1)
);
*/
$output = array(
    lcg_value(), 
    lcg_value()
); 
$hiddenLayer = array(
    array(lcg_value(), lcg_value(), lcg_value()),
    array(lcg_value(), lcg_value(), lcg_value())
); 
var_dump($output);
echo "<br>";
var_dump($hiddenLayer);
echo "<br>";

for ($eraNum = 0; $eraNum < $era; $eraNum++ ){
    for($inputNum = 0; $inputNum <  sizeof($correct); $inputNum++){
        $input = $correct[$inputNum];
        $hiddenResult = array(0, 0); 
        for ($neuron = 0; $neuron < sizeof($hiddenLayer); $neuron++){
            for ($weightNum = 0; $weightNum < sizeof($hiddenLayer[$neuron]); $weightNum++){
                $hiddenResult[$neuron] += $hiddenLayer[$neuron][$weightNum] * $input[$weightNum];
            }
            $hiddenResult[$neuron] = sigm($hiddenResult[$neuron]); //!
        }
        $neuralResult = 0;
        for ($outWeight = 0; $outWeight < sizeof($output); $outWeight++){
            $neuralResult += $hiddenResult[$outWeight]*$output[$outWeight];
        }
        $neuralResult = sigm($neuralResult); //!
        //var_dump($neuralResult);
        $error = $neuralResult - $input[sizeof($input) - 1];
        $weightDelta = $error * $neuralResult * (1 - $neuralResult); //!
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
            for($neuronWeightNum = 0; $neuronWeightNum < sizeof($hiddenLayer[$neuron]); $neuronWeightNum++){
                //echo $hiddenLayer[$neuron][$neuronWeightNum];
                //echo "(".$hiddenLayer[$neuron][$neuronWeightNum]." ".$input[$neuronWeightNum]." ".$neuronWeightDelta.")";
                $hiddenLayer[$neuron][$neuronWeightNum] = $hiddenLayer[$neuron][$neuronWeightNum] - $input[$neuronWeightNum]* $neuronWeightDelta * $learningRate;
                //echo " => ".$hiddenLayer[$neuron][$neuronWeightNum]."<br>";
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