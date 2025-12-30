<?php 

class Servidor extends Equipamento {
    protected $capacidadeStorageTB;
    protected $qtdBaias;

    public function __construct($id, $marca, $modelo, $status, $capacidadeStorageTB, $qtdBaias) {
        parent::__construct($id, $marca, $modelo, $status);
        $this->capacidadeStorageTB = $capacidadeStorageTB;
        $this->qtdBaias = $qtdBaias;
    }

    public function getDetalhesTecnicos() {
        return "Capacidade de Storage: " . $this->capacidadeStorageTB . "TB - Quantidade de Baias: " . $this->qtdBaias;
    }

    // getters and setters
    public function getCapacidadeStorageTB() {
        return $this->capacidadeStorageTB;
    }
    public function setCapacidadeStorageTB($capacidadeStorageTB) {
        $this->capacidadeStorageTB = $capacidadeStorageTB;
    }
    public function getQtdBaias() {
        return $this->qtdBaias;
    }
    public function setQtdBaias($qtdBaias) {
        $this->qtdBaias = $qtdBaias;
    }
}