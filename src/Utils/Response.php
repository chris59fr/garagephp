<?php
namespace App\Utils;

/**
 * Classe Response : fournit des méthodes standard pour envoyer des réponses HTTP.
 * Centraliser ces actions (redirection, erreur) rend le code des contrôleurs plus propre.
 */

class Response{

    /**
     * Effectue une redirection HTTP vers une autre URL.
     *
     * @param string $url L'URL de destination.
     */
    public function redirect(string $url): void
    {
        // `header()` envoie un en-tête HTTP brut. 'Location' déclenche la redirection.
        header("Location: " . $url);
        // `exit()` est crucial après une redirection pour s'assurer que le script s'arrête
        // et qu'aucun autre code n'est exécuté.
        exit();
    }

    /**
     * Affiche une page d'erreur standard et arrête le script.
     *
     * @param string $message Le message d'erreur à afficher.
     * @param int $code Le code de statut HTTP (ex: 404 pour non trouvé, 500 pour erreur serveur).
     */
    public function error(string $message, int $code): void
    {
        // `http_response_code()` définit le code de statut de la réponse HTTP.
        http_response_code($code);
        // `die()` affiche le message et termine immédiatement l'exécution du script.
        die("Erreur {$code}: " . htmlspecialchars($message));
    }

}