<?php

declare(strict_types=1);

namespace Service;

use Service\Exception\SessionException;

class Session
{
    /**
     * @throws SessionException
     */
    public static function start(): void
    {
        $status = session_status();

        if ($status === PHP_SESSION_ACTIVE) {
            return;
        } elseif ($status === PHP_SESSION_NONE) {
            // Session non démarée

            if (headers_sent()) {
                throw new SessionException("Impossible de modifier les entêtes HTTP.");
            }
            session_set_cookie_params(3600);
            session_start();
        } elseif ($status === PHP_SESSION_DISABLED) {
            // Session désactivée

            throw new SessionException("Session désactivée.");
        } else {
            throw new SessionException("Erreur inconnue.");
        }
    }
}
