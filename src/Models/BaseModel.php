<?php

namespace App\Models;
use App\Config\Database;
use PDO;

abstract class BaseModel{
    
    /**
     * @var PDO l'instance de connexion à la base de données
     */
    protected PDO $db;

    /**
     * 
     * @var string le nom de la table associé au model
     */
    protected string $table;


    public function __construct(?PDO $db = null){
        $this->db = $db ?? Database::getInstance();
    }
}