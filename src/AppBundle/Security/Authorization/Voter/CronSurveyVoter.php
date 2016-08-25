<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Model\CronSurveyInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class CronSurveyVoter implements VoterInterface
{
    const EDIT = 'edit';
    const VIEW = 'view';

    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::EDIT,
            self::VIEW
        ));
    }

    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Model\CronSurveyInterface';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    public function vote(TokenInterface $token, $requestedCronSurvey, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($requestedCronSurvey))) {
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
        if ($attribute === self::VIEW) {
            return VoterInterface::ACCESS_GRANTED;
        }
        if ($attribute === self::EDIT && ( $loggedInUser->hasRole('ROLE_SUPER_ADMIN') || $loggedInUser->hasRole('ROLE_ADMIN') )){
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}