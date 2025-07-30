<?php
namespace App\Security;

/**
 * Gère les jetons (tokens) de sécurité, notamment les jetons CSRF.
 * CSRF (Cross-Site Request Forgery) est une attaque où un utilisateur malveillant
 * force un utilisateur authentifié à exécuter une action non désirée.
 * Le jeton CSRF est une valeur unique et secrète qui protège contre cela.
 */

class TokenManager{

     /**
     * Génère un jeton CSRF ou récupère celui qui existe déjà dans la session.
     * Le jeton est stocké en session pour pouvoir le comparer plus tard.
     *
     * @return string Le jeton de sécurité.
     */
    public function generateCsrfToken(): string
    {
        // Si aucun jeton n'existe en session, on en crée un.
        if (empty($_SESSION['csrf_token'])) {
            // `random_bytes()` génère une chaîne de caractères aléatoires cryptographiquement sûre.
            // `bin2hex()` la convertit en une chaîne hexadécimale lisible.
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

        /**
     * Valide un jeton CSRF soumis par un formulaire.
     *
     * @param string $token Le jeton reçu du formulaire.
     * @return bool `true` si le jeton est valide, `false` sinon.
     */
    public function validateCsrfToken(string $token): bool
    {
        // On vérifie que le jeton du formulaire n'est pas vide, que le jeton en session existe,
        // et que les deux sont identiques.
        // `hash_equals()` est utilisée pour comparer les jetons. C'est une fonction qui
        // prend un temps constant, ce qui la protège contre les attaques par analyse temporelle ("timing attacks").
        return !empty($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

}