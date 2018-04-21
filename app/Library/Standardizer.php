<?php
namespace App\Library;
use Illuminate\Support\Facades\File;
use Exception;
use Storage;

class Standardizer{
	protected $rawData;
	protected $dataType;
	protected $Dictionary;
	protected $mean;
	protected $stdDev;
	// Construct and calculate needed information by each column type
	public function __construct(array $rawData ,array $dataType){
		$this->rawData = $rawData;
		$this->dataType = $dataType;
		$this->mean = array();
		$this->stdDev = array();
		$this->Dictionary = array();
		for($i = 0 ; $i < $this->getTotalInput() ; $i++){
			if($dataType[$i] == 0){
				$this->mean[$i] = $this->getMean($i);
				$this->stdDev[$i] = $this->getStdDev($i);
			}
			else if( $dataType[$i] == 1 || $dataType[$i] == 2 || $dataType[$i] == 3 ){
				$this->Dictionary[$i] = $this->getDistinctValues($i);
				$this->mean[$i] = round($this->getMean($i));
				// echo "Dictionary for column ".$i."<br>";
				// print_r($this->Dictionary[$i]);
				// echo "<br>";
			}
		}
	}

	public function fillMissingValue($data){
		for($i = 0 ; $i < $this->getTotalData() ; $i++){
			for($j = 0 ; $j < $this->getTotalInput(); $j++){
				if($this->isMissing($i,$j)){
					$data[$i][$j] =$this->getMean($j);
				}
			}
		}
		return $data;
	}


	public function getTotalData(){
		return sizeof($this->rawData);
	}

	// Total number of input
	public function getTotalInput(){
		return sizeof($this->dataType);
	}

	// Get total number of distinct value in a given categorical column
	public function getTotalDistinctVal($column){
		if($this->dataType[$column] != 1 && $this->dataType[$column] != 2 && $this->dataType[$column] != 3){
			throw new Exception('Error while getting total distinct values at non-categorical column '.$column);
		}
		return sizeof($this->Dictionary[$column]);
	}

	public function isMissing($row,$column){
		return (strcmp($this->rawData[$row][$column] , '?') == 0);
	}

	public function getMean($column){
		$sum = 0;
		for($i = 0 ; $i < $this->getTotalData() ; $i++){
			if(!$this->isMissing($i,$column)){
				if($this->dataType[$column] == 0)
					$sum += $this->rawData[$i][$column];
				else{
					$sum += $this->Dictionary[$column][$this->rawData[$i][$column]];
				}
			}
		}
		$mean = $sum/($this->getTotalData());
		if($this->dataType[$column] != 0){
			$mean = array_search(round($mean),$this->Dictionary[$column]);
		}
		return $mean;
	}

	public function getStdDev($column){
		if($this->dataType[$column] != 0){
			throw new Exception('Error while normalizing column '.$column.', only numerical data have std dev');
		}

		$sum = 0;
		for($i = 0 ; $i < $this->getTotalData() ; $i++){
			if(!$this->isMissing($i,$column))
				$sum += pow(($this->rawData[$i][$column] - $this->mean[$column]),2);
		}
		return sqrt($sum / ($this->getTotalData()-1));
	}

	public function normalizeBinary($data,$column){
		if($this->dataType[$column] != 1){
			throw new Exception('Error while normalizing column '.$column.' data must be binary');
		}
		$rs = 1;
		if($this->Dictionary[$column][$data] == 0)
			$rs = -1;
		return $rs;
	}

	public function normalizeNumerical($data,$column){
		if($this->dataType[$column] != 0){
			throw new Exception('Error while normalizing column '.$column.' data must be numerical');
		}
		return ($data - $this->mean[$column])/($this->stdDev[$column]);

	}

	public function getDistinctValues($column){
		if($this->dataType[$column] != 1 && $this->dataType[$column] != 2 && $this->dataType[$column] != 3){
			throw new Exception('Error while normalizing column '.$column.', only categorical values need total distinct value');
		}
		$distinctMap = array();
		$cnt = 0;
		for($i = 0; $i < $this->getTotalData() ; $i++){
			$value = $this->rawData[$i][$column];

			if(!$this->isMissing($i,$column)){
				if(!array_key_exists($value, $distinctMap)){
					$distinctMap[$value] = $cnt;
					$cnt++; 
				}
			}
		}
		return $distinctMap;
	}

	public function normalizeData(){
		$this->rawData = $this->fillMissingValue($this->rawData);
		$result = array();
		for($i = 0; $i < $this->getTotalData(); $i++){
			$cnt = 0;
			$result[$i] = array();
			for($j = 0; $j < $this->getTotalInput(); $j++){

				// Normalize numerical value
				if($this->dataType[$j] == 0){
					$result[$i][$cnt] = $this->normalizeNumerical($this->rawData[$i][$j],$j);
					$cnt++;
				}
				// Normalize binary value
				else if($this->dataType[$j] == 1){
					$result[$i][$cnt] = $this->normalizeBinary($this->rawData[$i][$j],$j);
					$cnt++;
				}
				//Normalize categorical value-x
				else if($this->dataType[$j] == 2){
					$rawValue = $this->rawData[$i][$j];
					$storedValue = $this->Dictionary[$j][$rawValue];
					$totalDistinctValues = sizeof($this->Dictionary[$j]);
					for($t = 0 ; $t < $totalDistinctValues - 1; $t++){		
						if($storedValue == $totalDistinctValues - 1){
							$result[$i][$cnt] = -1;
						}
						else{
							if($storedValue == $t)
								$result[$i][$cnt] = 1;
							else
								$result[$i][$cnt] = 0;
						}
						$cnt++;
					}
				}

				// Normalize categorical value-y
				else if($this->dataType[$j] == 3){
					$rawValue = $this->rawData[$i][$j];
					$storedValue = $this->Dictionary[$j][$rawValue];
					$totalDistinctValues = sizeof($this->Dictionary[$j]);
					for($t = 0 ;$t < $totalDistinctValues ; $t++){
						if($storedValue == $t)
							$result[$i][$cnt] = 1;
						else
							$result[$i][$cnt] = 0;
						$cnt++;
					}
				}
			}
		}
		return $result;
	}

	public function NormalizeFromData($data){
		$data = $this->fillMissingValue($data);
		$result = array();
		$cnt = 0;
		for($j = 0; $j < $this->getTotalInput(); $j++){
			// Normalize numerical value
			if($this->dataType[$j] == 0){
				$result[$cnt] = $this->normalizeNumerical($data[$j],$j);
				$cnt++;
			}
			// Normalize binary value
			else if($this->dataType[$j] == 1){
				$result[$cnt] = $this->normalizeBinary($data[$j],$j);
				$cnt++;
			}
			//Normalize categorical value-x
			else if($this->dataType[$j] == 2){
				// print_r($distinctValues);
				// echo "<br>";
				$rawValue = $data[$j];
				$storedValue = $this->Dictionary[$j][$rawValue];
				$totalDistinctValues = sizeof($this->Dictionary[$j]);
				for($t = 0 ; $t < $totalDistinctValues - 1; $t++){		
					if($storedValue == $totalDistinctValues - 1){
						$result[$cnt] = -1;
					}
					else{
						if($storedValue == $t)
							$result[$cnt] = 1;
						else
							$result[$cnt] = 0;
					}
					$cnt++;
				}
			}
		}
		return $result;
	}

	// Revert to original output from array of output returned by neural network
	public function revertOutput($NNOutput){
		if($this->getTotalInput() > 1 || $this->dataType[0] != 3){
			throw new Exception('Standardizer must be of outputs');
		}
		//Pick the node with highest probability
		$output_node = array_keys($NNOutput, max($NNOutput))[0];
		$originalOutput = array_search($output_node,$this->Dictionary[0]);
		return $originalOutput;
		
	}

	public function store(){
		$array = get_object_vars($this);
		$json = json_encode($array);
		File::put(storage_path('app\standardizerData.txt'),$json);
		// $str = '';
		// for($i = 0 ; $i < $this->getTotalInput(); $i++){
		// 	$str = '{'
		// }
	}


}

?>