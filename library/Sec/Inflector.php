<?php
/**
	-----------------------------------------------------------------------
	CHANGELOG
	-----------------------------------------------------------------------
	02/02/2010
	[+] pluralize() transforma palavras do singular para o plural
	[+] singularize() transforma palavras do plural para o singular
	15/04/2010
	[m] Troca do str_replace() pelo preg_replace() como função de
	substituição.
	16/04/2010
	[m] Métodos transformados em estáticos
	[m] Alterações simples nas regras
	13/02/2013
	[+] addException() adiciona uma exceção às regras
	[+] addRule() adiciona uma regra
	-----------------------------------------------------------------------
	TO DO
	-----------------------------------------------------------------------
	Precisa analisar também a questão das palavras compostas, onde, no
	português, na maioria das vezes, a pluralização ocorre no primeiro termo.
*/

class Sec_Inflector{
	/**
	 *    Regras de singularização/pluralização
	 */
	//singular     plural
	static public $rules = array(
		'ão'	=> 'ões', 
		'ês'	=> 'eses', 
		'm'	=> 'ns', 
		'al'	=> 'ais',
		'el'	=> 'eis',
		'il'	=> array('eis','is'),
		'ol'	=> 'ois',
		'ul'	=> 'uis',
		'l'	=> 'is',
		'r'	=> 'res', 
		'x'	=> 'xes', 
		'z'	=> 'zes',
		'b'	=> 'b',  
		'c'	=> 'c',  
		'f'	=> 'f',  
		'g'	=> 'g',  
		'h'	=> 'h',  
		'j'	=> 'j',
		'k'	=> 'k',  
		'p'	=> 'p',
		'q'	=> 'q',
		't'	=> 't',
		'v'	=> 'v',
		'ç'	=> 'ç',
		'õ'	=> 'õ',
		'ã'	=> 'ã',
		'ẽ'	=> 'ẽ',
	);

	/**
	 *    Exceções às regras
	 */
	static public $exceptions = array(
		'cidadão' => 'cidadãos',
		'mão'     => 'mãos',
		'qualquer'=> 'quaisquer',
		'campus'  => 'campi',
		'lápis'   => 'lápis',
		'ônibus'  => 'ônibus',
		'em'      => 'em',
		'de'      => 'de',
		'e'       => 'e',
		'mal'		 => 'males',
	);

	/**
	 *    Adiciona uma exceção às regras
	 *
	 *    @version
	 *      1.0 13/02/2013 Initial
	 *
	 *    @param  string $singularWord Palavra no singular
	 *    @param  string $pluralWord Palavra no plural
	 *    @return bool
	 */
	public static function addException($singularWord, $pluralWord){
		self::$exceptions[$singularWord] = $pluralWord;
		return true;
	}

	/**
	 *    Adiciona uma regra
	 *
	 *    @version
	 *      1.0 13/02/2013 Initial
	 *
	 *    @param  string $singularSufix Terminação da palavra no singular
	 *    @param  string $pluralSufix Terminação da palavra no plural
	 *    @return bool
	 */
	public static function addRule($singularWord, $pluralWord){
		self::$rules[$singularSufix] = $pluralSufix;
		return true;
	}

	/**
	 *    Passa uma palavra para o plural
	 *
	 *    @version
	 *      1.0 Initial
	 *      1.1 15/04/2010 Substituição do str_replace() pelo preg_replace()
	 *          como função de substituição
	 *
	 *    @param  string $word A palavra que deseja ser passada para o plural
	 *    @return string
	*/
	public static function pluralize($word)
	{
		//Pertence a alguma exceção?
		$newWord = array();
		$words = explode(' ', $word);

		for($i=0; $i<count($words); $i++):

			$find = false;
			if(array_key_exists($words[$i], self::$exceptions)):
				$newWord[] = self::$exceptions[$words[$i]];
				$find = true;
			else: //Não pertence a nenhuma exceção. Mas tem alguma regra?
				foreach(self::$rules as $singular => $plural):
					if(!is_array($plural)):
						if(preg_match("({$singular}$)", $words[$i])):
							$newWord[] =  preg_replace("({$singular}$)", $plural, $words[$i]);
							$find = true;
							break;
						endif;
					else:
						foreach($plural as $subplural):
							if(preg_match("({$singular}$)", $words[$i])):
								$newWord[] =  preg_replace("({$singular}$)", $subplural, $words[$i]);
								$find = true;
							endif;
						endforeach;		
						if(count($newWord)):
							break;
						endif;
					endif;
				endforeach;
			endif;

			//Não pertence às exceções, nem às regras.
			//Se não terminar com "s", adiciono um.
			if(!$find):
            if(substr($words[$i], -1) !== 's'):
					$newWord[] = $words[$i].'s';
				else:
					$newWord[] = $words[$i];
            endif;
			endif;

		endfor;
		return $newWord;
	}
    
	/**
	 *    Passa uma palavra para o singular
	 *    
	 *    @version
	 *      1.0 Initial
	 *      1.1 15/04/2010 Substituição do str_replace() pelo preg_replace()
	 *          como função de substituição
	 *
	 *    @param  string $words[$i]ord A palavra que deseja ser passada para o singular
	 *    @return string
	 */
	public static function singularize($word)
	{
		//Pertence às exceções?
		$words = explode( ' ',$word);
		$newWord = array();

		for($i=0; $i<count($words); $i++):
         $find = false;
			if(in_array($words[$i], self::$exceptions)):
				$invert = array_flip(self::$exceptions);
				$newWord[] = $invert[$words[$i]];
				$find = true;

			else: //Não é exceção.. Mas pertence a alguma regra?
				foreach(self::$rules as $singular => $plural):

					if(!is_array($plural)):
						if(preg_match("({$plural}$)",$words[$i])):
							$newWord[]= preg_replace("({$plural}$)", $singular, $words[$i]);
							$find = true;
							break;
						endif;
					else:
						foreach($plural as $subplural):
							if(preg_match("({$subplural}$)", $words[$i])):
								$newWord[] = preg_replace("({$subplural}$)", $singular, $words[$i]);
								$find = true;
							endif;
						endforeach;
						if(count($newWord)):
							break;
						endif;
					endif;

				endforeach;
			endif;

			//Nem é exceção, nem tem regra definida. 
			//Apaga a última somente se for um "s" no final
			if(!$find):
				if(substr($words[$i], -1) == 's'):
					$newWord[] = substr($words[$i], 0, -1);
				else:
					$newWord[] = $words[$i];
				endif;
			endif;
           
		endfor;
		return $newWord;
	}
}
