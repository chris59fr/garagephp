<?php
// tests/bootstrap.php

// Charger l'autoloader de Composer
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Config;

// Charger un fichier .env spécifique aux tests pour utiliser une base de données séparée
// Cela évite de polluer votre base de données de développement.
Config::load(dirname(__DIR__), '.env.testing');