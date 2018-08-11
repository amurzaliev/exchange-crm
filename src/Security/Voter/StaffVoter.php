<?php

namespace App\Security\Voter;

use App\Entity\Staff;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class StaffVoter extends Voter
{
    const VIEW = 'VIEW';
    const EDIT = 'EDIT';

    /**
     * @var AccessDecisionManagerInterface
     */
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {

        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::EDIT])
            && $subject instanceof Staff;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Staff $staff */
        $staff = $subject;

        if ($this->decisionManager->decide($token, ['ROLE_ADMIN'])) {
            return true;
        }

        if ($this->decisionManager->decide($token, ['ROLE_OWNER'])) {
            return true;
        }

        if ($staff->getUser() === $user) {
            return true;
        }

        if ($this->decisionManager->decide($token, ['ROLE_USER'])) {
            switch ($attribute) {
                case self::VIEW:
                    return $this->canView($staff, $user);
                case self::EDIT:
                    return false;
            }
        }

        return false;
    }

    private function canView(staff $staff, User $user)
    {
        if ($user->getStaff() === $staff) {
            return true;
        }

        return false;
    }
}
