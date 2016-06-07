<?php
class Sec_View_Helper_ShowAvisoIe6 extends Zend_View_Helper_Abstract
{
    public function showAvisoIe6()
    {
        $result = "";

        $browser = getBrowser();
        $this->view->browser = $browser;
        
        $div  = $this->view->render('geral/_aviso-incompativel.php');

        switch($browser["name"]):
            case "Explorer":
                if (intval($browser["version"])<10){
                    $result = $div;
                }
                break;

            case "Google Chrome":
                if (intval($browser["version"])<23){
                    $result = $div;
                }
                break;

            case "Mozilla Firefox":
                if (intval($browser["version"])<30){
                    $result = $div;
                }
                break;

            case "Opera":
                if (intval($browser["version"])<12){
                    $result = $div;
                }
                break;
        endswitch;

        if($result):
            $this->view->headLink()->appendStylesheet('/assets/css/naocompativel.css');
        endif;
        
        return $result;
    }
}

function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
           $platform = 'windows';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known).')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';

    if (!preg_match_all($pattern, $u_agent, $matches)) {
           // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
           //we will have two since we are not using 'other' argument yet
           //see if version is before or after the name
           if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
               $version= $matches['version'][0];
           }
           else {
               $version= $matches['version'][1];
           }
    }
    else {
           $version= $matches['version'][0];
    }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}

    return array(
           'userAgent' => $u_agent,
           'name'      => $bname,
           'version'   => $version,
           'platform'  => $platform,
           'pattern'    => $pattern
    );
}