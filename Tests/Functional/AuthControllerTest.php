<?php
namespace Tests\Functional;

use App\Config\Database;
use App\Models\User;
use PDO;
use PHPUnit\Framework\TestCase;

class AuthControllerTest extends TestCase
{
    private PDO $pdo;

    /**
     * Cette méthode est appelée avant chaque test.
     * Idéal pour préparer la base de données.
     */
    protected function setUp(): void
    {
        $this->pdo = Database::getInstance();
        // On vide la table des utilisateurs et on la recrée pour un test propre
        $this->pdo->exec('TRUNCATE TABLE users');
    }

    public function testLoginWithValidCredentialsAuthenticatesUser(): void
    {
        // 1. Préparation : Insérer un utilisateur dans la BDD de test
        $user = new User($this->pdo);
        $user->setUsername('testuser')
             ->setEmail('test@example.com')
             ->setPassword('Password123')
             ->save();

        // 2. Action : Simuler une requête POST
        $_POST['email'] = 'test@example.com';
        $_POST['password'] = 'Password123';
        // Le token CSRF est géré par la session, on peut le simuler
        $_SESSION['csrf_token'] = 'fake_token';
        $_POST['csrf_token'] = 'fake_token';

        // Simuler le contrôleur et capturer la sortie pour éviter les `exit()` et `header()`
        ob_start();
        $controller = new \App\Controllers\AuthController();
        $controller->login();
        ob_end_clean(); // Nettoyer le buffer de sortie

        // 3. Assertion : Vérifier que l'utilisateur est bien connecté
        $this->assertNotNull($_SESSION['user_id']);
        $this->assertEquals($user->getId(), $_SESSION['user_id']);
    }

    public function testLoginWithInvalidCredentialsDoesNotAuthenticateUser(): void
    {
        // 1. Préparation (aucune, la BDD est vide)

        // 2. Action
        $_POST['email'] = 'wrong@example.com';
        $_POST['password'] = 'wrongpassword';
        $_SESSION['csrf_token'] = 'fake_token';
        $_POST['csrf_token'] = 'fake_token';

        ob_start();
        $controller = new \App\Controllers\AuthController();
        $controller->login();
        // Ici, le contrôleur rendrait une vue avec une erreur.
        // On capture cette sortie pour la vérifier.
        $output = ob_get_clean();

        // 3. Assertion
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        // On vérifie que le message d'erreur est bien présent dans la sortie HTML
        $this->assertStringContainsString('Email ou mot de passe incorrect', $output);
    }
}