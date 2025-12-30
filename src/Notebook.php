<?php 

class Notebook extends Equipamento {
    protected $processador;
    protected $ram;

    public function __construct($id, $marca, $modelo, $status, $processador, $ram) {
        parent::__construct($id, $marca, $modelo, $status);
        $this->processador = $processador;
        $this->ram = $ram;
    }

    public function getDetalhesTecnicos() {
        return "Processador: " . $this->processador . " - RAM: " . $this->ram . "GB";
    }

    // getters and setters
    public function getProcessador() {
        return $this->processador;
    }
    public function setProcessador($processador) {
        $this->processador = $processador;
    }
    public function getRam() {
        return $this->ram;
    }
    public function setRam($ram) {
        $this->ram = $ram;
    }
}