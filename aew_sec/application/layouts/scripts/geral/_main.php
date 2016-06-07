<?php
echo $this->render('geral/_sec-bar.php');
echo $this->render('geral/_login-bar.php');
echo $this->render('geral/_modal.php');
echo $this->render('geral/_footer.php');

$layout = Zend_Layout::getMvcInstance();
echo $layout->render('_componentes/_barra-chat');

if($this->getModule() != 'aew')
{
    echo $layout->render('_componentes/_filtro');
}

if($this->getModule() == 'espaco-aberto')
{
    echo $layout->render('espaco-aberto/sidebar-left');
    //echo $layout->render('espaco-aberto/sidebar-right');
}

if($this->getModule() == 'tv-anisio-teixeira')
{
    echo $layout->render('tv-anisio-teixeira/_menu-bar');
    echo $layout->render('tv-anisio-teixeira/_layout-featured');
}