<?php

namespace AppBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class AnswerResultVoter implements VoterInterface
{
    const EDIT = 'edit';
    const VIEW = 'view';
    const ADMIN = 'admin';

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::EDIT,
            self::VIEW,
            self::ADMIN
        ));
    }

    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Model\AnswerResultInterface';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    public function vote(TokenInterface $token, $requestedAnswerResult, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($requestedAnswerResult))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // get current logged in user
        $loggedInUser = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)

        if ($attribute !== self::ADMIN) {
            return VoterInterface::ACCESS_GRANTED;
        }
        if ($loggedInUser->hasRole('ROLE_SUPER_ADMIN')){
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}