<?php
/**
 * ZucchiSecurity (http://zucchi.co.uk/)
 *
 * @link      http://github.com/zucchi/ZucchiSecurity for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zucchi Limited (http://zucchi.co.uk)
 * @license   http://zucchi.co.uk/legals/bsd-license New BSD License
 */
namespace ZucchiSecurity\Authentication\Plugin;

use Zend\Crypt\Password\Bcrypt;
use Zend\EventManager\EventInterface;

use Doctrine\ORM\EntityManager;

use ZucchiSecurity\Entity\AuthenticatableInterface;
use ZucchiSecurity\Form\Login as LoginForm;
use ZucchiSecurity\Authentication\Result;
use ZucchiSecurity\Event\SecurityEvent;

use Zucchi\Debug\Debug;


/**
 * Service to handle authentication via local entity
 * 
 * @author Matt Cockayne <matt@zucchi.co.uk>
 * @package ZucchiSecurity 
 * @subpackage Auth  
 * @category Plugin
 */
class Local extends AbstractPlugin implements PluginInterface
{
    /**
     * @var EntityManager
     */
    protected $entityManager;
    
    /**
     * Authenticate the request & form
     * @param unknown_type $request
     * @param LoginForm $form
     * @return Result
     */
    public function authenticate($request, LoginForm $form = null)
    { 
        $result = new Result();
        $result->identity = $identity = $form->get('identity')->getValue();
        
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $query = $this->buildQuery($identity);
                $resultSet = $query->getResult();
                
                if (count($resultSet) == 1) { // single entity found 
                    $entity = $resultSet[0];
                    $credential = $form->get('credential')->getValue();
                    if ($entity instanceof AuthenticatableInterface) {
                        if ($this->options->allowLocked || !$entity->isLocked()) {
                            if ($entity->verifyCredential($credential)) {
                                $result->code = Result::SUCCESS;
                                $result->entity = $entity;                            
                            } else {
                                $result->code = Result::FAILURE_CREDENTIAL_INVALID;
                            }
                        } else {
                            $result->code = Result::FAILURE_PROHIBITED;
                        }
                    } else {
                        $result->code = Result::FAILURE_NOT_AUTHENTICATABLE;
                    }
                    
                } else if (count($resultSet) > 1) { // more than one entity found
                    $result->code = Result::FAILURE_IDENTITY_AMBIGUOUS;
                    
                } else { // no entity found
                    $result->code = Result::FAILURE_IDENTITY_NOT_FOUND;
                }
            }
        }
        
        // trigger the authenticate event
        $em = $this->getServiceManager()->get('eventmanager');
        $securityEvent = new SecurityEvent();
        $securityEvent->setName(SecurityEvent::EVENT_AUTHENTICATE);
        $securityEvent->setTarget($result);
        $securityEvent->setParam('plugin', $this);
        $em->trigger($securityEvent);
        
        return $result;
    }
    
    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        if (!$this->entityManager) {
            $this->entityManager = $this->getServiceManager()->get('doctrine.entitymanager.orm_default');
        }
        return $this->entityManager;
    }

    /**
     * build a Doctrine Query required to authenticate
     *  
     * @param string $identity
     * @param string $credential
     * @return \Doctrine\ORM\Query
     */
    protected function buildQuery($identity)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $metadata = $this->entityManager->getClassMetadata($this->options->entity);
        
        $qb->select('e');
        
        $qb->from($this->options->entity, 'e');
        
        $identities = $qb->expr()->orX();
        foreach ($this->options->identityFields as $field) {
            $identities->add('e.' . $field . ' = :identity');
        }
        $qb->where($identities);
        
        if (!$this->options->allowLocked && $metadata->hasField('locked')) {
            $qb->andWhere('e.locked = 0');
        }
        
        $qb->setParameter('identity', $identity);
        
        return $qb->getQuery();
    }
    
    /**
     * Add fields to form for local Authentication
     * 
     * @param EventInterface $event
     */
    static public function extendLoginForm(EventInterface $event)
    {
        $form = $event->getTarget();

        $form->add(array(
            'name'  => 'identity',
            'type' => 'Zend\Form\Element\Text',
            
            'attributes' => array(
                'required' => true,
                'type' => 'text',
                'autofocus' => true
            ),
            'options' => array(
                'label' => _('Identity'),
                'bootstrap' => array( // options for bootstrap form
                    'help' => array(
                        'style' => 'block',
                        'content' => _('Your identity to log in with'),
                    ),
                )
            ),
        ), array('priority' => 100));

        $form->add(array(
            'name'  => 'credential',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'required' => true,
                'type' => 'password',
            ),
            'options' => array(
                'label' => _('Credential'),
                'bootstrap' => array( // options for bootstrap form
                    'help' => array(
                        'style' => 'block',
                        'content' => _('The credential you need to log in with'),
                    ),
                )
            ),
        ), array('priority' => 99));
        
        $form->get('actions')->add(array(
            'name' => 'login',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Login',
                'class' => 'btn btn-primary'
            ),
            'options' => array(
                'bootstrap' => array(
                    'style' => 'inline',
                ),
            ),
        ));
        
    } 
    
    static public function extendLogoutForm(EventInterface $event)
    {
        $form = $event->getTarget();
        
        $form->add(array(
            'name' => 'logout',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Logout',
                'class' => 'btn btn-primary'
            ),
            'options' => array(
                'bootstrap' => array(
                    'style' => 'inline',
                ),
            ),
        ));
    }
}