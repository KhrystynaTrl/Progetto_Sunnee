<?php
namespace App\models;

class Ordine{
    public $ID;
    public $data_di_vendita;
    public $quantita;
    public $prodotto;

public function __construct($ID, $data_di_vendita, $quantita, $prodotto){
    $this->ID=$ID;
    $this->data_di_vendita=$data_di_vendita;
    $this->quantita = $quantita;
    $this->prodotto = $prodotto;
}
}


?>