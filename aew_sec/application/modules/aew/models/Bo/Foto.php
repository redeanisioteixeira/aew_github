<?php
/**
 * BO da entidade Foto
 */
class Aew_Model_Bo_Foto extends Sec_Model_Bo_Abstract
{
    protected $url,$titulo,$extensao,$credito,$exposicao,$abertura,$dataCriacao;
    protected $iso,$artista,$copyright,$tituloOriginal,$modelo,$legenda,$make,$comentarioFoto;
    protected $idalbum; // id do album caso foto pertença a algum 
    protected $idPerfil; //int id do perfil (Comunidade ou usuario)
    protected $fotoFile;
    protected $comentarios = array();
    
    public static $FOTO_CACHE_30X30 = "cache/imagecache30x30/";
    public static $FOTO_CACHE_64X64 = "cache/imagecache64x64/";
    public static $FOTO_CACHE_90X90 = "cache/imagecache90x90/";
    public static $FOTO_CACHE_134X134 = "cache/imagecache134x134/";
    public static $FOTO_CACHE_160X160 = "cache/imagecache160x160/";
    public static $FOTO_CACHE_450X450 = "cache/imagecache450x450/";
    public static $FOTO_CACHE_GRAY = "cache/imagecachegray/";

    function __construct($urlFoto = null) {
        $this->setUrl($urlFoto);
    }

    /**
     * 
     * @return string
     */
    function getLegenda() {
        return $this->legenda;
    }

    /**
     * 
     * @param string $legenda
     */
    function setLegenda($legenda) {
        $this->legenda = $legenda;
    }

    /**
     * 
     * @return array
     */
    function getComentarios() {
        return $this->comentarios;
    }

    /**
     * 
     * @return string
     */
    function getMake() {
        return $this->make;
    }

    /**
     * 
     * @return string (comentario metadados)
     */
    function getComentarioFoto() {
        return $this->comentarioFoto;
    }

    /**
     * 
     * @param string $make
     */
    function setMake($make) {
        $this->make = $make;
    }

    /**
     * 
     * @param string $comentarioFoto
     */
    function setComentarioFoto($comentarioFoto) {
        $this->comentarioFoto = $comentarioFoto;
    }

    /**
     * 
     * @param array $comentarios
     */
    function setComentarios($comentarios) {
        $this->comentarios = $comentarios;
    }

    /**
     * 
     * @return string
     */
    public function getExposicao()
    {
        return $this->exposicao;
    }
    
    /**
     * 
     * @return int
     */
    public function getIdPerfil()
    {
        return $this->idPerfil;
    }

    /**
     * 
     * @param int $id
     */
    public function setIdPerfil($id)
    {
        $this->idPerfil = $id;
    }

    /**
     * 
     * @return string
     */
    public function getAbertura()
    {
        return $this->abertura;
    }

    /**
     * 
     * @return string
     */
    public function getDataCriacao()
    {
        return $this->dataCriacao;
    }

    /**
     * 
     * @return string
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * 
     * @return string
     */
    public function getArtista()
    {
        return $this->artista;
    }

    /**
     * 
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * 
     * @return string
     */
    public function getTituloOriginal()
    {
        return $this->tituloOriginal;
    }

    /**
     * 
     * @return string
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * 
     * @param string $exposicao
     */
    public function setExposicao($exposicao)
    {
        $this->exposicao = $exposicao;
    }

    /**
     * 
     * @param string $abertura
     */
    public function setAbertura($abertura)
    {
        $this->abertura = $abertura;
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
     * @param string $iso
     */
    public function setIso($iso)
    {
        $this->iso = $iso;
    }

    /**
     * 
     * @param string $artista
     */
    public function setArtista($artista)
    {
        $this->artista = $artista;
    }

    /**
     * 
     * @param string $copyright
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;
    }

    /**
     * 
     * @param string $tituloOriginal
     */
    public function setTituloOriginal($tituloOriginal)
    {
        $this->tituloOriginal = $tituloOriginal;
    }

    /**
     * 
     * @param string $modelo
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;
    }

    /**
    * faz o upload do arquivo de imagem
    * @param Zend_Form_Element_File $file
    * @return boolean
    */
    function uploadImg()
    {
        $arr_extensao = array(".jpg", ".png", ".jpeg");
        foreach($arr_extensao as $ext)
        {
            $arquivo_foto = $this->getFotoDirectory().DS.$this->getId().$ext;
            if(file_exists($arquivo_foto))
            {
                unlink($arquivo_foto);
            }
        }
        if($this->getFotoFile()->isUploaded()):
            $dirty = true;
            $ext = Sec_File::getExtension($this->getFotoFile()->getFileName());
            $this->setExtensao($ext);
            $fileName = $this->getFotoDirectory().DS.$this->getId().'.'.$ext;
            $this->getFotoFile()->addFilter('Rename', array('target' => $fileName,'overwrite' => true));
        endif;
        if($this->getFotoFile()->receive())
        {
            $this->geraCache($this->getFotoDirectory(), $this->getId(), $ext);
            $this->setUrl($this->getFotoDirectory().DS.$this->getId().'.'.$ext);
            return true;
        }
        return false;
    }
    
    /**
     * 
     * @param Aew_Model_Bo_AlbumComentario $comentario
     * @return int
     */
    function insertComentario(Aew_Model_Bo_AlbumComentario $comentario)
    {
        $comentario->setIdusuarioalbumfoto($this->getId());
        return $comentario->insert();
    }
    
    /**
    * Gera cache da foto
    * @param $path, $id, $type
    * @return boolean
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
                            if(file_exists($file))
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
            $arr_side = array(30,64,90,134,160,450); //-- Tamanhos da imagem cache
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

    /**
     * 
     * @param resource $img
     * @param int $new_side
     * @return resource
     */
    public function _resize($img, $new_side)
    {
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
    * Deleta a foto
    * @param AlbumFoto $foto
    * @return bool
    */
    public function delete_($foto)
    {
	$fotoFileDownload = $foto->getFotoDirectory().DS.$foto[$this->nomeId].".".$foto['extensao'];
	if(file_exists($fotoFileDownload)):
            if(false == unlink($fotoFileDownload)):
            	return false;
            endif;
	endif;
	return parent::delete($foto);
    }

    /**
     * id do album ao qual pertence a foto
     * @return int
     */
    public function getIdalbum()
    {
        return $this->idalbum;
    }

    /**
     * 
     * @param int $idalbum
     */
    public function setIdalbum($idalbum)
    {
        $this->idalbum = $idalbum;
    }
        
    /**
    * @param string $idcache
    * @param boolean $gray
    * @param string $width
    * @param string $height
    * @param boolean $link
    * @param string $class
     * @return string no formato html
    */
    public function getFotoCache($idcache, $gray = false, $width = 0, $height = 0, $link = false, $class = '')
    {
        $width = ($width > 0) ? "width='$width'": "" ;
        $height = ($height > 0) ? "height='$height'": "" ;
        
        $dir_cache  = "$idcache";
        $dir_cache .= ($gray == true ? "gray/":""); 
        $arquvo_foto = $dir_cache.$this->getId().'.'.$this->getExtensao();
        $dir_cache = $this->uri().DS.$dir_cache;
        
        if((!file_exists($this->getFotoDirectory().DS.$arquvo_foto)) || (!$this->getId())): 
            $arquvo_foto  = $dir_cache."padrao.png";  //SE NÃO FEZ UPLOAD DA FOTO DO PERFIL PEGA FOTO PADRÃO
        else:
            $arquvo_foto = $dir_cache.$this->getId().'.'.$this->getExtensao();
        endif;
    
        $div = "";
        if(!$link):
            $div = "<img src='$arquvo_foto' $width $height ".($class ? " class='$class'" : "").">";
        else:
            $div = $arquvo_foto;
        endif;
        
        
        return $div;
    }
        
    /**
     * 
     * @return string
     */
    public function getUrl() 
    {
        if($this->getId())
            return $this->uri()."/".$this->getId().".".$this->getExtensao();
        
        return $this->url;
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
     * 
     * @return string
     */
    public function getExtensao() 
    {
        return $this->extensao;
    }

    /**
     * 
     * @param string $url
     */
    public function setUrl($url) 
    {
        $this->url = $url;
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
     * 
     * @param string $extensao
     */
    public function setExtensao($extensao) 
    {
        $this->extensao = $extensao;
    }
    /**
     * 
     * @return string
     */
    public function getCredito()
    {
        return $this->credito;
    }

    /**
     * 
     * @param string $credito
     */
    public function setCredito($credito)
    {
        $this->credito = $credito;
    }

        
    /**
     * recupera e preenche objeto com metadados da foto
     * @return boolean true se coonseguir recuperar  
     * false caso arquivo não exista ou não obtenha permissão
     */
    public function selectMetaDados()
    {
        if(!$this->getUrl())
            return false;
        
	// Check if the variable is set and if the file itself exists before continuing
        if (!file_exists($this->getUrl()))
            return false;

        $exif_ifd0 = array();
        $exif_exif = array();
        
        $exif = exif_read_data($this->getUrl(), 0, true);
        foreach ($exif as $key => $section)
        {
            foreach ($section as $name => $val)
            {
                $val = rawurlencode($val);
                if($key == "IFD0"):
                    $exif_ifd0[$name] = $val;
                endif;

                if($key == "EXIF"):
                    $exif_exif[$name] = $val;
                endif;
            }
        }

        //error control
        $notFound = "";
        // Make 
        if (@array_key_exists('Make', $exif_ifd0)) {
                $camMake = $exif_ifd0['Make'];
        } else { $camMake = $notFound; }

        // Model
        if (@array_key_exists('Model', $exif_ifd0)) {
                $camModel = $exif_ifd0['Model'];
        } else { $camModel = $notFound; }

        // Exposure
        if (@array_key_exists('ExposureTime', $exif_ifd0)) {
                $camExposure = $exif_ifd0['ExposureTime'];
        } else { $camExposure = $notFound; }

        // Aperture
        if (@array_key_exists('ApertureFNumber', $exif_ifd0['COMPUTED'])) {
                $camAperture = $exif_ifd0['COMPUTED']['ApertureFNumber'];
        } else { $camAperture = $notFound; }

        // Date
        if (@array_key_exists('DateTime', $exif_ifd0)) {
                $camDate = $exif_ifd0['DateTime'];
        } else { $camDate = $notFound; }

        // Artist
        if (@array_key_exists('Artist', $exif_ifd0) || @array_key_exists('Author', $exif_ifd0)) {
                $camArtist = (isset($exif_ifd0['Artist'])?$exif_ifd0['Artist']:$exif_ifd0['Author']);
        } else { $camArtist = $notFound; }

        // Copyright
        if (@array_key_exists('Copyright', $exif_ifd0)) {
                $camCopyright = $exif_ifd0['Copyright'];
        } else { $camCopyright = $notFound;}

        //Comments
        if (@array_key_exists('Comments', $exif_ifd0)) {
                $camComments = $exif_ifd0['Comments'];
        } else { $camComments = $notFound;}

        // Ttile
        if(@array_key_exists('Title', $exif_ifd0)){
                $camTitle = $exif_ifd0['Title'];
        }
        elseif(@array_key_exists('ImageDescription', $exif_exif)){
                $camTitle = $exif_exif['ImageDescription'];

        }else{$camTitle = $notFound;}

        // ISO
        if (@array_key_exists('ISOSpeedRatings',$exif_exif)) {
                $camIso = $exif_exif['ISOSpeedRatings'];
        } else { $camIso = $notFound; }

        
        $this->setMake($camMake);
        $this->setModelo($camModel);
        $this->setExposicao($camExposure);
        $this->setAbertura($camAperture);
        $this->setDataCriacao($camDate);
        $this->setIso($camIso);
        $this->setArtista($camArtist);
        $this->setCopyright($camCopyright);
        $this->setTituloOriginal($camTitle);
        $this->setComentarioFoto($camComments);
        
        return true;
    } 
    
    /**
     * @param string $cache
     * @return string
     */    
    public function getLinkUrl($cache='') 
    {
        
        $filename = $cache.$this->getId().'.'.$this->getExtensao();
        if(($this->getId()) && (file_exists($this->getFotoDirectory().DS.$filename)) && ($this->getExtensao()))
        {
            $filename = $this->getFotoDirectory().DS.$cache.$this->getId().'.'.$this->getExtensao();
            
        }
        else    
        {
            $filename =  $this->getFotoDirectory().DS.$cache.'padrao.png';
            
        }
        return $filename;
    }
    
    /**
     * retorna o diretorio para as fotos
     * @return string string do caminho para o diretorio das fotos de perfil
     */
    public static function getFotoDirectory()
    {
        
    }
    
    /**
     * nao implementado
     */
    function uri()
    {
        
    }
    
    /**
     * 
     * @return Zend_Form_Element_File 
     */
    public function getFotoFile()
    {
        return $this->fotoFile;
    }

    public function setFotoFile(Zend_Form_Element_File $fotoFile)
    {
        $this->fotoFile = $fotoFile;
    }
    
    /**
     * 
     * @return insere registro no banco de dados e realiza upload da imagem
     */
    function insert() 
    {
        if(!$this->getExtensao())
        {
            $ext = Sec_File::getExtension($this->getFotoFile()->getFileName());
            $this->setExtensao($ext);
        }
        $result = parent::insert();
        if($result)
            return $this->uploadImg();
        
        return $result;
    }
    
    /**
     * seleciona no banco de dados os comentarios da foto
     * @param type $num
     * @param type $offset
     * @param Aew_Model_Bo_Usuario $usuarioAutor
     * @return array
     */
    function selectComentarios($num=0,$offset=0,Aew_Model_Bo_Usuario $usuarioAutor=null)
    {
        return $this->getComentarios();
    }

    /**
     * nao implementado
     */
    protected function createDao() {
        
    }

}
