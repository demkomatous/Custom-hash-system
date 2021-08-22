<?php 
	function CustomHash($string) {
		$string = strtr($string, [' ' => 'wx']);
		$ASCII = str_split($string, 1);
		$loop = count($ASCII);
		$arr = [];

		for ($i=0; $i < $loop; $i++) { 
			$arr [] .= ord($ASCII[$i]);
		}
		
		$implode = implode($arr);

		$StepA = $implode ** 3;
		$StepB = $StepA ** -1;
		$StepC = $StepB / 24 + $StepA / 15;
		$StepD = $StepC ** (1 / 15);
		$StepE = ($StepD * $StepC ** 2) / 4;
		$StepF = $StepA / 18 + $StepB + $StepC + $StepD + $StepE;
		$StepG = strigToBinary($StepF);
		$StepH = shift($StepG, 5);
		$StepI = XORCompare($StepH, $StepG);
		$StepJ = XORCompare($StepI, $StepG);
		$StepKa = $StepJ.$StepH;
		$StepK = shift($StepKa, 18);
		$StepL = shift($StepI, -9);
		$LastStep = $StepG."001".$StepI."010111".$StepH."0110101".$StepK."00110100110".$StepL.$StepJ;
		$Hash = conf2Char($LastStep);

		return $Hash;
	}

	function conf2Char($binary) {
		$binaries = str_split($binary, 8);

		$string = null;
		$betweenString = null;
		$i = 0;

		foreach ($binaries as $binary) {
			$a = 0;
			$logic = 0;
			$betweenString = pack('H*', dechex(bindec($binary)));

			if (is_numeric($betweenString) || ctype_alpha($betweenString)) {
				$string .= $betweenString; 
			} else {
				while ($logic == 0) { 
					$arr = str_split($binaries[$i], 1);

					$p1 = array_splice($arr, 1);
					$arr = array_merge($p1,$arr);

					$binaries[$i] = implode($arr);

					$betweenString = pack('H*', dechex(bindec($binaries[$i])));

					if (is_numeric($betweenString) || ctype_alpha($betweenString)) {
						$string .= $betweenString; 
						$logic = 1;
					} elseif ($a == 8) {
						$logic = 1;
					}
					$a++;
				}

			}

			$i++;
		}
		return $string;
	}

	function strigToBinary($string) {
		$characters = str_split($string);
		$binary = [];

		foreach ($characters as $character) {
			$data = unpack('H*', $character);
			$binary[] = base_convert($data[1], 16, 2);
		}

		return implode($binary);    
	}

	function XORCompare($x, $y) {
	//Split
		$stepX = str_split($x, 1);
		$stepY = str_split($y, 1);

	//loop && compare
		$loop = count($stepX);
		$arr = [];

		for ($i=0; $i < $loop; $i++) {
			$compareX = $stepX[$i];
			$compareY = $stepY[$i];
			if ($compareX == $compareY) {
				$arr[$i] = 1;
			} else {
				$arr[$i] = 0;
			}
		}
		
		return implode($arr);
	}

	function shift($arr, $a) {
		$arr = str_split($arr, 1);

		$p1 = array_splice($arr, $a);
		$arr = array_merge($p1,$arr);

		return implode($arr);
	}
?>