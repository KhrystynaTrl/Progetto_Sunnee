<?php
namespace models; 
class Prodotto{
    
    public $ID;
    public $nome;
    public $kg_riciclati;

public function __construct($ID, $nome, $kg_riciclati){
    $this->ID = $ID;
    $this->nome = $nome;
    $this->kg_riciclati = $kg_riciclati;
}

}