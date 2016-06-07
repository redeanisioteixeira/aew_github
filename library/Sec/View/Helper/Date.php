<?php
class Sec_View_Helper_Date
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function Date($date, $format = Sec_Date::DATE)
    {
        $result = new Sec_Date($date);
		return $result->toString($format);
    }
}