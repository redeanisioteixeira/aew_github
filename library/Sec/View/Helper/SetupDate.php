<?php
class Sec_View_Helper_SetupDate
{
    public function SetupDate($date = null, $tipo = 1)
    {
        ob_start();
        
        if(is_null($date)){
            $date = date("d/m/Y H:i");
        }

        $result = '';
        $locale = new Zend_Locale('pt_BR');
        Zend_Date::setOptions(array('format_type' => 'php'));

        $date = new Zend_Date($date, false, $locale);

        if ($tipo == 1){
            $result = $date->toString('j').' de '.$date->toString('F').' de '.$date->toString('Y').' às '.$date->toString('g:i a');
        }

        if ($tipo == 2){
            $result = $date->toString('j').' de '.$date->toString('F').' de '.$date->toString('Y');
        }

        if ($tipo == 3){
            $result = ucfirst($date->toString('F')).' de '.$date->toString('Y');
        }

        if ($tipo == 4){
			$result = $date->toString('d/m/Y').' às '.$date->toString('g:i a');        
		}

        ob_get_clean();

        return $result;
    }
}
