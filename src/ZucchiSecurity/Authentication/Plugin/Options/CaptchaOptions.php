<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Authentication\Plugin\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * default options for captcha 
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage Auth 
 * @category Plugin/Options
 */
class CaptchaOptions extends AbstractOptions 
{
    protected $enabled = false;
    
    protected $adapter = 'dumb';
    
    protected $figletWidth = 40;
    
    protected $imageFont = '';
    
    protected $imageFontSize = '24';
    
    protected $imageHeight = '50';
    
    protected $imageWidth = '200';
    
    protected $imageDir = './public/images/captcha/';
    
    protected $imageUrl = '/images/captcha/';

    protected $imageDotNoise = 0;
    
    protected $imageLineNoise = 0;
    
    protected $recaptchaPrivateKey;
    
    protected $recaptchaPublicKey;
    
	/**
     * @return the $enabled
     */
    public function getEnabled ()
    {
        return $this->enabled;
    }

	/**
     * @param boolean $enabled
     */
    public function setEnabled ($enabled)
    {
        $this->enabled = $enabled;
    }

	/**
     * @return the $adapter
     */
    public function getAdapter ()
    {
        return $this->adapter;
    }

    
    
	/**
     * @param string $adapter
     */
    public function setAdapter ($adapter)
    {
        $this->adapter = $adapter;
    }
    
	/**
     * @return the $figletWidth
     */
    public function getFigletWidth ()
    {
        return $this->figletWidth;
    }

	/**
     * @param number $figletWidth
     */
    public function setFigletWidth ($figletWidth)
    {
        $this->figletWidth = $figletWidth;
    }

	/**
     * @return the $imageFont
     */
    public function getImageFont ()
    {
        return $this->imageFont;
    }

	/**
     * @param string $imageFont
     */
    public function setImageFont ($imageFont)
    {
        $this->imageFont = $imageFont;
    }

	/**
     * @return the $imageFontSize
     */
    public function getImageFontSize ()
    {
        return $this->imageFontSize;
    }

	/**
     * @return the $imageHeight
     */
    public function getImageHeight ()
    {
        return $this->imageHeight;
    }

	/**
     * @return the $imageWidth
     */
    public function getImageWidth ()
    {
        return $this->imageWidth;
    }

	/**
     * @return the $imageUrl
     */
    public function getImageUrl ()
    {
        return $this->imageUrl;
    }

	/**
     * @param string $imageFontSize
     */
    public function setImageFontSize ($imageFontSize)
    {
        $this->imageFontSize = $imageFontSize;
    }

	/**
     * @param string $imageHeight
     */
    public function setImageHeight ($imageHeight)
    {
        $this->imageHeight = $imageHeight;
    }

	/**
     * @param string $imageWidth
     */
    public function setImageWidth ($imageWidth)
    {
        $this->imageWidth = $imageWidth;
    }

	/**
     * @param string $imageUrl
     */
    public function setImageUrl ($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

	/**
     * @return the $imageDir
     */
    public function getImageDir ()
    {
        return $this->imageDir;
    }

	/**
     * @param NULL $imageDir
     */
    public function setImageDir ($imageDir)
    {
        $this->imageDir = $imageDir;
    }

	/**
     * @return the $imageDotNoise
     */
    public function getImageDotNoise ()
    {
        return $this->imageDotNoise;
    }

	/**
     * @param number $imageDotNoise
     */
    public function setImageDotNoise ($imageDotNoise)
    {
        $this->imageDotNoise = $imageDotNoise;
    }

	/**
     * @return the $imageLineNoise
     */
    public function getImageLineNoise ()
    {
        return $this->imageLineNoise;
    }

	/**
     * @param number $imageLineNoise
     */
    public function setImageLineNoise ($imageLineNoise)
    {
        $this->imageLineNoise = $imageLineNoise;
    }

	/**
     * @return the $recaptchaPrivateKey
     */
    public function getRecaptchaPrivateKey ()
    {
        return $this->recaptchaPrivateKey;
    }

	/**
     * @param field_type $recaptchaPrivateKey
     */
    public function setRecaptchaPrivateKey ($recaptchaPrivateKey)
    {
        $this->recaptchaPrivateKey = $recaptchaPrivateKey;
    }

	/**
     * @return the $recaptchaPublicKey
     */
    public function getRecaptchaPublicKey ()
    {
        return $this->recaptchaPublicKey;
    }

	/**
     * @param field_type $recaptchaPublicKey
     */
    public function setRecaptchaPublicKey ($recaptchaPublicKey)
    {
        $this->recaptchaPublicKey = $recaptchaPublicKey;
    }


    
}