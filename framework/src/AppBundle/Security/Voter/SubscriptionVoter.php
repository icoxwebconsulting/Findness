<?php

namespace AppBundle\Security\Voter;

use AppBundle\Entity\Customer;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class SubscriptionVoter
 *
 * @package AppBundle\Security\Voter
 */
class SubscriptionVoter extends Voter
{
    // these strings are just invented: you can use anything
    const ACTIVE = 'active';

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * SubscriptionVoter constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @return bool
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::ACTIVE))) {
            return false;
        }

        // only vote on Post objects inside this voter
        if (!$subject instanceof Customer) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     * @return bool|void
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof Customer) {
            // the user must be logged in; if not, deny access
            return false;
        }

        /** @var Customer $customer */
        $customer = $subject;

        if ($user !== $customer) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::ACTIVE:
                return $this->isActive($customer);
        }

        throw new \LogicException('This code should not be reached!');
    }

    /**
     * @param Customer $customer
     * @return bool
     */
    private function isActive(Customer $customer)
    {
        $subscription = $this->em->getRepository('AppBundle:Subscription')->findByCustomer($customer);

        return $subscription !== null;
    }
}