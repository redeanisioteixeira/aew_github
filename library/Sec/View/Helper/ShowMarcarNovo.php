<?php

class Sec_View_Helper_ShowMarcarNovo
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function ShowMarcarNovo($data_inicial, $cor = 'vermelho')
    {
		$data_final   = new DateTime(date('Y/m/d H:m:s'));
		$data_inicial = new DateTime($data_inicial);

		$intervalo = $data_final->diff($data_inicial);

		$div = '';
		if($intervalo->days < DIAS_NOVO):
			$div  = '<div class="marcador-novo absolute absolute absolute-right" dias="'.$intervalo->days.'" style="top: -10px;">';
			$div .= '	<i class="text-shadow link-'.$cor.' font-size-350 trans40 fa fa-certificate"></i>';
			$div .= '	<span class="absolute link-branco" style="top: 12px;left: 4px;transform: rotate(-25deg);letter-spacing: -1px;"><b>novo!</b></span>';
			$div .= '</div>';
		endif;

		return $div;
    }
}


