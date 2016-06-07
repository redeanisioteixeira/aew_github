<?php
/**
 * Bo da entidade 
 */
class Aew_Model_Bo_SiteTematico extends Aew_Model_Bo_ConteudoDigital
{
    /**
     * cria BO da entidade SitesTematicos
     * @return \Aew_Model_Dao_SitesTematicos
     */
    protected function createDao() {
        return new  Aew_Model_Dao_SitesTematicos();
    }

}
