<?php

require_once "src/Equipamento.php";
require_once "src/Notebook.php";
require_once "src/Servidor.php";

$equipsJson = json_decode(file_get_contents("data/equipamentos.json"), true);
$equipamentos = [];
foreach ($equipsJson as $equip) {
    switch ($equip["tipo"]) {
        case "Notebook":
            $notebook = new Notebook($equip["id"], $equip["marca"], $equip["modelo"], $equip["status"], 
            $equip["processador"], $equip["ram"]);
            $equipamentos[$equip["id"]] = $notebook;
            break;
        case "Servidor":
            $servidor = new Servidor($equip["id"], $equip["marca"], $equip["modelo"], $equip["status"], 
            $equip["capacidadeStorageTB"], $equip["qtdBaias"]);
            $equipamentos[$equip["id"]] = $servidor;
            break;
    }
}

do {
    echo "\n===== Menu =====\n";
    echo "1. Listar Equipamentos\n";
    echo "2. Alterar Status de Equipamento\n";
    echo "3. Cadastrar Equipamento\n";
    echo "0. Sair\n";
    echo "Escolha uma opção: ";
    $opcao = trim(fgets(STDIN)); // file get string, standard input, trim para remover espaços em branco

    switch ($opcao) {
        case '1':
            listarEquipamentos();
            break;
        case '2':
            alterarStatusEquipamento();
            break;
        case '3':
            cadastrarEquipamento();
            break;
        case '0':
            echo "Saindo...\n";
            break;
        default:
            echo "Opção inválida. Tente novamente.\n";
            break;
    }
} while ($opcao !== '0');

function listarEquipamentos() {
    global $equipamentos;
    foreach ($equipamentos as $equip) {
        echo $equip->getId() . " - " . $equip->getMarca() . " " . $equip->getModelo() . " - " . $equip->getStatus() . " - " . $equip->getDetalhesTecnicos() . "\n";
    }
}

function alterarStatusEquipamento() {
    global $equipamentos;
    do {
        echo "Digite o ID do equipamento que deseja alterar o status: ";
        $id = trim(fgets(STDIN));
        if (isset($equipamentos[$id])) {
            $equip = $equipamentos[$id];
            echo "Status atual: " . $equip->getStatus() . "\n";
            $statusOpcoes = Equipamento::STATUS_OPCOES;
            $op = null;
            do {
                echo "Escolha o novo status:\n";
                foreach ($statusOpcoes as $index => $status) {
                    echo ($index + 1) . ". $status\n";
                }
                echo "Digite o número correspondente ao novo status: ";
                $op = trim(fgets(STDIN));
                $op = intval($op);
                if ($op < 1 || $op > count($statusOpcoes)) {
                    echo "Opção inválida. Por favor, digite um número entre 1 e " . count($statusOpcoes) . ".\n";
                }
            } while ($op < 1 || $op > count($statusOpcoes));
            try {
                $novoStatus = $statusOpcoes[$op - 1];
                $equip->setStatus($novoStatus);
                $equipamentos[$id] = $equip;
                salvarEquipamentosJson($equipamentos);
                echo "Status alterado para \"$novoStatus\" e salvo no arquivo.\n";
            } catch (Exception $e) {
                echo "Opção de status inválida. " . $e->getMessage() . "\n";
            }
        } else {
            echo "Equipamento com ID $id não encontrado.\n";
        }
    } while (!isset($equipamentos[$id]));
}

function cadastrarEquipamento() {
    global $equipamentos;
    // tipo
    do {
        echo "Escolha o tipo de equipamento a ser cadastrado:\n";
        foreach (Equipamento::TIPO_OPCOES as $index => $tipo) {
            echo ($index + 1) . ". $tipo\n";
        }
        echo "Digite o número correspondente ao tipo de equipamento: ";
        $op = trim(fgets(STDIN));
        $op = intval($op); // converte para inteiro
        if ($op < 1 || $op > count(Equipamento::TIPO_OPCOES)) {
            echo "Opção inválida. Por favor, digite um número entre 1 e " . count(Equipamento::TIPO_OPCOES) . ".\n";
        }
    } while ($op < 1 || $op > count(Equipamento::TIPO_OPCOES));
    $tipo = Equipamento::TIPO_OPCOES[$op - 1];

    // marca e modelo
    do {
        echo "Digite a marca do equipamento: ";
        $marca = trim(fgets(STDIN));
        if (empty($marca)) {
            echo "A marca não pode ser vazia. Por favor, digite uma marca válida.\n";
        }   
    } while (empty($marca));
    
    do {
        echo "Digite o modelo do equipamento: ";
        $modelo = trim(fgets(STDIN));
        if (empty($modelo)) {
            echo "O modelo não pode ser vazio. Por favor, digite um modelo válido.\n";
        }
    } while (empty($modelo));

    // status
    echo "Escolha o status do equipamento: \n";
    foreach (Equipamento::STATUS_OPCOES as $index => $status) {
        echo ($index + 1) . ". $status\n";
    }
    do {
        echo "Digite o número correspondente ao status do equipamento: ";
        $op = trim(fgets(STDIN));
        $op = intval($op);
        if ($op < 1 || $op > count(Equipamento::STATUS_OPCOES)) {
            echo "Opção inválida. Por favor, digite um número entre 1 e " . count(Equipamento::STATUS_OPCOES) . ".\n";
        }
    } while ($op < 1 || $op > count(Equipamento::STATUS_OPCOES));
    $status = Equipamento::STATUS_OPCOES[$op - 1];

    // tipos
    // === ID logic refatorado! ===
    if (empty($equipamentos)) {
        $id = 1;
    } else {
        $id = max(array_keys($equipamentos)) + 1;
    }

    if ($tipo == "Notebook") {
        echo "Digite o processador do notebook: ";
        $processador = trim(fgets(STDIN));

        do {
            echo "Digite a RAM do notebook (em GB): ";
            $ram = trim(fgets(STDIN));
            if (!is_numeric($ram) || intval($ram) < 1) {
                echo "Informe um valor numérico válido para a RAM (maior que 0).\n";
            }
        } while (!is_numeric($ram) || intval($ram) < 1);

        $equip = new Notebook($id, $marca, $modelo, $status, $processador, intval($ram));
        $equipamentos[$equip->getId()] = $equip;
        salvarEquipamentosJson($equipamentos);
        echo "Equipamento cadastrado com sucesso.\n";
    } elseif ($tipo == "Servidor") {

        do {
            echo "Digite a capacidade de storage do servidor (em TB): ";
            $capacidadeStorageTB = trim(fgets(STDIN));
            if (!is_numeric($capacidadeStorageTB) || floatval($capacidadeStorageTB) <= 0) {
                echo "Informe um valor numérico válido para a capacidade de storage (maior que 0).\n";
            }
        } while (!is_numeric($capacidadeStorageTB) || floatval($capacidadeStorageTB) <= 0);

        do {
            echo "Digite a quantidade de baias do servidor: ";
            $qtdBaias = trim(fgets(STDIN));
            if (!is_numeric($qtdBaias) || intval($qtdBaias) < 1) {
                echo "Informe um valor numérico válido para a quantidade de baias (maior que 0).\n";
            }
        } while (!is_numeric($qtdBaias) || intval($qtdBaias) < 1);

        $equip = new Servidor($id, $marca, $modelo, $status, floatval($capacidadeStorageTB), intval($qtdBaias));
        $equipamentos[$equip->getId()] = $equip;
        salvarEquipamentosJson($equipamentos);
        echo "Equipamento cadastrado com sucesso.\n";
    }
}


function salvarEquipamentosJson($equipamentos) {
    $arrayParaJson = [];
    foreach ($equipamentos as $equip) {
        if ($equip instanceof Notebook) {
            $arrayParaJson[] = [
                "id" => $equip->getId(),
                "tipo" => "Notebook",
                "marca" => $equip->getMarca(),
                "modelo" => $equip->getModelo(),
                "status" => $equip->getStatus(),
                "processador" => $equip->getProcessador(),
                "ram" => $equip->getRam()
            ];
        } elseif ($equip instanceof Servidor) {
            $arrayParaJson[] = [
                "id" => $equip->getId(),
                "tipo" => "Servidor",
                "marca" => $equip->getMarca(),
                "modelo" => $equip->getModelo(),
                "status" => $equip->getStatus(),
                "capacidadeStorageTB" => $equip->getCapacidadeStorageTB(),
                "qtdBaias" => $equip->getQtdBaias()
            ];
        }
    }
    file_put_contents("data/equipamentos.json", json_encode($arrayParaJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}