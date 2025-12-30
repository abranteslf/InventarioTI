<?php

abstract class Equipamento {
    const STATUS_OPCOES = ["Disponivel", "Em Uso", "Manutencao", "Baixado"];
    const TIPO_OPCOES = ["Notebook", "Servidor"];
    protected $id;
    protected $marca;
    protected $modelo;
    protected $status;

    public function __construct($id, $marca, $modelo, $status) {
        $this->id = $id;
        $this->marca = $marca;
        $this->modelo = $modelo;
        if (!in_array($status, self::STATUS_OPCOES)) {
            throw new InvalidArgumentException("Status inválido: $status");
        } else {
            $this->status = $status;
        }
    }

    abstract public function getDetalhesTecnicos();

    // getters and setters
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function getMarca() {
        return $this->marca;
    }
    public function setMarca($marca) {
        $this->marca = $marca;
    }
    public function getModelo() {
        return $this->modelo;
    }
    public function setModelo($modelo) {
        $this->modelo = $modelo;
    }
    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        if (!in_array($status, self::STATUS_OPCOES)) {
            throw new InvalidArgumentException("Status inválido: $status");
        } else {
            $this->status = $status;
        }
    }
}