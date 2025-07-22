<?php

namespace App\Controllers;
use App\Models\Car;

/**
 * Gère la logique de la page d'accueil
 */
class HomeController extends BaseController{

    public function index():void{

        $carModel = new Car();

        $this->render('home/index',[
            'title'=>'Accueil - Garage php',
            'cars'=> $carModel->all()
        ]);
    }

}