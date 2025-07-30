<?php
namespace App\Utils;
use App\Config\Config;

/**
 * Gère l'écriture de logs dans des fichiers.
 * Logger est essentiel pour le débogage et la surveillance de l'application en production.
 */

class Logger{

        /**
     * Écrit un message dans le fichier de log.
     *
     * @param string $level Le niveau de criticité du log (ex: 'INFO', 'WARNING', 'ERROR').
     * @param string $message Le message à logger.
     */
    public function log(string $level, string $message): void
    {
        // On récupère le chemin du dossier de stockage depuis la configuration.
        $storagePath = Config::get('STORAGE_PATH');
        if (!$storagePath) return; // Si le chemin n'est pas configuré, on ne fait rien.

        $logFilePath = $storagePath . '/logs/app.log';
        // On formate l'entrée du log avec la date, le niveau et le message.
        $entry = sprintf("[%s] %s: %s" . PHP_EOL, date('Y-m-d H:i:s'), strtoupper($level), $message);

        // `file_put_contents` écrit dans le fichier.
        // `FILE_APPEND` ajoute le contenu à la fin du fichier au lieu de l'écraser.
        file_put_contents($logFilePath, $entry, FILE_APPEND);
    }
}