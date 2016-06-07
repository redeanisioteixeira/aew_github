<?php
require_once 'Mobile_Detect.php';
/**
 * Classe TipoDispositivo
 *
 * @author lisandro
 *
 */
class Sec_TipoDispositivo 
{
    static $TABLET = 'tablet',$MOBILE = 'mobile', $DESKTOP  = 'desktop';
    
    /**
     * Retorna tipo de dispositivo de acesso ao portal
     */
    static function detectar()
    {
        $deviceType = (self::isMobile() ? (self::isTablet() ? self::$TABLET : self::$MOBILE) : self::$DESKTOP);
        $scriptVersion = self::getScriptVersion();
        $arrDisp = Array();	
        $arrDisp['deviceType'] = $deviceType;
        $arrDisp['scriptVersion'] = $scriptVersion;
        
        return $arrDisp;
    }
    
    static function getScriptVersion()
    {
        $mobileDetect = new Mobile_Detect(); 
        return $mobileDetect->getScriptVersion();
    }
    
    public function getDevice()
    {
        return $this->getDevice();
    }
    static function isTablet() 
    {
        $mobileDetect = new Mobile_Detect(); 
        return $mobileDetect->isTablet();
    }
    static function isMobile() 
    {
        $mobileDetect = new Mobile_Detect(); 
        return $mobileDetect->isMobile();
    }
    static function isDesktop()
    {
        if(!self::isMobile() && !self::isTablet())
            return true;
        
        return false;
    }
}