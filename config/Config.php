<?php

namespace App\Config;
use Dotenv\Dotenv;

class Config{

    /*Class Config manuelle, cette classe sert a charger le fichier .env,
         à le lire et séparer et nettoyer les données 
    private static array $config = [];
    private static bool $loaded = false;

    public static function load(): void{

        if(self::$loaded) return;

        $envFile = __DIR__.'/../../.env';
        if(!file_exists($envFile)){
            throw new \Exception("Fichier .env manquant");
        }

        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach($lines as $line){
            if(strpos(trim($line), '#') === 0) continue;

            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim(trim($value), '"\'');

            self::$config[$key]= $value;
            $_ENV[$key]= $value;
            putenv("$key=$value");
        }
        self::validateConfig();
        self::$loaded = true;
    }

    public static function get(string $key, $default = null){
        if(!self::$loaded){
            self::load();
        }
        return self::$config[$key] ?? $default;
    }

    private static function validateConfig():void{


        $required = ['DB_HOST', 'DB_NAME', 'DB_USER', 'APP_KEY'];
        $missing = array_filter($required, fn($key) => empty(self::$config[$key]));

        if(!empty($missing)){
            throw new\Exception("Variables d'environnements manquantes :" . implode(', ',$missing));
        }
    }

    public static function isDebug():bool{
        return self::get('APP_DEBUG', 'false') === 'true';
    }*/

    /** 
     * @param string $path le chemin vers le dossier contenant le fichier .env
    */

    public static function load($path = __DIR__ . '../'):void{

        //on verifie si le fichier .env existe avant de tenter de le charger
        if(file_exists($path . '.env')){
            $dotenv = Dotenv::createImmutable($path);
            $dotenv->load();
        }
    }

    /**
     * @param string $key le nom de la variable 
     * @param mixed $default une valeur par défaut à retourner si la variable n'existe pas
     * @return mixed la valeur de la variable ou la valeur par defaut
     */

     public static function get(string $key, $default = null){
        return $_ENV[$key] ?? $default;
     }

}