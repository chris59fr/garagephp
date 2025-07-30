<?php
namespace App\Security;

/**
 * Fichier qui fournit des méthodes pour valider et nettoyer les données
 */

class Validator{


    private array $errors = [];


    private array $data;


    public function validate(array $data, array $rules): array{

        $this->errors = [];
        $this->data = $data;

        foreach($rules as $field =>$rulesString){
            $value = $data[$field] ?? null;
            $rulesArray = explode('|', $rulesString);

            foreach($rulesArray as $rule){
                $this->applyRule($field, $value, $rule);
            }
        }
        return $this->errors;
    }

    private function applyRule(string $field, $value, string $rule):void{

        $param = null;
        if(strpos($rule, ':') !== false){
            [$rule, $param] = explode(':', $rule, 2);
        }

        switch($rule){
            case 'required':
                if(empty($value)){
                    $this->addError($field, " le champ {$field} est requis.");
                }
                break;

            case 'email':
                if(!filter_var($value, FILTER_VALIDATE_EMAIL)){
                    $this->addError($field, " le champ {$field} doit être une adresse email valide.");
                }
                break;

            case 'min':
                if(strlen($value) < (int) $param){
                    $this->addError($field, "Le champ {$field} doit contenir au moins {$param} caractères.");
                }
                break;

            case 'max':
                if (strlen($value) > (int) $param) {
                    $this->addError($field, "Le champ {$field} ne peut pas dépasser {$param} caractères.");
                }
                break;

            case 'same':
                if ($value !== ($this->data[$param] ?? null)) {
                    $this->addError($field, "Le champ {$field} doit être identique au champ {$param}.");
                }
                break;
        }
    }

    private function addError(string $field, string $message):void{

        $this->errors[$field][] = $message;

    }

    public function sanitize(array $data):array{

        $sanitized = [];
        foreach($data as $key => $value){
            $sanitized[$key] = is_string($value) ? htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8'): $value;
        }
        return $sanitized;
    }



}