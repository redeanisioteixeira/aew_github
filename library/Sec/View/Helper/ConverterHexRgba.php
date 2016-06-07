<?php
class Sec_View_Helper_ConverterHexRgba
{
    protected $view;

    public function setView($view)
    {
        $this->view = $view;
    }

    public function ConverterHexRgba($color = '', $opacity = false, $random = false)
    {
		$hex = array_merge(range(0, 9), range('A', 'F'));
		$default = 'rgb(0,0,0)';

		if($random):
			$color = '';
			while(strlen($color) < 6):
				$num = rand(0, 15);
				$color .= $hex[$num];
			endwhile;
			$opacity = false;
		endif;

		//Return default if no color provided
		if(empty($color)):
            return $default; 
        endif;
        
		//Sanitize $color if "#" is provided 
        if ($color[0] == '#' ):
            $color = substr( $color, 1 );
        endif;
 
        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6):
            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
        elseif ( strlen( $color ) == 3 ):
            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
        else:
            return $default;
        endif;
 
        //Convert hexadec to rgb
        $rgb =  array_map('hexdec', $hex);
 
        //Check if opacity is set(rgba or rgb)
        if($opacity):
            if(abs($opacity) > 1):
                $opacity = 1.0;
            endif;
            $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
        else:
            $output = 'rgb('.implode(",",$rgb).')';
        endif;

        return $output;
    }
}
