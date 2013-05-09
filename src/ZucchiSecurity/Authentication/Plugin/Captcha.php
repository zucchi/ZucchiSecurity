<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Authentication\Plugin;

use Zend\EventManager\EventInterface;
use Zend\Form\Element;
use Zend\Captcha as ZendCaptcha;
use Zend\Service\ReCaptcha\Recaptcha;
use ZucchiSecurity\Exception;

/**
 * Service to handle authentication via local entity
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage Auth  
 * @category Plugin
 */
class Captcha extends AbstractPlugin implements PluginInterface
{
    /**
     * Add fields to form for Authentication
     * 
     * @param EventInterface $event
     */
    static public function extendLoginForm(EventInterface $event)
    {
        $form = $event->getTarget();
        $sm = $event->getServiceManager();

        $options = $sm->get('zucchisecurity.auth.captcha.options');
        
        if ($options->getEnabled()) {
            $captcha = new Element\Captcha('captcha');
            $captcha->setLabel('Please verify you are human');
            
            switch (strtolower($options->getAdapter())) {
                case 'recaptcha':
                    $privKey = $options->getRecaptchaPrivateKey();
                    $pubKey = $options->getRecaptchaPublicKey();
                    if (!$privKey || !$pubKey) {
                        throw new Exception\BadConfigException('Recaptcha keys incorrectly configured');
                    }
                    $recaptcha = new ZendCaptcha\ReCaptcha();
                    $recaptcha->setPrivkey($privkey)
                              ->setPubkey($pubkey)
                              ->setService(new ZendService\ReCaptcha\ReCaptcha());
                    $captcha->setCaptcha($recaptcha);
                    break;
                case 'image':
                    $image = new ZendCaptcha\Image();
                    $image->setFont($options->getImageFont())
                          ->setFontSize($options->getImageFontSize())
                          ->setHeight($options->getImageHeight())
                          ->setWidth($options->getImageWidth())
                          ->setImgDir($options->getImageDir())
                          ->setImgUrl($options->getImageUrl())
                          ->setDotNoiseLevel($options->getImageDotNoise())
                          ->setLineNoiseLevel($options->getImageLineNoise());
                    $captcha->setCaptcha($image);
                    break;
                case 'figlet':
                    $figlet = new ZendCaptcha\Figlet(array(
                        'outputWidth' => $options->getFigletWidth(),
                    ));
                    $captcha->setCaptcha($figlet);
                    break;
                case 'dumb':
                default:
                    $captcha->setCaptcha(new ZendCaptcha\Dumb());
                    break;
            }
            
            $form->add($captcha, array('priority' => -9998));
        }
    } 
    
    static public function extendLogoutForm(EventInterface $event)
    {
        // dont do anything
    }
}