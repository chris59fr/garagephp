<?php
namespace App\Controllers;
use Models\Car;
use Security\TokenManager;

class CarController extends BaseController
{
    private Car $carModel;
    private TokenManager $tokenManager;

    public function __construct()
    {
        parent::__construct();
        $this->carModel = new Car();
        $this->tokenManager = new TokenManager();
    }

    public function index(): void
    {
        $this->requireAuth();
        $cars = $this->carModel->all();
        $this->render('cars/index', ['title' => 'Gestion des voitures', 'cars' => $cars]);
    }
    
    public function show(int $id): void
    {
        $this->requireAuth();
        $car = (new Car())->find($id);
        if (!$car) $this->response->error("Voiture non trouvée", 404);
        $this->render('cars/show', ['title' => 'Détails', 'car' => $car]);
    }
    
    public function create(): void
    {
        $this->requireAuth();
        $this->render('cars/create', ['title' => 'Ajouter une voiture', 'csrf_token' => $this->tokenManager->generateCsrfToken()]);
    }

    public function store(): void
    {
        $this->requireAuth();
        $data = $this->getPostData();
        if (!$this->tokenManager->validateCsrfToken($data['csrf_token'] ?? '')) {
            $this->response->error('Token de sécurité invalide', 403);
        }

        $errors = $this->validator->validate($data, [
            'brand' => 'required|max:100',
            'model' => 'required|max:100',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'required|max:50',
            'plate_number' => 'required|max:20|alpha_num_dash',
            'price' => 'required|numeric|min:0'
        ]);

        if (!empty($errors)) {
            $this->render('cars/create', ['errors' => $errors, 'old' => $data, 'csrf_token' => $this->tokenManager->generateCsrfToken()]);
            return;
        }
        
        try {
            $car = new Car();
            $car->setBrand($data['brand'])->setModel($data['model'])->setYear((int)$data['year'])
                ->setColor($data['color'])->setPlateNumber($data['plate_number'])->setPrice((float)$data['price'])
                ->setStatus($data['status'] ?? Car::STATUS_AVAILABLE);
            
            if ($car->save()) {
                $this->response->redirect('/cars');
            } else {
                throw new \Exception("Erreur lors de la sauvegarde.");
            }
        } catch (\Exception $e) {
            $this->render('cars/create', ['error' => $e->getMessage(), 'old' => $data, 'csrf_token' => $this->tokenManager->generateCsrfToken()]);
        }
    }
    
    public function edit(int $id): void
    {
        $this->requireAuth();
        $car = (new Car())->find($id);
        if (!$car) $this->response->error("Voiture non trouvée", 404);
        
        $this->render('cars/edit', [
            'title' => 'Modifier la voiture',
            'car' => $car,
            'csrf_token' => $this->tokenManager->generateCsrfToken()
        ]);
    }

    public function update(int $id): void
    {
        $this->requireAuth();
        $data = $this->getPostData();
        $car = (new Car())->find($id);
        if (!$car) $this->response->error("Voiture non trouvée", 404);
        
        if (!$this->tokenManager->validateCsrfToken($data['csrf_token'] ?? '')) {
            $this->response->error('Token de sécurité invalide', 403);
        }

        // Mêmes règles de validation que pour store()
        $errors = $this->validator->validate($data, [
            'brand' => 'required|max:100', 'model' => 'required|max:100', 'year' => 'required|integer',
            'color' => 'required|max:50', 'plate_number' => 'required|max:20', 'price' => 'required|numeric'
        ]);

        if (!empty($errors)) {
            $this->render('cars/edit', ['errors' => $errors, 'car' => $car, 'csrf_token' => $this->tokenManager->generateCsrfToken()]);
            return;
        }

        try {
            $car->setBrand($data['brand'])->setModel($data['model'])->setYear((int)$data['year'])
                ->setColor($data['color'])->setPlateNumber($data['plate_number'])->setPrice((float)$data['price'])
                ->setStatus($data['status'] ?? Car::STATUS_AVAILABLE);
            
            if ($car->save()) {
                $this->response->redirect('/cars');
            } else {
                throw new \Exception("Erreur lors de la mise à jour.");
            }
        } catch (\Exception $e) {
            $this->render('cars/edit', ['error' => $e->getMessage(), 'car' => $car, 'csrf_token' => $this->tokenManager->generateCsrfToken()]);
        }
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        $car = (new Car())->find($id);
        if ($car && $car->delete()) {
             $this->response->redirect('/cars');
        } else {
            $this->response->error('Erreur lors de la suppression', 500);
        }
    }
    
    public function apiAvailable(): void
    {
        $cars = (new Car())->findAvailable();
        $data = array_map(fn($car) => $car->toArray(), $cars);
        $this->response->json(['success' => true, 'data' => $data, 'count' => count($data)]);
    }
}
