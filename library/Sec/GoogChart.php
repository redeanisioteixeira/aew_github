<?php
# Chart
#
# by Ludwig Pettersson
# <http://luddep.se>
#
# With help from Fredrik Holmstr�m
# <http://loveandtheft.org/>
#

# Copyright (c) 2008 Ludwig Pettersson

# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:

# The above copyright notice and this permission notice shall be included in
# all copies or substantial portions of the Software.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
# THE SOFTWARE.

class Sec_GoogChart
{
    // Constants
    const BASE = 'http://chart.apis.google.com/chart?';

    // Variables
    protected $types = array(
                            'pie' => 'p3',
                            'line' => 'lc',
                            'sparkline' => 'ls',
                            'bar-horizontal' => 'bhg',
                            'bar-vertical' => 'bvg',
                            'qr-code' => 'qr',
                        );

    protected $type;
    protected $title;
    protected $classe;
    protected $data = array();
    protected $size = array();
    protected $color = array();
    protected $fill = array();
    protected $labelsXY = false;
    protected $legend;
    protected $useLegend = true;
    protected $background = 'a,s,ffffff';
    protected $save; 
    protected $path; 

    protected $query = array();

    // debug
    public $debug = array();

    // Return string
    public function __toString()
    {
        return $this->display();
    }


    /** Create chart
    */
    protected function display()
    {
            // Create query
        if($this->types[strtolower($this->type)]=='qr'):
                $this->query = array(
                                    'cht'   => $this->types[strtolower($this->type)],// Type
                                    'chl'   => $this->data['values'],// Data values
                                    'chld'  => $this->chld, // Data labels
                                    'chs'   => $this->size[0].'x'.$this->size[1], // Size
                                );

        else:
                $this->query = array(
                                    'cht'   => $this->types[strtolower($this->type)], // Type
                                    'chtt'  => $this->title, // Title
                                    'chd'   => 't:'.$this->data['values'], // Data
                                    'chl'   => $this->data['names'], // Data labels
                                    'chdxl' => ( ($this->useLegend) && (is_array($this->legend)) ) ? implode('|',$this->legend) : null, // Data legend
                                    'chs'   => $this->size[0].'x'.$this->size[1], // Size
                                    'chco'  => preg_replace( '/[#]+/', '', implode(',',$this->color)), // Color ( Remove # from string )
                                    'chm'   => preg_replace( '/[#]+/', '', implode('|',$this->fill)), // Fill ( Remove # from string )
                                    'chxt'  => ( $this->labelsXY == true) ? 'x,y' : null, // X & Y axis labels
                                    'chf'   => preg_replace( '/[#]+/', '', $this->background), // Background color ( Remove # from string )
                                );
        endif;

        // Return chart
        $chat = $this->img(
                            Sec_GoogChart::BASE.http_build_query($this->query),
                            $this->path,
                            $this->title,
                            $this->classe,
                            $this->save
                        );
        
        return $chat; 
    }

    /** Set attributes
    */
    public function setChartAttrs( $attrs )
    {
        // debug
        $this->debug[] = $attrs;

        foreach( $attrs as $key => $value )
        {
            $this->{"set$key"}($value);
        }
    }


    /** Set Url
    */
    protected function setSave( $save )
    {
        $this->save = $save;
    }

    /** Set type
    */
    protected function setType( $type )
    {
        $this->type = $type;
    }


    /** Set title
    */
    protected function setTitle( $title )
    {
        $this->title = $title;
    }

    /** Set classe
    */
    protected function setClasse( $classe )
    {
        $this->classe = $classe;
    }

    /** Set data
    */
    protected function setData( $data )
    {
        // Clear any previous data
        unset( $this->data );

        // Check if multiple data
        if( is_array(reset($data)) )
        {
            /** Multiple sets of data
            */
            foreach( $data as $key => $value )
            {
                // Add data values
                $this->data['values'][] = implode( ',', $value );

                // Add data names
                $this->data['names'] = implode( '|', array_keys( $value ) );
            }
            /** Implode data correctly
            */
            $this->data['values'] = implode('|', $this->data['values']);
            /** Create legend
            */
            $this->legend = array_keys( $data );
        }
        else
        {
            /** Single set of data
            */
            // Add data values
            $this->data['values'] = implode( ',', $data );

            // Add data names
            $this->data['names'] = implode( '|', array_keys( $data ) );
        }

    }

    /** Set chd
    */
    protected function setChd( $chd )
    {
            $this->chd = $chd;
    }

    /** Set chds
    */
    protected function setChds( $chds )
    {
        $this->chds = $chds;
    }

    /** Set legend
    */
    protected function setLegend( $legend )
    {
        $this->useLegend = $legend;
    }

    /** Set size
    */
    protected function setSize( $width, $height = null )
    {
        // check if width contains multiple params
        if(is_array( $width ) )
        {
            $this->size = $width;
        }
        else
        {
            // set each individually
            $this->size[] = $width;
            $this->size[] = $height;
        }
    }

    /** Set chld
    */
    protected function setChld( $chld )
    {
        $this->chld = $chld;
    }

    /** Set color
    */
    protected function setColor( $color )
    {
        $this->color = $color;
    }

    /** Set labels
    */
    protected function setLabelsXY( $labels )
    {
        $this->labelsXY = $labels;
    }

    /** Set fill
    */
    protected function setFill( $fill )
    {
        // Fill must have atleast 4 parameters
        if( count( $fill ) < 4 )
        {
            // Add remaining params
            $count = count( $fill );
            for( $i = 0; $i < $count; ++$i )
                $fill[$i] = 'b,'.$fill[$i].','.$i.','.($i+1).',0';
        }

        $this->fill = $fill;
    }

    /** Set background
    */
    protected function setBackground( $background )
    {
        $this->background = 'bg,s,'.$background;
    }

    /** Set background
    */
    protected function setPath( $path )
    {
        $this->path = $path;
    }
    
    /** Create img html tag
     * 
     * 	
     */
    protected function img($url, $path_qrcode, $alt = null, $classe = null, $save = null)
    {
	if($save != null):
            copy($url, $path_qrcode."/$save.png");
	endif;
        
    	$resultado = sprintf('<img class="img-responsive %s" src="%s" alt="%s" width="%spx" height="%spx" />', $classe, $url, $alt, $this->size[0], $this->size[1]);
    	
        return $resultado;
    }
}