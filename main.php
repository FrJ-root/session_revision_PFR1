<?php

interface ReservableInterface {
    public function reserver(Client $client, DateTime $dateDebut, int $nbJours): Reservation;
}

abstract class Vehicule {
    protected int $id;
    protected string $marque;
    protected string $modele;
    protected float $prixJour;
    protected bool $disponible;
    protected string $immatriculation;

    abstract public function getType();

    public function afficherDetails(){
        return "ID: ".$this->id . ", Marque: ".$this->marque.", Modele: ".$this->modele.", Prix/jour: ".$this->prixJour;
    }

    public function calculerPrix(int $jours){
        return $this->prixJour * $jours;
    }

    public function estDisponible(){
        return $this->disponible;
    }
}

class Voiture extends Vehicule implements ReservableInterface {
    private int $nbPortes;
    private string $transmission;

    public function __construct($id, $immatriculation, $marque, $modele, $prixJour, $disponible, $nbPortes, $transmission) {
        $this->id = $id;
        $this->immatriculation = $immatriculation;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->prixJour = $prixJour;
        $this->nbPortes = $nbPortes;
        $this->disponible = $disponible;
        $this->transmission = $transmission;
    }
    public function getType(){
        return "Voiture";
    }
    public function reserver(Client $client, DateTime $dateDebut, int $nbJours): Reservation {
        if ($this->estDisponible()) {
            $this->disponible = false;
            return new Reservation($this, $client, $dateDebut, $nbJours);
        } else throw Exception("non disponible");
    }
}

class Moto extends Vehicule implements ReservableInterface {
    private int $cylindree;

    public function __construct($id, $immatriculation, $marque, $modele, $prixJour, $disponible, $cylindree) {
        $this->id = $id;
        $this->modele = $modele;
        $this->marque = $marque;
        $this->prixJour = $prixJour;
        $this->cylindree = $cylindree;
        $this->disponible = $disponible;
        $this->immatriculation = $immatriculation;
    }
    public function getType(){
        return "Moto";
    }
    public function reserver(Client $client, DateTime $dateDebut, int $nbJours): Reservation {
        if ($this->estDisponible()) {
            $this->disponible = false;
            return new Reservation($this, $client, $dateDebut, $nbJours);
        } else throw Exception("non disponible");
    }
}

class Camion extends Vehicule implements ReservableInterface {
    private int $capaciteTonnage;

    public function __construct($id, $immatriculation, $marque, $modele, $prixJour, $disponible, $capaciteTonnage) {
        $this->id = $id;
        $this->modele = $modele;
        $this->marque = $marque;
        $this->prixJour = $prixJour;
        $this->disponible = $disponible;
        $this->capaciteTonnage = $capaciteTonnage;
        $this->immatriculation = $immatriculation;
    }
    public function getType(){
        return "Camion";
    }
    public function reserver(Client $client, DateTime $dateDebut, int $nbJours): Reservation {
        if (!$this->estDisponible()) {
            $this->disponible = false;
            return new Reservation($this, $client, $dateDebut, $nbJours);
        } else throw new Exception("non disponible.");
    }
}

abstract class Personne {
    protected $prenom;
    protected $email;
    protected $nom;

    abstract public function afficherProfil();
}

class Client extends Personne {
    private int $numeroClient;
    private $reservations = [];

    public function __construct($nom, $prenom, $email, $numeroClient) {
        $this->nom = $nom;
        $this->email = $email;
        $this->prenom = $prenom;
        $this->numeroClient = $numeroClient;
    }
    public function ajouterReservation(Reservation $r) {
        $this->reservations[] = $r;
    }
    public function afficherProfil() {
        return "Client: ".$this->nom.$this->prenom.", Email: ".$this->email.", Réservations: " . count($this->reservations);
    }
    public function getHistorique() {
        return $this->reservations;
    }
}

class Agence {
    private $nom;
    private $ville;
    private $clients = [];
    private $vehicules = [];

    public function __construct($nom, $ville) {
        $this->nom = $nom;
        $this->ville = $ville;
    }
    public function ajouterVehicule(Vehicule $v) {
        $this->vehicules[] = $v;
    }
    public function rechercherVehiculeDisponible(string $type){
        foreach ($this->vehicules as $vehicule) {
            if ($vehicule->getType() === $type && $vehicule->estDisponible()) {
                return $vehicule;
            }else throw new Exception("non disponible.");
        }
    }
    public function enregistrerClient(Client $c) {
        $this->clients[] = $c;
    }
    public function faireReservation(Client $client, Vehicule $vehicule, DateTime $dateDebut, int $nbJours): Reservation {
        if ($vehicule->estDisponible()) {
            $reservation = $vehicule->reserver($client, $dateDebut, $nbJours);
            $client->ajouterReservation($reservation);
            return $reservation;
        } else throw new Exception("Véhicule non disponible.");
    }
}

class Reservation {
    private int $nbJours;
    private string $statut;
    private Client $client;
    private Vehicule $vehicule;
    private DateTime $dateDebut;

    public function __construct(Vehicule $vehicule, Client $client, DateTime $dateDebut, int $nbJours) {
        $this->dateDebut = $dateDebut;
        $this->vehicule = $vehicule;
        $this->nbJours = $nbJours;
        $this->client = $client;
        $this->statut = "att";
    }
    public function calculerMontant(){
        return $this->vehicule->calculerPrix($this->nbJours);
    }
    public function confirmer() {
        $this->statut = "confirmee";
    }
    public function annuler() {
        $this->statut = "annulee";
    }
}


$agenceParis = new Agence("Paris", "Paris");
$agenceCasablanca = new Agence("Casablanca", "Casablanca");

$agenceParis->ajouterVehicule(new Voiture(1, "123ABC", "peuget", "200", 30, true, 5, "manuelle"));
$agenceParis->ajouterVehicule(new Moto(1, "123ABC", "BMW", "300", 90, true, 600));
$agenceParis->ajouterVehicule(new Camion(1, "123ABC", "scania", "400", 40, true, 600));
  
$agenceCasablanca->ajouterVehicule(new Voiture(2, "456DEF", "BMW", "200", 30, true, 5, "auto"));
$agenceCasablanca->ajouterVehicule(new Moto(2, "456DEF", "kawasaki", "KCS", 50, true, 600));
$agenceCasablanca->ajouterVehicule(new Camion(2, "456DEF", "mercides", "k34", 50, true, 6, 600));

$client1 = new Client("abde ", "abde", "abde@gmail.com", 1);
$client2 = new Client("abde2 ", "abde2", "abde2@gmail.com", 2);

$agenceParis->enregistrerClient($client1);
$agenceCasablanca->enregistrerClient($client2);

$reservation1 = $agenceParis->faireReservation($client1, $agenceParis->rechercherVehiculeDisponible("Voiture"), new DateTime(), 1);
$reservation1 = $agenceParis->faireReservation($client1, $agenceParis->rechercherVehiculeDisponible("Moto"), new DateTime(), 1);
$reservation1 = $agenceParis->faireReservation($client1, $agenceParis->rechercherVehiculeDisponible("Camion"), new DateTime(), 1);

$reservation2 = $agenceCasablanca->faireReservation($client2, $agenceCasablanca->rechercherVehiculeDisponible("Voiture"), new DateTime(), 2);
$reservation2 = $agenceCasablanca->faireReservation($client2, $agenceCasablanca->rechercherVehiculeDisponible("Moto"), new DateTime(), 2);
$reservation2 = $agenceCasablanca->faireReservation($client2, $agenceCasablanca->rechercherVehiculeDisponible("Camion"), new DateTime(), 2);

echo $client1->afficherProfil()."\n";
echo $client2->afficherProfil();