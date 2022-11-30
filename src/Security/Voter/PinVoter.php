<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PinVoter extends Voter
{
    // public const EDIT = 'POST_EDIT';
    // public const VIEW = 'POST_VIEW';
    public const MANAGE = 'PIN_MANAGE';

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        // return in_array($attribute, [self::EDIT, self::VIEW])
        return in_array($attribute, [self::MANAGE])

            && $subject instanceof \App\Entity\Pin;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::MANAGE:
                // logic to determine if the user can EDIT - je rajoute mes conditions
                return $user->isVerified() && $user == $subject->getUser();
                // return true or false
                //le break est inutile si j'ajoute un return
                //break;
            //case self::DELETE:
                // logic to determine if the user can DELETE
                //return $user->isVerified() && $user == $subject->getUser();
                // return true or false
                //break;
        }

        return false;
    }
}
