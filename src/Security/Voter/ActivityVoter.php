<?php

namespace App\Security\Voter;

use App\Entity\Activity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ActivityVoter extends Voter
{

    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['LOCKED'])
            && $subject instanceof Activity;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Activity $subject */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'LOCKED':

                // The activity isn't locked
                if ($subject->getStatus() == 0) {
                    return true;
                }

                // This is user is person who initiated the lock
                if ($subject->getStatus() == 1 && $subject->getModifiedUser() == $user) {
                    return true;
                }

                // SysAdmin can override
                if ($subject->getStatus() == 1 && $this->security->isGranted('ROLE_ADMIN')){
                    return true;
                }

                break;
        }

        return false;
    }
}
