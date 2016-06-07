<?php

/**
 * BO da entidade AlbumFoto
 */

abstract class Aew_Model_Bo_AlbumFoto extends Aew_Model_Bo_Foto
{
    protected $cota = 20;
    protected $dataCriacao;
    protected $titulo;
    protected $comentarios = array();
    
    /**
     * 
     * @return int
     */
    public function getCota()
    {
        return $this->cota;
    }
    
    /**
     * @return array
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;
    }

    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    /**
     * 
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * @param int $cota
     */
    public function setCota($cota)
    {
        $this->cota = $cota;
    }

    /**
     * 
     * @param string $dataCriacao
     */
    public function setDataCriacao($dataCriacao)
    {
        $this->dataCriacao = $dataCriacao;
    }

    /**
     * 
     * @param string $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * retorna os parametro do objeto em um array
     * onde a chave e o nome da variavel de instancia e tambem
     * o nome do campo na tabela
     * @return array
     */
    function toArray()
    {
        $data = parent::toArray();
        if($this->getDataCriacao())
        {
             $data['datacriacao'] = $this->getDataCriacao();
        }
        if($this->getTitulo())
        {
             $data['titulo'] = $this->getTitulo();
        }
        return $data;
    }
    
    
    /**
     * preenche o objeto com dados de uma array (de mapeamento chave-valor)
     * @param array $data
     */
    public function exchangeArray($data)
    {
        parent::exchangeArray($data);
        $this->setDataCriacao(isset($data['datacriacao'])? $data['datacriacao']: null);
        $this->setTitulo(isset($data['titulo'])? $data['titulo']: null);
    }

    /**
     * 
     * @param Zend_Form $form
     * @return int
     */
    function _save($form)
    {
        $arr_extensao = array(".jpg", ".gif", ".png", ".jpeg");
        foreach($arr_extensao as $ext):
            $arquivo_foto = $this->getFotoDirectory().DS.$this->getId().$ext;
            if(file_exists($arquivo_foto)):
                unlink($arquivo_foto);
            endif;
        endforeach;
        $file = $form->foto;
        if($file->isUploaded())
        {
            $dirty = true;
            $ext = Sec_File::getExtension($file->getFileName());
            $this->setExtensao($ext);
            $file->addFilter('Rename', array('target' => $this->getFotoDirectory().DS.$this->getId().'.'.$ext,'overwrite' => true));
        }
        if($file->receive())
        {
            $this->geraCache($this->getFotoDirectory(), $this->getId(), $ext);
        }
        return parent::save();
    }
    
    /**
    * Gera cache da foto
    * @param $path, $id, $type
    * @return bool
    */
    public function geraCache($path, $id, $type)
    {
        $arquivo = "$id.$type"; 
        $dir_fotos_cache = $path.DS."cache";
        $dir_fotos_gray  = $path.DS."cache".DS."gray";

        //--- Elimina fotos com outras extensões para evitar gerar lixo
        $arr_extensao = array(".jpg", ".gif", ".png", ".jpeg");
        foreach($arr_extensao as $ext):
            if (is_dir($dir_fotos_cache)):
                if ($dh = opendir($dir_fotos_cache)):
                    while (($file = readdir($dh)) !== false):
                        if($file != "." && $file != ".." && filetype($dir_fotos_cache.DS.$file)=="dir"):
                            $file = $dir_fotos_cache.DS.$file.DS.$id.$ext;	
                            unlink($file);
                            clearstatcache();
                        endif;
                    endwhile;
                    closedir($dh);
                endif;
            endif;
        endforeach;
        //--- Gerar image cache gray
        if($type=="jpg" || $type=="jpeg"):
            $im = ImageCreateFromJpeg($path.DS.$arquivo);
        endif;
        if($type=="png"):
            $im = ImageCreateFromPng($path.DS.$arquivo);
        endif;
        if($type=="gif"):
            $im = ImageCreateFromGif($path.DS.$arquivo);
        endif;
        if($im):
            ImageFilter($im, IMG_FILTER_GRAYSCALE);
            if($type=="jpg" || $type=="jpeg"):
                ImageJpeg($im, $dir_fotos_gray.DS.$arquivo,100);
            endif;
            if($type=="png"):
                ImagePng($im, $dir_fotos_gray.DS.$arquivo,0);
            endif;
            if($type=="gif"):
                ImageGif($im, $dir_fotos_gray.DS.$arquivo,100);
            endif;
        endif;
        ImageDestroy($im);
        $arr_side = array(160); //-- Tamanhos da imagem cache
        //--- Gera tamanho da imagem
        foreach($arr_side as $side):
            $dir_cache = "imagecache$side"."x$side";
            //-- Verifica pasta e cria caso de não existir
            $dir_cache = $dir_fotos_cache.DS.$dir_cache;
            if(!is_dir($dir_cache)): 
                if (mkdir($dir_cache, 0777, false)):
                    chmod($dir_cache,0777);
                endif;
            endif;
            if(!is_dir($dir_cache.DS."gray")): 
                if (mkdir($dir_cache.DS."gray", 0777, false)):
                    chmod($dir_cache.DS."gray",0777);
                endif;
            endif;

                    if($type=="jpg" || $type=="jpeg"):
                            $im = ImageCreateFromJpeg($path.DS.$arquivo);
                    endif;

                    if($type=="png"):
                            $im = ImageCreateFromPng($path.DS.$arquivo);
                    endif;

                    if($type=="gif"):
                            $im = ImageCreateFromGif($path.DS.$arquivo);
                    endif;

                    if($im):
                            $im = $this->_resize($im, $side);

                            if($type=="jpg" || $type=="jpeg"):
                                    ImageJpeg($im, $dir_cache.DS.$arquivo,100);
                                    ImageFilter($im, IMG_FILTER_GRAYSCALE);
                                    ImageJpeg($im, $dir_cache.DS."gray".DS.$arquivo,100);			
                            endif;

                            if($type=="png"):
                                    ImagePng($im, $dir_cache.DS.$arquivo,0);
                                    ImageFilter($im, IMG_FILTER_GRAYSCALE);
                                    ImagePng($im, $dir_cache.DS."gray".DS.$arquivo,0);
                            endif;

                            if($type=="gif"):
                                    ImageGif($im, $dir_cache.DS.$arquivo,100);
                                    ImageFilter($im, IMG_FILTER_GRAYSCALE);
                                    ImageGif($im, $dir_cache.DS."gray".DS.$arquivo,100);
                            endif;

                    endif;

                    ImageDestroy($im);

            endforeach;	
            clearstatcache();
            return;
    }

    public function _resize($img, $new_side){
            $width  = imagesx($img);
            $height = imagesy($img);

            $new_width  = $new_side;
            $new_height = ($new_side * $height) / $width;

            $new_img = ImageCreateTrueColor($new_width, $new_width);
            $color   = ImagecolorAllocate($new_img, 255, 255, 255);

            $top = 0;
            if($new_height<$new_width):
                    $top = ($new_width - $new_height)/2;
            endif;

            ImageCopyResampled($new_img, $img, 0, $top, 0, 0, $new_width, $new_height, $width, $height);
            ImageDestroy($img);

            return $new_img;
    }

    /**
     * 
     * @return boolean
     */
    public function delete()
    {
        $fotoFileDownload = $this->getFotoDirectory().DS.$this->getId().".".$this->getExtensao();
        if(file_exists($fotoFileDownload))
        {
            if(false == unlink($fotoFileDownload))
            {
                return false;
            }
        }
        return parent::delete();
    }
}