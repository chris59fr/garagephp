<?php
namespace App\Models;

use PDO;
use InvalidArgumentException;

/**
 * Modèle Car : représente une voiture dans la base de données.
 *
 * Cette classe contient toutes les propriétés d'une voiture (marque, modèle, etc.)
 * ainsi que la logique pour la sauvegarder, la modifier et la récupérer
 * depuis la base de données.
 */
class Car extends BaseModel
{
    /**
     * @var string Le nom de la table en base de données.
     */
    protected string $table = 'cars';

    /**
     * @var array Propriétés privées représentant les colonnes de la table `cars`.
     * L'encapsulation (via `private`) garantit que les données sont protégées et
     * ne peuvent être modifiées que par les méthodes de cette classe (setters).
     */
    private ?int $id = null;
    private string $brand;
    private string $model;
    private int $year;
    private string $color;
    private string $plateNumber;
    private float $price;
    private string $status;

    // --- GETTERS ---
    // Les getters sont des méthodes publiques qui permettent de LIRE la valeur
    // des propriétés privées depuis l'extérieur de la classe.

    public function getId(): ?int { return $this->id; }
    public function getBrand(): string { return $this->brand; }
    public function getModel(): string { return $this->model; }
    public function getYear(): int { return $this->year; }
    public function getColor(): string { return $this->color; }
    public function getPlateNumber(): string { return $this->plateNumber; }
    public function getPrice(): float { return $this->price; }
    public function getStatus(): string { return $this->status; }
    public function getFormattedPrice(): string { return number_format($this->price, 2, ',', ' ') . ' €'; }

    // --- SETTERS ---
    // Les setters sont des méthodes publiques qui permettent de MODIFIER la valeur
    // des propriétés privées. C'est ici que l'on place la logique de validation
    // pour s'assurer que les données sont toujours valides.

    public function setBrand(string $brand): self
    {
        $this->brand = trim($brand);
        return $this; // Retourner `$this` permet de chaîner les méthodes.
    }

    public function setModel(string $model): self
    {
        $this->model = trim($model);
        return $this;
    }

    public function setYear(int $year): self
    {
        if ($year < 1900 || $year > (int)date('Y') + 1) {
            throw new InvalidArgumentException("L'année fournie est invalide.");
        }
        $this->year = $year;
        return $this;
    }

    public function setColor(string $color): self
    {
        $this->color = trim($color);
        return $this;
    }

    public function setPlateNumber(string $plateNumber): self
    {
        // On normalise la plaque en la passant en majuscules.
        $this->plateNumber = strtoupper(trim($plateNumber));
        return $this;
    }

    public function setPrice(float $price): self
    {
        if ($price < 0) {
            throw new InvalidArgumentException("Le prix ne peut pas être négatif.");
        }
        $this->price = $price;
        return $this;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, ['disponible', 'vendu'])) {
            throw new InvalidArgumentException("Le statut de la voiture est invalide.");
        }
        $this->status = $status;
        return $this;
    }

    /**
     * Récupère toutes les voitures de la base de données.
     * @return array Un tableau de voitures, triées par date de création.
     */
    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * Trouve une voiture par son ID.
     * @return array|null Les données de la voiture ou null si non trouvée.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ?: null;
    }

    /**
     * Sauvegarde la voiture en base de données (création ou mise à jour).
     * @return bool `true` en cas de succès, `false` sinon.
     */
    public function save(): bool
    {
        // Logique pour créer une nouvelle voiture
        if ($this->id === null) {
            $sql = "INSERT INTO cars (brand, model, year, color, plate_number, price, status) 
                    VALUES (:brand, :model, :year, :color, :plate_number, :price, :status)";
        } else {
        // Logique pour mettre à jour une voiture existante
            $sql = "UPDATE cars SET brand = :brand, model = :model, year = :year, color = :color, 
                    plate_number = :plate_number, price = :price, status = :status 
                    WHERE id = :id";
        }

        $stmt = $this->db->prepare($sql);

        // On prépare le tableau de paramètres à lier à la requête SQL
        $params = [
            ':brand' => $this->brand,
            ':model' => $this->model,
            ':year' => $this->year,
            ':color' => $this->color,
            ':plate_number' => $this->plateNumber,
            ':price' => $this->price,
            ':status' => $this->status ?? 'disponible',
        ];

        // Si c'est une mise à jour, on ajoute l'ID aux paramètres
        if ($this->id !== null) {
            $params[':id'] = $this->id;
        }

        $result = $stmt->execute($params);

        // Si c'est une création réussie, on met à jour l'ID de l'objet
        if ($this->id === null && $result) {
            $this->id = (int)$this->db->lastInsertId();
        }

        return $result;
    }
}