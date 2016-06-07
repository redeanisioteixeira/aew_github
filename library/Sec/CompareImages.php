<?php
class Sec_CompareImages{
	const ALTURA  = 8;
	const LARGURA = 8;
 	
	private function mimeType($i)
	{
		/*returns array with mime type and if its jpg or png. Returns false if it isn't jpg or png*/
		$mime = GetImageSize($i);
		$return = array($mime[0],$mime[1]);
      
		switch ($mime['mime']):
			case 'image/jpeg':
				$return[] = 'jpg';
				return $return;

			case 'image/png':
				$return[] = 'png';
				return $return;

			default:
				return false;

		endswitch;
    }  
    
	private function createImage($i)
	{
		/*retuns image resource or false if its not jpg or png*/
		$mime = $this->mimeType($i);
      
		if($mime[2] == 'jpg'):
			return ImageCreateFromJpeg ($i);
		elseif ($mime[2] == 'png'): 
				return ImageCreateFromPng ($i);
			else:
				return false; 
			endif;
    }
    
	private function resizeImage($i,$source)
	{
		/*resizes the image to a 8x8 squere and returns as image resource*/
		$mime = $this->mimeType($source);
      
		$t = ImageCreateTrueColor(self::LARGURA, self::ALTURA);
		
		$source = $this->createImage($source);
		
		ImageCopyResized($t, $source, 0, 0, 0, 0, self::LARGURA, self::ALTURA, $mime[0], $mime[1]);
		
		return $t;
	}
    
    private function colorMeanValue($i)
	{
		/*returns the mean value of the colors and the list of all pixel's colors*/
		$colorList = array();

		for($a = 0; $a<self::LARGURA; $a++):
			for($b = 0; $b<self::ALTURA; $b++):
				$rgb = imagecolorat($i, $a, $b);
				$hex = $rgb & 0xFF;
				$hex = ($hex<=50 ? 0 : $hex);
				$colorList[] = $hex;
			endfor;
		endfor;

		return array(array_sum($colorList)/(self::LARGURA * self::ALTURA), $colorList);
	}
    
    private function bits($colorMean)
	{
		/*returns an array with 1 and zeros. If a color is bigger than the mean value of colors it is 1*/
		$bits = array();
		 
		foreach($colorMean[1] as $color):
			$bits[] = ($color >= $colorMean[0] ? 1 : 0);
		endforeach;

		return $bits;

	}
	
    public function compare($a, $b)
	{
		/*main function. returns the hammering distance of two images' bit value*/
		$i1 = $this->createImage($a);
		$i2 = $this->createImage($b);
		
		if(!$i1 || !$i2):
			return false;
		endif;	

		$i1 = $this->resizeImage($i1,$a);
		$i2 = $this->resizeImage($i2,$b);
		
		ImageFilter($i1, IMG_FILTER_GRAYSCALE);
		ImageFilter($i2, IMG_FILTER_GRAYSCALE);
		
		$colorMean1 = $this->colorMeanValue($i1);
		$colorMean2 = $this->colorMeanValue($i2);
		
		$bits1 = $this->bits($colorMean1);
		$bits2 = $this->bits($colorMean2);

		$hammeringDistance = 0;
		$Tbits = self::LARGURA * self::ALTURA;
		for($a = 0; $a < $Tbits; $a++):
			if($bits1[$a] != $bits2[$a]):
				$hammeringDistance++;
			endif;
		endfor;
		
		$hammeringDistance = round($hammeringDistance * 100 / $Tbits, 2);

		return $hammeringDistance;
	}

	public function vazio($a)
	{
		$img = $this->createImage($a);
		$img = $this->resizeImage($img, $a);
		
		ImageFilter($img, IMG_FILTER_GRAYSCALE);

		$colorMean = $this->colorMeanValue($img);
		$bits      = $this->bits($colorMean);
		$Tbits     = (self::LARGURA * self::ALTURA);

		return (array_sum($bits) >= $Tbits - 2 ? true : false);
	}
}
?>
