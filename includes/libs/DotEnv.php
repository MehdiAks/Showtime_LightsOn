<?php
// Petite classe utilitaire pour charger un fichier .env et injecter les valeurs
// dans les superglobales PHP ($_ENV, $_SERVER) ainsi que dans l'environnement
// du processus via putenv(). L'objectif est de centraliser la configuration
// sans dépendance externe, tout en supportant la conversion simple des booléens.
class DotEnv{
    /**
     * Convert true and false to booleans, instead of:
     *
     * VARIABLE=false -> ['VARIABLE' => 'false']
     *
     * it will be
     *
     * VARIABLE=false -> ['VARIABLE' => false]
     *
     * default = true
     */
    // Option interne : si activée, la chaîne "true"/"false" est convertie en booléen.
    const PROCESS_BOOLEANS = 'PROCESS_BOOLEANS';

    /**
     * The directory where the .env file can be located.
     *
     * @var string
     */
    // Chemin complet vers le fichier .env à charger.
    protected $path;

    /**
     * Configure the options on which the parsed will act
     *
     * @var array
     */
    // Options de parsing (ex: conversion des booléens).
    protected $options = [];

    public function __construct(string $path, array $options = []){
        if(!file_exists($path)){
            // On lève une exception explicite si le fichier n'existe pas.
            throw new \InvalidArgumentException(sprintf('%s does not exist', $path));
        }

        // Stockage du chemin dans l'instance pour le chargement ultérieur.
        $this->path = $path;

        // Traitement des options par défaut et de celles passées par l'appelant.
        $this->processOptions($options);
    }

    private function processOptions(array $options) : void
    {
        // On fusionne les options passées avec les valeurs par défaut.
        // Les options passées par l'appelant ont la priorité.
        $this->options = array_merge([
            static::PROCESS_BOOLEANS => true
        ], $options);
    }

    /**
     * Processes the $path of the instances and parses the values into $_SERVER and $_ENV, also returns all the data that has been read.
     * Skips empty and commented lines.
     */
    public function load() : void
    {
        if(!is_readable($this->path)){
            // On empêche le chargement si le fichier n'est pas lisible.
            throw new \RuntimeException(sprintf('%s file is not readable', $this->path));
        }

        // Lecture ligne par ligne (sans retours à la ligne, sans lignes vides).
        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            // On ignore les commentaires (ligne qui commence par "#").
            if(strpos(trim($line), '#') === 0){
                continue;
            }

            // On découpe la ligne en "NOM=valeur". Le "2" limite à 2 parties
            // pour éviter de couper les valeurs contenant "=".
            list($ba_bec_name, $value) = explode('=', $line, 2);
            $ba_bec_name = trim($ba_bec_name);
            // Nettoyage/normalisation de la valeur (conversion booléenne éventuelle).
            $value = $this->processValue($value);

            // On n'écrase pas une variable déjà définie côté PHP/serveur.
            if(!array_key_exists($ba_bec_name, $_SERVER) && !array_key_exists($ba_bec_name, $_ENV)){
                // putenv() alimente l'environnement du processus (utile pour certains libs).
                putenv(sprintf('%s=%s', $ba_bec_name, $value));
                $_ENV[$ba_bec_name] = $value;
                $_SERVER[$ba_bec_name] = $value;
            }
        }
    }

    private function processValue(string $value){
        // Nettoie les espaces autour de la valeur brute lue dans le fichier.
        $trimmedValue = trim($value);

        if(!empty($this->options[static::PROCESS_BOOLEANS])){
            // Détection case-insensitive des chaînes "true"/"false".
            $loweredValue = strtolower($trimmedValue);

            $isBoolean = in_array($loweredValue, ['true', 'false'], true);

            if($isBoolean){
                // Conversion en booléen PHP réel si demandé.
                return $loweredValue === 'true';
            }
        }
        // Par défaut, on retourne la valeur telle quelle (string).
        return $trimmedValue;
    }
}

?>
