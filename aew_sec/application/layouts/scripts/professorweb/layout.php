<?php
    $url_get = "http://oprofessorweb.wordpress.com"; 
    if (isset($_GET['url_aew'])):
        $url_get = $_GET['url_aew'];
        $url_get = unserialize(rawurldecode(base64_decode($url_get)));
    endif;
    $url_get1 = $url_get;
?>

<?php echo $this->doctype(); ?>
<html lang="pt-BR" xml:lang="pt-BR" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->headMeta(); ?>
        <?php echo $this->headTitle();?>
    </head>

   <FRAMESET ROWS="148px,*">
      <FRAME ID="id_topo" NAME="topo" SRC="/professorweb/home/topo" NAME=TITLE SCROLLING="NO" FRAMEBORDER="0" NORESIZE STYLE="box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);"> 
      <FRAME ID="id_frame_ambiente_educacional" NAME="frame_ambiente_educacional" SRC="<?php echo $url_get;?>" SCROLLING="AUTO" FRAMEBORDER="0">
      <NOFRAMES>
         <input id="url" type="hidden" value="<?php echo $url_get1;?>">
      </NOFRAMES>
   </FRAMESET>

</html>
