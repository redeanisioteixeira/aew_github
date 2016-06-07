<?php 
//Modificar las 2 siguientes lineas si quereis 
$fichero = "/var/www/html/ambiente.educacao.ba.gov.br/aew_sec/public/img/usuarios.txt";//Nombre del fichero donde se guardaran los datos 
$logout= 900;//Duracion de la sesion en segundos

$IP = $_SERVER["REMOTE_ADDR"];
$count = 0; 
$res = ''; 

$fp = fopen($fichero,"r"); 
$t = time(); 
$content = fread($fp,filesize($fichero)); 
fclose($fp); 

$lineas = split(" ",$content); 
for ($i=0; $i < count($lineas); $i)
{
    $datos = split(':',$lineas[$i]); 
    $n = $t - $logout; 
    if ($datos[0] != $IP && $datos[1] > ($n))
    {
        $res .= "$datos[0]:$datos[1] "; 
        $count; 
    } 
}

$res .= "$IP:$t "; 
$count; 
$fp = fopen($fichero,"w");
fwrite ($fp,$res); 
fclose($fp); 

echo "Hay $count visitantes activos";
?> 
