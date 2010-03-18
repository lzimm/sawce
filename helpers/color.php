<?php
class ColorHelper {               
    private static $oldHue;
    private static $newHue;
    private static $valueMultiplier;
    /**
     * @param @oldHue 0 is red, 120 is green, 240 is blue. See this interactive color wheel: http://r0k.us/graphics/SIHwheel.html
     */    
    public static function generate($outputFilename, $inputFilename, $oldHue, $newHue, $valueMultiplier) {
        if (! file_exists($inputFilename)) { throw new Exception('Master CSS file does not exist: ' . $inputFilename); }
        self::$oldHue = $oldHue;
        self::$newHue = $newHue;
        self::$valueMultiplier = $valueMultiplier;
        $contents = @file_get_contents($inputFilename);
        $contents = preg_replace_callback('/#([0-9A-F][0-9A-F][0-9A-F][0-9A-F][0-9A-F][0-9A-F])([^0-9A-F])/i', array('ColorSchemeHelper', 'changeColor'), $contents);
        $contents = preg_replace_callback('/#([0-9A-F][0-9A-F][0-9A-F])([^0-9A-F])/i', array('ColorSchemeHelper', 'changeColor'), $contents);        
        @file_put_contents($outputFilename, $contents);
    }        
    
    public static function changeColor($matches) {                
        $RGB = self::hexToRGB(self::expandHex($matches[1]));
        $HSV = self::RGB_to_HSV($RGB[0], $RGB[1], $RGB[2]);
        $tolerance = 30; 
        if (! self::shadeOfGray($RGB[0], $RGB[1], $RGB[2]) && self::angleDifference($HSV[0], self::$oldHue) < $tolerance) {
            $HSV[0] = self::correctOvershoot(self::scale($HSV[0], self::$oldHue-$tolerance, self::$oldHue+$tolerance, self::$newHue-$tolerance, self::$newHue+$tolerance)); 
            $HSV[2] *= self::$valueMultiplier;              
        }
        $RGB = self::HSV_to_RGB($HSV[0], $HSV[1], $HSV[2]);            
        return '#' . self::rgbToHex($RGB) . $matches[2];
    }    
    
    public static function shadeOfGray($r, $g, $b) {
        return $r == $g && $g == $b;
    }
    
    public static function angleDifference($a, $b) {
        return ($difference = abs($a - $b)) <= 180 ? $difference : (360 - $difference); 
    }    
    
    public static function expandHex($hex) {
        return strlen($hex) == 6 ? $hex : ($hex{0}.$hex{0}.$hex{1}.$hex{1}.$hex{2}.$hex{2});
    }
    
    public static function hexToRGB($hex) {
        // Code from cory@lavacube.com, "dechex", http://ca3.php.net/manual/en/function.dechex.php
        // [Jon Aquino 2005-11-02]
        $rgb = array();
        $rgb[0] = hexdec(substr($hex, 0, 2));
        $rgb[1] = hexdec(substr($hex, 2, 2));
        $rgb[2] = hexdec(substr($hex, 4, 2));
        return $rgb;
    }
    
    public static function rgbToHex($rgb) {
        // Code from cory@lavacube.com, "dechex", http://ca3.php.net/manual/en/function.dechex.php
        // [Jon Aquino 2005-11-02]
        foreach( $rgb as $val )
        {
            $out .= str_pad(dechex($val), 2, '0', STR_PAD_LEFT);
        }        
        return $out;        
    }
        
    // Code from Mike Snead, "RGB color to Hue/Sat/Brightness Conversion (and back)", 
    // http://www.phpfreaks.com/quickcode/RGB-color-to-HueSatBrightness-Conversion-and-back/537.php
    // [Jon Aquino 2005-11-02]    
    public static function RGB_to_HSV ( $r , $g , $b )
    {
        $r = $r/255;
        $g = $g/255;
        $b = $b/255;
        
        $MAX = max($r,$g,$b);
        $MIN = min($r,$g,$b);
        
        if     ($MAX == $MIN) return array(0,0,$MAX);
        if     ($r == $MAX) $HUE = ((0 + (($g - $b)/($MAX-$MIN))) * 60);
        elseif ($g == $MAX) $HUE = ((2 + (($b - $r)/($MAX-$MIN))) * 60);
        elseif ($b == $MAX) $HUE = ((4 + (($r - $g)/($MAX-$MIN))) * 60);
        if     ( $HUE < 0 ) $HUE += 360;
        
        return array($HUE,(($MAX - $MIN)/$MAX),$MAX);
    }
    
    // Code from Mike Snead, "RGB color to Hue/Sat/Brightness Conversion (and back)", 
    // http://www.phpfreaks.com/quickcode/RGB-color-to-HueSatBrightness-Conversion-and-back/537.php
    // [Jon Aquino 2005-11-02]    
    public static function HSV_to_RGB ( $H , $S , $V )
    {	
        if ($S == 0) return array($V * 255,$V * 255,$V * 255);
    
        $Hi = floor($H/60);
        $f  = (($H/60) - $Hi);
        $p  = ($V * (1 - $S));
        $q  = ($V * (1 - ($S * $f)));
        $t  = ($V * (1 - ($S * (1 - $f))));
    
        switch ( $Hi )
        {
            case 0  : $red = $V; $gre = $t; $blu = $p; break;
            case 1  : $red = $q; $gre = $V; $blu = $p; break;
            case 2  : $red = $p; $gre = $V; $blu = $t; break;
            case 3  : $red = $p; $gre = $q; $blu = $V; break;
            case 4  : $red = $t; $gre = $p; $blu = $V; break;
            case 5  : $red = $V; $gre = $p; $blu = $q; break;
            default : exit("error -- invalid parameters\n\n");
        }
    
        return array(round($red * 255),round($gre * 255),round($blu * 255));
    }    
    
    public static function correctOvershoot($hue) {
        return $hue >= 360 ? $hue - 360 : ($hue < 0 ? $hue + 360 : $hue);
    }
    
    public static function scale($x, $oldMin, $oldMax, $newMin, $newMax) {
        return $newMin + (($x - $oldMin) * ($newMax - $newMin)/($oldMax - $oldMin));        
    }
}

    function RGB_to_HSV ( $r , $g , $b )
    {
        $r = $r/255;
        $g = $g/255;
        $b = $b/255;
        
        $MAX = max($r,$g,$b);
        $MIN = min($r,$g,$b);
        
        if     ($MAX == $MIN) return array(0,0,$MAX);
        if     ($r == $MAX) $HUE = ((0 + (($g - $b)/($MAX-$MIN))) * 60);
        elseif ($g == $MAX) $HUE = ((2 + (($b - $r)/($MAX-$MIN))) * 60);
        elseif ($b == $MAX) $HUE = ((4 + (($r - $g)/($MAX-$MIN))) * 60);
        if     ( $HUE < 0 ) $HUE += 360;
        
        return array($HUE,(($MAX - $MIN)/$MAX),$MAX);
    }
    
    // Code from Mike Snead, "RGB color to Hue/Sat/Brightness Conversion (and back)", 
    // http://www.phpfreaks.com/quickcode/RGB-color-to-HueSatBrightness-Conversion-and-back/537.php
    // [Jon Aquino 2005-11-02]    
    function HSV_to_RGB ( $H , $S , $V )
    {	
        if ($S == 0) return array($V * 255,$V * 255,$V * 255);
    
        $Hi = floor($H/60);
        $f  = (($H/60) - $Hi);
        $p  = ($V * (1 - $S));
        $q  = ($V * (1 - ($S * $f)));
        $t  = ($V * (1 - ($S * (1 - $f))));
    
        switch ( $Hi )
        {
            case 0  : $red = $V; $gre = $t; $blu = $p; break;
            case 1  : $red = $q; $gre = $V; $blu = $p; break;
            case 2  : $red = $p; $gre = $V; $blu = $t; break;
            case 3  : $red = $p; $gre = $q; $blu = $V; break;
            case 4  : $red = $t; $gre = $p; $blu = $V; break;
            case 5  : $red = $V; $gre = $p; $blu = $q; break;
            default : exit("error -- invalid parameters\n\n");
        }
    
        return array(round($red * 255),round($gre * 255),round($blu * 255));
    }
?>   