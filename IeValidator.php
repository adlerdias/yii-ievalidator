<?php

/**
 * IeValidator class file.
 *
 * @author Adler S Dias <adlersd@gmail.com>
 * @copyright Copyright &copy; 2008-2012 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 * IeValidator valida uma Inscrição estadual brasileira conforme algoritimo de verificação de cada estado.
 * @author Adler S Dias <adlersd@gmail.com>
 * @version 0.1
 * 
 * Usage:
 * 
 * public function rules() {
 *   	return array(
 *       	array('firstKey', 'ext.validators.IeValidator',
 *           	'with'=>'secondKey'),
 *   	);
 *	}
 *
 *	Thanks to rawtaz and tom[] from #yii on freenode
 */
class IeValidator extends CValidator
{
	/**
	 * The attributes boud in the estado with attribute
	 * @var string
	 */
	public $with;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel the data object being validated
	 * @param string the name of the attribute to be validated.
	 */
	protected function validateAttribute( $object, $attribute ){
		if ( !$this->validaIE( $object, $attribute, $this->with ) )
			$this->addError($object, $attribute, Yii::t('yii','{attribute} não é uma Inscrição Estadual válida.'));
	}
	
	 /*
     * @autor: Adler Silva Dias
     * @email: adlersd@gmail.com
     * 
     * @param CModel the data object being validated
	 * @param string the name of the attribute to be validated.
	 * @param string the name of the attribute using a dummy attribute to be validated.
     * 
    */
	private function validaIE($object, $attribute, $with)
	{
		$inscricao = $object->$attribute;
		$estado = $object->$with;
		
		if($inscricao <> "ISENTO"){
			if (!is_numeric($inscricao) OR !is_string($inscricao)) {
				return false;
			} else {
				if ($estado == "AC") {
					if (strlen($inscricao) <> 13) {
						return false;
					} else {
						// 01.004.823/001-12
						$soma1 = ($inscricao[0] * 4) + ($inscricao[1] * 3) + ($inscricao[2] * 2) + ($inscricao[3] * 9) +
						($inscricao[4] * 8) + ($inscricao[5] * 7) + ($inscricao[6] * 6) + ($inscricao[7] * 5) +
						($inscricao[8] * 4) + ($inscricao[9] * 3) + ($inscricao[10] * 2);
		
						$resto = 11 - ($soma1 % 11);
						if ($resto >= 10) {
							$digito1 = 0;
						} else {
							$digito1 = $resto;
						}
		
						$soma2 = ($inscricao[0] * 5) + ($inscricao[1] * 4) + ($inscricao[2] * 3) + ($inscricao[3] * 2) +
						($inscricao[4] * 9) + ($inscricao[5] * 8) + ($inscricao[6] * 7) + ($inscricao[7] * 6) +
						($inscricao[8] * 5) + ($inscricao[9] * 4) + ($inscricao[10] * 3) + ($inscricao[11] * 2);
		
						$resto = 11 - ($soma2 % 11);
						if ($resto >= 10) {
							$digito2 = 0;
						} else {
							$digito2 = $resto;
						}
		
						if (($inscricao[11] == $digito1) && ($inscricao[12] == $digito2)) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "AL") {
					if (strlen($inscricao) <> 9) {
						return false;
					} else {
						// 24000004D
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$resto = (($soma * 10) - (floor((($soma*10)/11)) * 11));
						if ($resto >= 10) {
							$digito = 0;
						} else {
							$digito = $resto;
						}
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "AP") {
					// 030123459
					if (strlen($inscricao) <> 9 OR substr($inscricao,0,2) != 03) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						if (substr($inscricao,0,8) >= 03000001 AND substr($inscricao,0,2) <= 03017000) {
							$soma += 5;
							$d = 0;
						} elseif (substr($inscricao,0,2) >= 03017001 AND substr($inscricao,0,2) <= 03019022) {
							$soma += 9;
							$d = 1;
						} else {
							$soma += 0;
							$d = 0;
						}
		
						$resto = 11 - ($soma % 11);
		
						if ($resto == 10) {
							$digito = 0;
						} elseif ($resto == 11) {
							$digito = $d;
						} else {
							$digito = $resto;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "AM") {
					if (strlen($inscricao) <> 9) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						if ($soma < 11) {
							$digito = 11 - $soma;
						} else {
							if (($soma % 11) >= 1) {
								$digito = 0;
							} else {
								$digito = 11 - ($soma % 11);
							}
						}
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "BA") {
					// 12345663
					if (strlen($inscricao) <> 8) {
						return false;
					} else {
						if (array_search(substr($inscricao,0,1),array(0,1,2,3,4,5,8))) {
							// 12345663
							$soma2 = ($inscricao[0] * 7) + ($inscricao[1] * 6) + ($inscricao[2] * 5) + ($inscricao[3] * 4) +
							($inscricao[4] * 3) + ($inscricao[5] * 2);
		
							$resto = $soma2 % 10;
							if ($resto == 0) {
								$digito2 = 0;
							} else {
								$digito2 = 10 - $resto;
							}
		
							$soma1 = ($inscricao[0] * 8) + ($inscricao[1] * 7) + ($inscricao[2] * 6) + ($inscricao[3] * 5) +
							($inscricao[4] * 4) + ($inscricao[5] * 3) + ($inscricao[7] * 2);
		
							$digito1 = 10 - ($soma1 % 10);
		
							if (($inscricao[6] == $digito1) && ($inscricao[7] == $digito2)) {
								return true;
							} else {
								return false;
							}
						} else {
							// 61234557
							$soma2 = ($inscricao[0] * 7) + ($inscricao[1] * 6) + ($inscricao[2] * 5) + ($inscricao[3] * 4) +
							($inscricao[4] * 3) + ($inscricao[5] * 2);
		
							$resto = $soma2 % 11;
							if ($resto < 2) {
								$digito2 = 0;
							} else {
								$digito2 = 11 - $resto;
							}
		
							$soma1 = ($inscricao[0] * 8) + ($inscricao[1] * 7) + ($inscricao[2] * 6) + ($inscricao[3] * 5) +
							($inscricao[4] * 4) + ($inscricao[5] * 3) + ($inscricao[7] * 2);
		
							$digito1 = 11 - ($soma1 % 11);
		
							if (($inscricao[6] == $digito1) && ($inscricao[7] == $digito2)) {
								return true;
							} else {
								return false;
							}
						}
					}
				} elseif ($estado == "CE") {
					// 060000015
					if (strlen($inscricao) <> 9) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$resto = $soma % 11;
		
						$digito = 11 - $resto;
		
						if ($digito >= 10) {
							$digito = 0;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "DF") {
					if (strlen($inscricao) <> 13 || substr($inscricao,0,2) != 07) {
						return false;
					} else {
						// 0730000100109
						$soma1 = ($inscricao[0] * 4) + ($inscricao[1] * 3) + ($inscricao[2] * 2) + ($inscricao[3] * 9) +
						($inscricao[4] * 8) + ($inscricao[5] * 7) + ($inscricao[6] * 6) + ($inscricao[7] * 5) +
						($inscricao[8] * 4) + ($inscricao[9] * 3) + ($inscricao[10] * 2);
		
						$resto = 11 - ($soma1 % 11);
						if ($resto >= 10) {
							$digito1 = 0;
						} else {
							$digito1 = $resto;
						}
		
						$soma2 = ($inscricao[0] * 5) + ($inscricao[1] * 4) + ($inscricao[2] * 3) + ($inscricao[3] * 2) +
						($inscricao[4] * 9) + ($inscricao[5] * 8) + ($inscricao[6] * 7) + ($inscricao[7] * 6) +
						($inscricao[8] * 5) + ($inscricao[9] * 4) + ($inscricao[10] * 3) + ($inscricao[11] * 2);
		
						$resto = 11 - ($soma2 % 11);
						if ($resto >= 10) {
							$digito2 = 0;
						} else {
							$digito2 = $resto;
						}
		
						if (($inscricao[11] == $digito1) && ($inscricao[12] == $digito2)) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "ES") {
					if (strlen($inscricao) <> 9) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$resto = $soma % 11;
		
						if ($resto < 2) {
							$digito = 0;
						} else {
							$digito = 11 - $resto;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "GO") {
					// 109876547
					if (strlen($inscricao) <> 9) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$resto = $soma % 11;
		
						if (substr($inscricao,0,8) == 11094402) {
							if (($inscricao[8] == 0) || ($inscricao[8] == 1))
								return true;
						}
		
						if ($resto == 0) {
							$digito = 0;
						} elseif ($resto == 1 && ($inscricao >= 10103105 && $inscricao <= 10119997)) {
							$digito = 1;
						} elseif ($resto == 1 && !($inscricao >= 10103105 && $inscricao <= 10119997)) {
							$digito = 0;
						} else {
							$digito = 11 - $resto;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
		
				} elseif ($estado == "MA") {
					// 120000385
					if (strlen($inscricao) <> 9 || (substr($inscricao,0,2) != 12)) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$resto = $soma % 11;
		
						if ($resto < 2) {
							$digito = 0;
						} else {
							$digito = 11 - $resto;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "MT") {
					// 00130000019
					if (strlen($inscricao) <> 11) {
						return false;
					} else {
						$soma = ($inscricao[0] * 3) + ($inscricao[1] * 2) + ($inscricao[2] * 9) + ($inscricao[3] * 8) +
						($inscricao[4] * 7) + ($inscricao[5] * 6) + ($inscricao[6] * 5) + ($inscricao[7] * 4) +
						($inscricao[8] * 3) +  ($inscricao[9] * 2);
		
						$resto = $soma % 11;
		
						if ($resto < 2) {
							$digito = 0;
						} else {
							$digito = 11 - $resto;
						}
		
						if ($inscricao[10] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "MS") {
					// 282182918
					if (strlen($inscricao) <> 9 || substr($inscricao,0,2) != 28) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$resto = $soma % 11;
		
						if ($resto != 0) {
							$digito = 11 - $resto;
							if ($digito > 9) {
								$digito = 0;
							}
						} else {
							$digito = 0;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "MG") {
					// 6250551850086
					if (strlen($inscricao) <> 13) {
						return false;
					} else {
						// 062.307.904/0081
						$mg = substr($inscricao,0,3) . "0" . substr($inscricao,3,10);
						for ($i = 0; $i < 12; $i++) {
							if($i%2==0) {
								$num = ($mg[$i] * 1);
								$soma += $num;
							} else {
								$num = ($mg[$i] * 2);
								if ($num>9) {
									$soma += (1 + ($num - 10));
								} else {
									$soma += $num;
								}
							}
						}
		
						$dezena_superior = ( (substr($soma,0,1)+1) * 10 );
		
						$digito1 = ($dezena_superior - $soma);
		
						$soma2 = ($inscricao[0] * 3) + ($inscricao[1] * 2) + ($inscricao[2] * 11) + ($inscricao[3] * 10) +
						($inscricao[4] * 9) + ($inscricao[5] * 8) + ($inscricao[6] * 7) + ($inscricao[7] * 6) +
						($inscricao[8] * 5) + ($inscricao[9] * 4) + ($inscricao[10] * 3) + ($inscricao[11] * 2);
		
						$resto = $soma2 % 11;
						if ($resto <= 1) {
							$digito2 = 0;
						} else {
							$digito2 = (11 - $resto);
						}
		
						if (($inscricao[11] == $digito1) && ($inscricao[12] == $digito2)) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "PA") {
					if (strlen($inscricao) <> 9 || substr($inscricao,0,2) != 15) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$resto = $soma % 11;
		
						if ($resto < 2) {
							$digito = 0;
						} else {
							$digito = 11 - $resto;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "PB") {
					if (strlen($inscricao) <> 9) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$digito = 11 - ($soma % 11);
		
						if ($digito >= 10) {
							$digito = 0;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "PR") {
					if (strlen($inscricao) <> 10) {
						return false;
					} else {
						$soma1 = ($inscricao[0] * 3) + ($inscricao[1] * 2) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$resto = $soma1 % 11;
						if ($resto < 2) {
							$digito1 = 0;
						} else {
							$digito1 = 11 - $resto;
						}
		
						$soma2 = ($inscricao[0] * 4) + ($inscricao[1] * 3) + ($inscricao[2] * 2) + ($inscricao[3] * 7) +
						($inscricao[4] * 6) + ($inscricao[5] * 5) + ($inscricao[6] * 4) + ($inscricao[7] * 3) +
						($inscricao[8] * 2);
		
						$resto = $soma2 % 11;
						if ($resto < 2) {
							$digito2 = 0;
						} else {
							$digito2 = 11 - $resto;
						}
		
						if (($inscricao[8] == $digito1) && ($inscricao[9] == $digito2)) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "PE") {
					if (strlen($inscricao) <> 9 && strlen($inscricao) <> 14) {
						return false;
					} else {
						if (strlen($inscricao) == 9) {
							$soma1 = ($inscricao[0] * 8) + ($inscricao[1] * 7) + ($inscricao[2] * 6) + ($inscricao[3] * 5) +
							($inscricao[4] * 4) + ($inscricao[5] * 3) + ($inscricao[6] * 2);
		
							$resto = $soma1 % 11;
		
							if ($resto < 2) {
								$digito1 = 0;
							} else {
								$digito1 = 11 - $resto;
							}
		
							$soma2 = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
							($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
							$resto = $soma2 % 11;
		
							if ($resto < 2) {
								$digito2 = 0;
							} else {
								$digito2 = 11 - $resto;
							}
		
							if ($inscricao[7] == $digito1 && $inscricao[8] == $digito2) {
								return true;
							} else {
								return false;
							}
						} else {
							$soma = ($inscricao[0] * 5) + ($inscricao[1] * 4) + ($inscricao[2] * 3) + ($inscricao[3] * 2) +
							($inscricao[4] * 1) + ($inscricao[5] * 9) + ($inscricao[6] * 8) + ($inscricao[7] * 7) +
							($inscricao[8] * 6) + ($inscricao[9] * 5) + ($inscricao[10] * 4) + ($inscricao[11] * 3) +
							($inscricao[12] * 2);
		
							$digito = 11 - ($soma % 11);
		
							if ($digito >= 10) {
								$digito -= 10;
							}
		
							if ($inscricao[13] == $digito) {
								return true;
							} else {
								return false;
							}
						}
					}
				} elseif ($estado == "PI") {
					if (strlen($inscricao) <> 9) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$digito = 11 - ($soma % 11);
		
						if ($digito >= 10) {
							$digito = 0;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "RJ") {
					if (strlen($inscricao) <> 8) {
						return false;
					} else {
						$soma = ($inscricao[0] * 2) + ($inscricao[1] * 7) + ($inscricao[2] * 6) + ($inscricao[3] * 5) +
						($inscricao[4] * 4) + ($inscricao[5] * 3) + ($inscricao[6] * 2);
		
						$resto = $soma % 11;
		
						if ($resto < 2) {
							$digito = 0;
						} else {
							$digito = 11 - $resto;
						}
		
						if ($inscricao[7] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "RN") {
					if (strlen($inscricao) <> 9 && strlen($inscricao) <> 10) {
						return false;
					} else {
						if (strlen($inscricao) == 9) {
							$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
							($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
							$resto = ($soma * 10) % 11;
		
							if ($resto == 10) {
								$digito = 0;
							} else {
								$digito = $resto;
							}
		
							if ($inscricao[8] == $digito) {
								return true;
							} else {
								return false;
							}
						} else {
							$soma = ($inscricao[0] * 10) + ($inscricao[1] * 9) + ($inscricao[2] * 8) + ($inscricao[3] * 7) +
							($inscricao[4] * 6) + ($inscricao[5] * 5) + ($inscricao[6] * 4) + ($inscricao[7] * 3) +
							($inscricao[8] * 2);
		
							$resto = ($soma * 10) % 11;
		
							if ($resto == 10) {
								$digito = 0;
							} else {
								$digito = $resto;
							}
		
							if ($inscricao[9] == $digito) {
								return true;
							} else {
								return false;
							}
						}
					}
				} elseif ($estado == "RS") {
					if (strlen($inscricao) <> 10) {
						return false;
					} else {
						$soma = ($inscricao[0] * 2) + ($inscricao[1] * 9) + ($inscricao[2] * 8) + ($inscricao[3] * 7) +
						($inscricao[4] * 6) + ($inscricao[5] * 5) + ($inscricao[6] * 4) + ($inscricao[7] * 3) +
						($inscricao[8] * 2);
		
						$digito = 11 - ($soma % 11);
		
						if ($digito >= 10) {
							$digito = 0;
						}
		
						if ($inscricao[9] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "RO") {
					if (strlen($inscricao) <> 9 && strlen($inscricao) <> 14) {
						return false;
					} else {
						if (strlen($inscricao) == 9) { 
							$soma = ($inscricao[3] * 6) + ($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) +
							($inscricao[7] * 2);
		
							$digito = 11 - ($soma % 11);
		
							if ($digito >= 10) {
								$digito -= 10;
							}
		
							if ($inscricao[8] == $digito) {
								return true;
							} else {
								return false;
							}
						} else {
							$soma = ($inscricao[0] * 6) + ($inscricao[1] * 5) + ($inscricao[2] * 4) + ($inscricao[3] * 3) +
							($inscricao[4] * 2) + ($inscricao[5] * 9) + ($inscricao[6] * 8) + ($inscricao[7] * 7) +
							($inscricao[8] * 6) + ($inscricao[9] * 5) + ($inscricao[10] * 4) + ($inscricao[11] * 3) +
							($inscricao[12] * 2);
		
							$digito = 11 - ($soma % 11);
		
							if ($digito >= 10) {
								$digito -= 10;
							}
		
							if ($inscricao[13] == $digito) {
								return true;
							} else {
								return false;
							}
						}
					}
				} elseif ($estado == "RR") {
					if (strlen($inscricao) <> 9) {
						return false;
					} else {
						$soma = 0;
						for ($i=0; $i<9; $i++) {
							$soma += $inscricao[$i] * ($i+1);
						}
		
						$digito = $soma % 9;
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "SC") {
					if (strlen($inscricao) <> 9) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$resto = $soma % 11;
		
						if ($resto < 2) {
							$digito = 0;
						} else {
							$digito = 11 - $resto;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
		
				} elseif ($estado == "SE") {
					if (strlen($inscricao) <> 9) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[2] * 7) + ($inscricao[3] * 6) +
						($inscricao[4] * 5) + ($inscricao[5] * 4) + ($inscricao[6] * 3) + ($inscricao[7] * 2);
		
						$digito = 11 - ($soma % 11);
		
						if ($digito >= 10) {
							$digito = 0;
						}
		
						if ($inscricao[8] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				} elseif ($estado == "SP") {
					if (strlen($inscricao) <> 12 && strlen($inscricao) <> 13) {
						return false;
					} else {
						if (strlen($inscricao) == 12) {
							$soma = ($inscricao[0] * 1) + ($inscricao[1] * 3) + ($inscricao[2] * 4) + ($inscricao[3] * 5) +
							($inscricao[4] * 6) + ($inscricao[5] * 7) + ($inscricao[6] * 8) + ($inscricao[7] * 10);
		
							$digito = $soma % 11;
		
							if ($digito == 10) {
								$digito = 0;
							} elseif ($digito == 11) {
								$digito = 1;
							}
		
							$soma2 = ($inscricao[0] * 3) + ($inscricao[1] * 2) + ($inscricao[2] * 10) + ($inscricao[3] * 9) +
							($inscricao[4] * 8) + ($inscricao[5] * 7) + ($inscricao[6] * 6) + ($inscricao[7] * 5) +
							($inscricao[8] * 4) + ($inscricao[9] * 3) + ($inscricao[10] * 2);
		
							$digito2 = $soma2 % 11;
		
							if ($digito2 == 10) {
								$digito2 = 0;
							} elseif ($digito2 == 11) {
								$digito2 = 1;
							}
		
							if ($inscricao[8] == $digito && $inscricao[11] == $digito2) {
								return true;
							} else {
								return false;
							}
						} else {
							return false;
						}
					}
				} elseif ($estado == "TO") {
					if (strlen($inscricao) <> 11 || (substr($inscricao,2,2) != 01 && substr($inscricao,2,2) != 02 && substr($inscricao,2,2) != 03 && substr($inscricao,2,2) !=99)) {
						return false;
					} else {
						$soma = ($inscricao[0] * 9) + ($inscricao[1] * 8) + ($inscricao[4] * 7) + ($inscricao[5] * 6) +
						($inscricao[6] * 5) + ($inscricao[7] * 4) + ($inscricao[8] * 3) + ($inscricao[9] * 2);
		
						$resto = $soma % 11;
		
						if ($resto < 2) {
							$digito = 0;
						} else {
							$digito = 11 - $resto;
						}
		
						if ($inscricao[10] == $digito) {
							return true;
						} else {
							return false;
						}
					}
				}
			}
		}else{
			return true;
		}
	}
}