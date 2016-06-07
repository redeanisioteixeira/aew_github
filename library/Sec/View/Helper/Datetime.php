<?php

class Sec_View_Helper_Datetime
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function datetime($date, $format = Sec_Date::DATETIME)
    {
        return $this->view->date($date, $format);
    }
}