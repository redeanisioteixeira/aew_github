<?php

/**
 * BO da entidade Usuario Tipo
 */

class Aew_Model_Bo_ConteudoTipo extends Sec_Model_Bo_Abstract
{
    protected $nomeconteudotipo;
    static $DOCUMENTO_EXPERIMENTO=1,$APRESENTACAO = 3,$PLANILHA=2, $VIDEO = 5;
    static $AUDIO=4,$IMAGEM=6,$ANIMACAO_SIMULACAO=7,$SITE=8,$SOFTWARE_EDUCACIONAL=9,$SEQUENCIA_DIDATICA=10;
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->setDao(new  Aew_Model_Dao_ConteudoTipo());
    }
    
    /**
     * retorna nome do tipo conteudo
     * @return string
     */
    public function getNome() {
        return $this->nomeconteudotipo;
    }
            
    /**
     * 
     * @param string $nome
     */
    public function setNome($nome) {
        $this->nomeconteudotipo = $nome;
    }
    
    /**
     * retorna url do icone  para representacao do conteudo
     * @param boolean $flagPequeno
     * @return string
     */
    public function getIconePortal($flagPequeno = false )
    {
        $extensao = ".png";
        switch($this->getNome()) 
        {
            case 'Animação/Simulação':      $icone = 'icone_animacao_portal';       break;
            case 'Apresentação':            $icone = 'icone_apresentacao_portal';   break;
            case 'Áudio':                   $icone = 'icone_audio_portal';          break;
	    case 'Documento/Experimento':   $icone = 'icone_documento_portal';      break;
	    case 'Imagem':                  $icone = 'icone_imagem_portal';         break;
	    case 'Planilha':                $icone = 'icone_planilha_portal';       break;
            case 'Vídeo':                   $icone = 'icone_video_portal';          break;
            case 'Curso':                   $icone = 'icone_curso_portal';          break;
            case 'Software Educacional':    $icone = 'icone_software_portal';       break;
	    default:                        $icone = 'icone_site_portal';           break;
	}

	$icone .= ( $flagPequeno ) ? "_peq" : "";
	$icone .= $extensao;
        return $icone;
    }
    
    /**
     * Retorna o nome do icone para o tipo de conteudo
     * @param $conteudoTipo
     * @return string
     */
    public function getIconeTipo()
    {
        switch($this->getNome()):
		case 'Animação/Simulação':
                        $icone = 'icone-animacao';
					break;

			case 'Apresentação':
					$icone = 'icone-apresentacao';
					break;

			case 'Áudio':
					$icone = 'icone-audio';
					break;

			case 'Documento/Experimento':
					$icone = 'icone-documento';
					break;

			case 'Imagem':
					$icone = 'icone-imagem';
					break;

			case 'Planilha':
					$icone = 'icone-planilha';
					break;

			case 'Vídeo':
					$icone = 'icone-video';
					break;

			case 'Curso':
					$icone = 'icone-curso';
					break;

			case 'Software Educacional':
					$icone = 'icone-software';
					break;

			case 'Sequência Didática':
				$icone = 'icone-sequencia';
				break;

			default:
					$icone = 'icone-site';
					break;
		endswitch;

		return $icone;
	}

        /**
         * cria objeto de acesso ao banco de dados
         * @return \Aew_Model_Dao_ConteudoTipo
         */
    protected function createDao() {
        $dao =  new Aew_Model_Dao_ConteudoTipo();
        return $dao;
    }

}