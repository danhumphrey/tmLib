<?php
/**
  * Captcha class
  *
  * @package tmLib
  * @subpackage AccessControl
  * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
  */

/**
 * Captcha class manages the generation and validation of captcha's
 *
 * @package tmLib
 * @subpackage AccessControl
 * @author Dan Humphrey <dan.humphrey@technomedia.co.uk>
 * @access public
 * @version 1.0
 */
class Captcha {
    private $session;
    private $baseImage;
    private $stringLength;
    private $captcha;
    private $captchaVar;

    /**
     * Constructs the Captcha
     *
     * @param Session $session a session object
     */
    function __construct($session) {
        $this->session = $session;
    }

    /**
     * Generate the captcha image
     *
     * @param string $baseImage (optional) the path to the base captcha image
     * @param int $stringLength (optional) the number of characters of captcha text
     */
    function generate($baseImage=null,$stringLength=5) {
        $this->baseImage = $baseImage;
        $this->stringLength = $stringLength;
        $this->generateImage();
        header("Content-type: image/png");
        imagepng($this->captcha);
        imagedestroy($this->captcha);
    }
    /**
     * Validates the captcha against a request variable
     *
     * @param IDataSet $request a request object implementing the IDataSet interface
     * @param string $captchaVar the request variable name to validate
     * @return bool true if valid
     */
    function validate($request,$captchaVar) {
        $this->captchaVar = $captchaVar;
        $key = $this->session->get('tmLibCaptchaKey');
        $this->session->remove('tmLibCaptchaKey');
        if(md5($request->get($this->captchaVar)) == $key) {
            return true;
        }
    }
    private function generateImage() {
        $this->captcha = ($this->baseImage) ? imagecreatefrompng($this->baseImage): $this->createNewImage();
        $textCol = imagecolorallocate($this->captcha, 5, 5, 4);
        $lime = imagecolorallocate($this->captcha,204,255,0);
        $red = imagecolorallocate($this->captcha,255,0,0);
        imageline($this->captcha,rand(0,99),0,rand(2,50),rand(2,50),$lime);
        imageline($this->captcha,rand(30,50),0,rand(2,70),rand(15,30),$red);
        imageline($this->captcha,rand(60,90),0,rand(50,85),rand(22,44),$lime);
        imageline($this->captcha,rand(20,49),0,rand(30,85),rand(20,51),$lime);
        imageline($this->captcha,rand(0,99),0,rand(2,50),rand(2,50),$lime);
        imageline($this->captcha,rand(0,99),0,rand(2,50),rand(2,50),$red);
        imageline($this->captcha,rand(0,99),0,rand(2,50),rand(2,50),$lime);
        imageline($this->captcha,rand(0,99),0,rand(2,50),rand(2,50),$lime);
        imageline($this->captcha,rand(0,99),0,rand(2,50),rand(2,50),$red);
        $this->centeredImageString($this->captcha, rand(3,6), $this->generateKey(), $textCol);

    }
    private function createNewImage() {
        $img = imagecreate(100,24);
        $bgCol = imagecolorallocate($img, 204, 204, 255);
        $blobCol1 = imagecolorallocate($img, 102, 204, 51);
        $blobCol2 = imagecolorallocate($img, 180, 180, 180);
        $blobCol3 = imagecolorallocate($img, 255, 247, 135);
        $blobCol4 = imagecolorallocate($img, 201, 38, 0);
        imagefilledrectangle($img,0,0,100,24,$bgCol);
        $this->createBlobs($img,$blobCol1);
        $this->createBlobs($img,$blobCol2);
        $this->createBlobs($img,$blobCol3,10);
        $this->createBlobs($img,$blobCol4,10);
        return $img;
    }
    private function createBlobs($img,$color,$number=22) {
        $imgW = imagesx($img);
        $imgH = imagesy($img);
        for($i=0;$i<$number;$i++)
        {
            $xstart = rand(0,$imgW);
            $xstop = rand($xstart,$xstart);
            $ystart = rand(0,$imgH);
            $ystop = rand($ystart,$ystart);

            imagefilledrectangle($img,$xstart,$ystart,$xstop,$ystop,$color);
        }
    }
    private function centeredImageString($image, $font_size, $text, $text_color){
        $img_w = imagesx($image);
        $img_h = imagesy($image);
        imagestring($image, $font_size, round(($img_w/2)-((strlen($text)*imagefontwidth($font_size))/2), 1), round(($img_h/2)-(imagefontheight($font_size)/2)), $text, $text_color);
    }
    private function generateKey() {
        $hash = md5(microtime() * mktime());
        $key = substr($hash,0,$this->stringLength);
        $this->session->set('tmLibCaptchaKey',md5($key));
        return $key;
    }
}
?>