<?php

class EspacoAberto_Form_Pesquisar extends Sec_Form {

    public function init()
    {
        /* Elementos */
        $this->setMethod('post');
        $this->setAction('/espaco-aberto/pesquisa');

        $texto = $this->createElement('text','termo');
        $texto->setLabel('Pesquisar: *')
            ->setRequired(true)
            ->setValue('Pesquisar...')
            ->setAttrib('maxlength', 250)
            ->setAttrib('style', 'font-size:13px; height: 17px;')
			->setAttrib('maxlength', '23')
            ->addFilters(array(
				new Zend_Filter_StringTrim()
				));
        $this->addElement($texto);

		$nivel = $this->createElement('text','cancel_termo');
        $nivel->setLabel('lastname')
				->setAttrib('style', 'width:5px; height:5px; margin: 7px 0 0 -20px; border:none');
        $this->addElement($nivel);

        $tipoArray = array('usuario' => 'Usuário' , 'colega' => 'Colega',
                           'comunidade' => 'Comunidade',
                           'escola' => 'Escola',
                           'email' => 'E-mail', 'forum' => 'Fórum');

        $nivel = $this->createElement('select','tipo');
        $nivel->setLabel('Pesquisar por: ')
        	 ->setAttrib('style', 'font-size:13px')
             ->setMultiOptions($tipoArray);
        $this->addElement($nivel);

        $enviar = $this->createElement('submit','ok');
        $enviar->setLabel("Ok")
        		->setAttrib('style', 'font-size:13px')
               ->setAttrib('id', 'btnSubmit-Enviar')
               ->setIgnore(true);
		$this->addElement($enviar);
    }

    public function relacioneForm()
    {
        $this->getElement('termo')->setAttrib( "id", "termoId" )->setValue('');
        $this->getElement('tipo')->setAttrib( "id", "tipoId" )->setValue('comunidade');

        $this->getElement('ok')->setAttrib( "id", "buscaRelacioneForm-ok" );
    }

    /**
     * Retorna a palavra sem acentuação para melhorar a performance na consulta
     * @return string
     */
    public function retiraAcentuacao($palavra)
    {
    	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		$string = utf8_decode($palavra);
		$string = strtr($string, utf8_decode($a), $b); //substitui letras acentuadas por "normais"
		$string = strtolower($string); // passa tudo para minusculo
		return utf8_encode($string);
    }


    public function retornaStringBusca($nome){

		return 'LOWER(sem_acentos(e.nome)) LIKE (\'%'.$nome.'%\'))';

    }

    function BuscaEmProfundidade($nome)
    {//implementar processamento da string para as buscas

	$nome = trim($nome);
	$nome = str_replace("\\", "", $nome);//retirando as '/' para que seja interpretada a string de maneira integral
	$listaAtual = '';
	$listaComparada = '';
	$operacao = false;
	for ($i = 0; $i < strlen($nome); $i++){
		if (strcmp($nome{$i}, "\"") == 0){
			$minimo = ++$i;
			$tamanho = 0;
			//ao encontrar uma aspa, entra num novo loop para verificar a palavra chave
			while ((strcmp($nome{$i}, "\"") != 0) && $i < strlen($nome)){
				$i++;
				$tamanho++;
			}
			if (strlen(trim(substr($nome, $minimo, $tamanho)))>0){
				if (!$operacao)//inicia a lista com o elemento se nao for vazio e se a lista for nova
					$listaAtual = $this->retornaStringBusca(trim(substr($nome, $minimo, $tamanho)));
				else {
					if ($listaAtual!=''){
						$listaComparada = $this->retornaStringBusca(trim(substr($nome, $minimo, $tamanho)));
						$listaAtual .= " AND ".$listaComparada;
					}
					else return null;
				}
			}
			$i++;//mais uma vez para que saia das aspas
                        $operacao = true;
		}//if
		else if (strcmp($nome{$i}, "+") == 0){
			while (strcmp($nome{$i+1}, " ") == 0)
				$i++;//se achar um outro especial pula para proximo caracter
			$minimo = ++$i;
			$tamanho = 0;
			while ($i < strlen($nome)){
				if ((strcmp($nome{$i}, "\"") == 0)
			|| (strcmp($nome{$i}, "+") == 0)
			|| (strcmp($nome{$i}, " ") == 0))
					break;
				$i++;
				$tamanho++;
			}
			if (strlen(trim(substr($nome, $minimo, $tamanho)))>0){

				if (!$operacao)//inicia a lista com o elemento se nao for vazio e se a lista for nova
					$listaAtual = $this->retornaStringBusca(trim(substr($nome, $minimo, $tamanho)));
				else {
					if ($listaAtual!=''){
						$listaComparada = $this->retornaStringBusca(trim(substr($nome, $minimo, $tamanho)));
						$listaAtual .= " AND ".$listaComparada;
					}
					else return null;
				}
			}
			$operacao = true;

		}
		else if (strcmp($nome{$i}, " ") == 0){
			if ((strcmp($nome{$i+1}, "+") == 0) || (strcmp($nome{$i+1}, " ") == 0))
				continue;//se achar um outro especial pula para proxima iteração
			$minimo = ++$i;
			$tamanho = 0;
			while ($i < strlen($nome)){
				if ((strcmp($nome{$i}, "\"") == 0)
			|| (strcmp($nome{$i}, "+") == 0)
			|| (strcmp($nome{$i}, " ") == 0))
					break;
				$i++;
				$tamanho++;
			}
			if (strlen(trim(substr($nome, $minimo, $tamanho)))>0){
				if (!$operacao)//inicia a lista com o elemento se nao for vazio e se a lista for nova
					$listaAtual = $this->retornaStringBusca(trim(substr($nome, $minimo, $tamanho)));
				else {
					if ($listaAtual!=''){
						$listaComparada = $this->retornaStringBusca(trim(substr($nome, $minimo, $tamanho)));
						$listaAtual .= " AND ".$listaComparada;
					}
					else return null;
				}
			}
			$operacao = true;

		}
		else {
			$minimo = $i;
			$tamanho = 0;
			while ($i < strlen($nome)){
				if ((strcmp($nome{$i}, "\"") == 0)
			|| (strcmp($nome{$i}, "+") == 0)
			|| (strcmp($nome{$i}, " ") == 0))
					break;
				$i++;
				$tamanho++;
			}
			if (strlen(trim(substr($nome, $minimo, $tamanho)))>0){
				if (!$operacao)//inicia a lista com o elemento se nao for vazio e se a lista for nova
					$listaAtual = $this->retornaStringBusca(trim(substr($nome, $minimo, $tamanho)));
				else {
					if ($listaAtual!=''){
						$listaComparada = $this->retornaStringBusca(trim(substr($nome, $minimo, $tamanho)));
						$listaAtual .= " AND ".$listaComparada;
					}
					else return null;
				}
			}
			$operacao = true;
		}
		$i--;//para que no proximo loop o operador seja computado
	}//for

	return $listaAtual;
    }

    /**
     * Retorna as opções do formulario de busca como options para o getSelect
     * @return array
     */
    public function searchFilter()
    {
        $values = $this->getValues();

        $options = array();




        if($values['termo'] != ''){

                $searchFilter = strtolower($this->retiraAcentuacao($values['termo']));//convertendo para minuscula e retirando o acento para melhorar a performance

//                $stringBusca = $this->BuscaEmProfundidade($searchFilter);

                $options['where']['(LOWER(sem_acentos(e.nome)) LIKE (?))']
                                                        = array('%'.$searchFilter.'%');


        }






        return $options;
    }

     /**
     * popula o formulário
     */
    public function populate($values)
    {


        parent::populate($values);
    }
}