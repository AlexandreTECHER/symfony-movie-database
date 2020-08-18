<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['MOVIE_DELETE', 'MOVIE_ADD'])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'MOVIE_DELETE':
                // Ici on pourrait mettre des instructions qui serviraient pour MOVIE_DELETE seulement
                // Mais comme y'a pas de break;, les vérif dans MOVIE_ADD s'exécuteraient aussi pour MOVIE_DELETE
            case 'MOVIE_ADD':
                if ($user->getEmail() == 'lucie@o.o') {
                    return true;
                }
                // if (in_array('ROLE_ADMIN', $user->getRoles())) {
                //     return true;
                // }
                break;
        }

        return false;
    }
}
