<?php
namespace App\Controllers;
use App\Security\Validator;
use App\Utils\Response;

/**
 * Controleur de base
 * Toutes les autres classe de controlleur hériteront de celle ci
 * 
 */
abstract class BaseController{

    protected Response $response;
    protected Validator $validator;

    public function __construct(){
        $this->response = new Response();
        $this->validator = new Validator();
    }


    /**
     * Affiche une vue en l'injectant dans le layout principale
     * @param string $view le nom du fichier de vue
     * @param array $data les données à rendre accessible dans la vue
     * 
     */
    protected function render(string $view, array $data=[]):void{

        //On construit le chemin complet vers le fichier de vue
        $viewPath = dirname(__DIR__, 2) . '/views/' .$view .'.php';

        //On vérifie que le fichier vue existe bien
        if(!file_exists($viewPath)){
            $this->response->error("Vue non trouvée : $viewPath", 500);
            return; 
        }

        //Extract transforme les clés d'un tableau en variables
        //Ex: $data = ['title' => 'Accueil'] devient $title = 'Accueil'
        extract($data);

         // On utilise la mise en tampon de sortie (output buffering) pour capturer le HTML de la vue.
        ob_start();
        include $viewPath;

        //Ici on vide le cache la variable $content contient la vue
        $content = ob_get_clean();

         // Finalement, on inclut le layout principal, qui peut maintenant utiliser la variable $content.
        include dirname(__DIR__, 2).'/views/layout.php';
    }

    /**
     * Récupère et nettoie les données envoyées via une requete POST
     */
    protected function getPostData():array{
        
        return $this->validator->sanitize($_POST);
    }

    /**
     * Vérifie si l'utilisateur est connecter sinon le redirige vers la page login
     */
    protected function requireAuth(): void{

        if(!isset($_SESSION['user_id'])){
            $this->response->redirect('/login');
        }
    }
}