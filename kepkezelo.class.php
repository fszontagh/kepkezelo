<?php
class kepkezelo {
    //image size
    var $size = 0;

    //image type
    var $type = '';

    //image default width
    var $width = 0;

    //image default height
    var $height = 0;

    //image bits
    var $bits = 0;

    //image channels
    var $channels = 0;

    //default mimetype
    var $mime = 'image/jpeg';

    //default picture path
    var $kep = 'tesztkep/ubuntu.png';
    var $kep_data = '';

    //resource of new image
    var $ujkep = '';

    //error status
    var $err_stat = 0;

    //default font
    var $font = 'fonts/cour.ttf';

    //new mimetype
    var $new_mime = 'png';

    //error background color
    var $error_background = "#90EE90";

    //error text color
    var $error_color = "#7B0000";
    //text positioning
    const TEXT_MIDDLE = 'middle'; //positioning to middle
    const TEXT_TOP = 'top'; //positioning to top
    const TEXT_BOTTOM = 'bottom'; //positioning to bottom
    const TEXT_CENTER = 'center'; //positioning to center
    const TEXT_LEFT = 'left'; //positioning to left
    const TEXT_RIGHT = 'right'; //positioning to right


    public function __construct($kep, $callback_start = null) {
        if ($callback_start != null) {
            if (isset($this->prefix)) {
                $kep = $this->prefix . $kep;
            }
            $status = call_user_func($callback_start, $kep);
            if ($status !== false) {
                return 0;
            }
        }

        $this->kep = $kep;
        if (file_exists($this->kep)) {
			if ($this->isImage($this->kep)===false) {
				return;
			}
            $tmp             = getimagesize($this->kep);
            $this->width     = $tmp[0];
            $this->height    = $tmp[1];
            $this->uj_width  = $this->width;
            $this->uj_height = $this->height;
            $this->mime      = $tmp['mime'];
            switch ($this->mime) {
				case "image/jpg":
                case "image/jpeg":
                    $this->ujkep = imagecreatefromjpeg($this->kep);
                    break; #ADD8E6
                case "image/png":
                    $this->ujkep = imagecreatefrompng($this->kep);
                    break;
                case "image/gif":
                    $this->ujkep = imagecreatefromgif($this->kep);
                    break;
            }
            if (isset($this->prefix)) {
                $this->kep = $this->prefix . $this->kep;
            }
            $this->save_alpha($this->ujkep);
        } else {
            $this->error($this->kep);
        }

    }

private function isImage($img){ 
    if(!getimagesize($img)){ 
        return FALSE; 
    }else{ 
        return TRUE; 
    } 
}
	public function levagas($width,$height) {
		$pos_x 	= 0;
		$pos_y = 0;	
		$this->atmeretezes($width,$height,true);
		$s = 0;
		
		if ($this->uj_width > $this->uj_height) {
			$pos_x = ($this->uj_width - $width) / 2;
			$s = $this->uj_height;
		}
		
		if ($this->uj_height > $this->uj_width) {
			$pos_y = ($this->uj_height - $height) / 2;
			$s = $this->uj_width;
		}

		$tmp = imagecreatetruecolor($width, $height);
		
		imagecopyresampled($tmp, $this->ujkep, 0, 0, 
		$pos_x, $pos_y, 
		$width, $height,
		$s, 
		$s);
		unset($this->ujkep);
		$this->ujkep = $tmp;
		unset($tmp);		
	}
	
    public function atmeretezes($width, $height,$to_crop=false) {
		$orig_w = $width;
		$orig_h = $height;
		
        if ($this->err_stat == 1) {
            return $this->ujkep;
        }
        $this->uj_width  = imagesx($this->ujkep);
        $this->uj_height = imagesy($this->ujkep);
	
			if ($width > $this->uj_width) {
				$width = $this->uj_width;
			}
			if ($height > $this->uj_height) {
				$height = $this->uj_height;
			}			
			
			if ($this->uj_height > $this->uj_width) {
				$width = $this->uj_width * ($height / $this->uj_height);
			} else if ($this->uj_height < $this->uj_width) {
				$height = $this->uj_height * ($width / $this->uj_width);
			}
			
			if ($to_crop === true) {
				if ($orig_w == $orig_h) {
					$diff = 0;
					if ($width < $height) {
						$diff = $height - $width;
					}else{
						$diff = $width - $height;
					}
						$height+=$diff;
						$width+=$diff;					
				}
			}
			
        $tmp_img = imagecreatetruecolor($width, $height);
        imagecopyresized($tmp_img, $this->ujkep, 0, 0, 0, 0, $width, $height, $this->uj_width, $this->uj_height);
        $this->ujkep     = $tmp_img;
        $this->uj_width  = $width;
        $this->uj_height = $height;
    }
    public function szoveg($szoveg, $meret = 12, $x = kepkezelo::TEXT_CENTER, $y = kepkezelo::TEXT_MIDDLE, $szin = '#ADD8E6', $forgatas = 0) {
        if ($this->err_stat == 1) {
            return 0;
        }
        $this->uj_width  = imagesx($this->ujkep);
        $this->uj_height = imagesy($this->ujkep);
        $all_width       = 0;
        $all_height      = 0;
        $szoveg          = utf8_decode($szoveg);
        for ($i = 0; $i <= strlen($szoveg) - 1; $i++) {
            $dimensions = imagettfbbox($meret, $forgatas, $this->font, $szoveg[$i]);
            $all_width += $dimensions[2];
            if ($all_height < $dimensions[4]) {
                $all_height = $dimensions[4];
            }
        }
        if (is_numeric($x)) {
            if ($x > $this->uj_width) {
                $x = $this->uj_width - (strlen($this->uj_width) * $all_width);
            }
        } else {
            switch ($x) {
                case kepkezelo::TEXT_CENTER:
                    $x = ($this->uj_width / 2) - ($all_width / 2);
                    break;
                case kepkezelo::TEXT_LEFT:
                    $x = 0;
                    break;
                case kepkezelo::TEXT_RIGHT:
                    $x = $this->uj_width - $all_width;
                    break;
            }
        }
        if (is_numeric($y)) {
            if ($y > $this->uj_height) {
                $y = $this->uj_height - (strlen($this->uj_height) * $all_height);
            }
        } else {
            switch ($y) {
                case kepkezelo::TEXT_MIDDLE:
                    $y = ($this->uj_height / 2) - ($all_height / 2);
                    break;
                case kepkezelo::TEXT_TOP:
                    $y = $meret;
                    break;
                case kepkezelo::TEXT_BOTTOM:
                    if ($forgatas > 180 AND $forgatas < 270) {
                        $y = $this->uj_height - $all_height - 20;
                    } else {
                        $y = $this->uj_height - $all_height;
                    }
                    break;
            }
        }
        $tmpclr  = $this->hex2RGB($szin);
        $szovegc = imagecolorallocate($this->ujkep, $tmpclr['red'], $tmpclr['green'], $tmpclr['blue']);

        imagettftext($this->ujkep, $meret, $forgatas, $x, $y, $szovegc, $this->font, $szoveg);
        return $this->ujkep;
    }
    public function keret($vastagsag = 1, $keret_szine) {
        if ($this->err_stat == 1) {
            return null;
        }
        $this->uj_width  = imagesx($this->ujkep);
        $this->uj_height = imagesy($this->ujkep);
        $borderC         = $this->hex2RGB($keret_szine);
        $border_color    = imagecolorallocate($this->ujkep, $borderC['red'], $borderC['green'], $borderC['blue']);
        $x1              = 0;
        $y1              = 0;
        $x2              = $this->uj_width - 1;
        $y2              = $this->uj_height - 1;
        for ($i = 1; $i <= $vastagsag; $i++) {
            imagerectangle($this->ujkep, $x1++, $y1++, $x2--, $y2--, $border_color);
        }
    }
    public function forgatas($szog, $hatterszin = "#00FF00") {
        if ($this->err_stat == 1) {
            return null;
        }

        $this->uj_width  = imagesx($this->ujkep);
        $this->uj_height = imagesy($this->ujkep);
        $clr             = $this->hex2RGB($hatterszin);
        $fc              = imagecolorallocate($this->ujkep, $clr['red'], $clr['green'], $clr['blue']);
        $this->ujkep     = imagerotate($this->ujkep, $szog, $fc);
        $this->save_alpha($this->ujkep);
        $this->uj_width  = imagesx($this->ujkep);
        $this->uj_height = imagesy($this->ujkep);
    }
    public function tukrozes($color, $gheight = 100, $start_atlatszosag = 100) {
        if ($this->err_stat == 1) {
            return null;
        }
        $this->uj_width  = imagesx($this->ujkep);
        $this->uj_height = imagesy($this->ujkep);
        $gheight         = round(($this->uj_height / 100) * $gheight);
        $gradparts       = $this->hex2RGB($color);
        $gdGradientColor = ImageColorAllocate($this->ujkep, $gradparts['red'], $gradparts['green'], $gradparts['blue']);
        $background      = imagecreatetruecolor($this->uj_width, $gheight);
        $newImage        = imagecreatetruecolor($this->uj_width, $this->uj_height);

        $this->save_alpha($newImage);
        $this->save_alpha($background);

        for ($x = 0; $x < $this->uj_width; $x++) {
            for ($y = $gheight; $y > 0; $y--) {
                imagecopy($newImage, $this->ujkep, $x, $y, $x, $this->uj_height - $y, 1, 1);
            }
        }
        imagecopymerge($background, $newImage, 0, 0, 0, 0, $this->uj_width, $this->uj_height, 100);
        $gradient_line = imagecreatetruecolor($this->uj_width, 1);
        $this->save_alpha($gradient_line);
        $transparency  = 100 - $start_atlatszosag;
        $transparency2 = 0;
        $leptek        = $start_atlatszosag / $gheight;
        $leptek2       = 127 / $gheight;
        for ($i = 0; $i < $this->uj_height; $i++) {
            $transparent = imagecolorallocatealpha($gradient_line, $gradparts['red'], $gradparts['green'], $gradparts['blue'], $transparency2);
            imagefill($gradient_line, 0, 0, $transparent);
            $this->save_alpha($gradient_line);
            imagecopymerge($background, $gradient_line, 0, $i, 0, 0, imagesx($gradient_line), imagesy($gradient_line), $transparency);
            if ($transparency != 100) {
                $transparency = $transparency + $leptek;
            }
            $transparency2 = $transparency2 + $leptek2;
        }
        $tmp = imagecreatetruecolor($this->uj_width, ($this->uj_height - 2) + $gheight);
        $this->save_alpha($tmp);
        imagecopymerge($tmp, //célkép
            $this->ujkep, //forráskép
            0, //cél X
            0, //cél y
            0, //forrás x
            1, //forrás y
            $this->uj_width, //forrás w
            $this->uj_height, //forrás h
            100); //forrás h
        imagecopymerge($tmp, //célkép
            $background, //forráskép
            0, //cél X
            $this->uj_height - 1, //cél y
            0, //forrás x
            1, //forrás y
            $this->uj_width, //forrás w
            $gheight, //forrás h
            100); //forrás h
        $this->ujkep = $tmp;
        imagedestroy($background);
        imagedestroy($newImage);
        $this->uj_width  = imagesx($this->ujkep);
        $this->uj_height = imagesy($this->ujkep);
        return $this->ujkep;
    }

    public function mutat($etag = false) {
        if ($etag === true) {
            ob_start();
        }
        if ($this->new_mime == 'png') {
            header("Content-Type: image/png");
            imagepng($this->ujkep);
        } elseif ($this->new_mime == 'jpg' OR $this->new_mime == 'jpeg') {
            header("Content-Type: image/jpeg");
            imagejpeg($this->ujkep, null, 100);
        }
        if ($etag === true) {
            $content = ob_get_clean();
            $etag    = md5($content);
            header("E-tag: " . $etag);
            die($content);
        }
        imagedestroy($this->ujkep);
    }

    public function mentes($nev, $minoseg = 80,$type=null) {
		if ($type==null) {
			$type_=explode("/",$this->mime);
			$type = end($type_);
		}
		$ret = false;
		switch ($type) {
			case "png":
				$ret = imagepng($this->ujkep, $nev, $minoseg);
			break;
			case "jpeg":
			case "jpg":
				$ret = imagejpeg($this->ujkep, $nev, $minoseg);
			break;
			case "gif":
				$ret = imagegif($this->ujkep, $nev, $minoseg);
			break;			
		}
        if (is_resource($this->kep)) {
			imagedestroy($this->kep);
		}
		if (is_resource($this->ujkep)) {
			imagedestroy($this->ujkep);
		}
		return $ret;
    }
    public function adat($callback = false) {
        ob_start();
        if ($this->new_mime == 'png') {
            imagepng($this->ujkep);
        } elseif ($this->new_mime == 'jpg' OR $this->new_mime == 'jpeg') {
            imagejpeg($this->ujkep, null, 100);
        }
        $pic = ob_get_clean();

        if ($callback === false) {
            return $pic;
        } else {
            return call_user_func($callback, $this->kep, $pic);
        }
        imagedestroy($this->ujkep);
    }
    private function error($txt) {
        $this->kep    = null;
        $this->width  = 200;
        $this->height = 200;
        $this->mime   = 'image/png';
        $im           = imagecreatetruecolor($this->width, $this->height);

        $textc   = $this->hex2RGB($this->error_color);
        $szovegc = imagecolorallocate($im, $textc['red'], $textc['green'], $textc['blue']);
        $backg   = $this->hex2RGB($this->error_background);
        $hatter  = imagecolorallocate($im, $backg['red'], $backg['green'], $backg['blue']);
        imagefill($im, 0, 0, $hatter);
        imagettftext($im, 12, 0, 10, 20, $szovegc, $this->font, $txt);
        $this->err_stat = 1;
        $this->ujkep    = $im;
    }
    public function atlatszoszin($color) {
        $clr               = $this->hex2RGB($color);
        $transparent_color = imagecolorallocate($this->ujkep, $clr['red'], $clr['green'], $clr['blue']);
        imagecolortransparent($this->ujkep, $transparent_color);
    }
    private function save_alpha($im) {
        imagealphablending($im, false);
        imagesavealpha($im, true);
    }
    private function blend_alpha($im) {
        imagesavealpha($im, false);
        imagealphablending($im, true);
    }
    private function hex2RGB($hexStr, $returnAsString = false, $seperator = ',') {
        $hexStr   = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
        $rgbArray = array();
        if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
            $colorVal          = hexdec($hexStr);
            $rgbArray['red']   = 0xFF & ($colorVal >> 0x10);
            $rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
            $rgbArray['blue']  = 0xFF & $colorVal;
        } elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
            $rgbArray['red']   = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
            $rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
            $rgbArray['blue']  = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
        } else {
            return false; //Invalid hex color code
        }
        return $returnAsString ? implode($seperator, $rgbArray) : $rgbArray; // returns the rgb string or the associative array
    }
}
?>
