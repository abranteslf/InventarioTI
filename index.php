<?php

require_once "src/Equipamento.php";
require_once "src/Notebook.php";
require_once "src/Servidor.php";

// Código original usando array indexada:
// $equipsJson = json_decode(file_get_contents("data/equipamentos.json"), true);
// $equipamentos = [];
// foreach ($equipsJson as $equip) {
//     switch ($equip["tipo"]) {
//         case "Notebook":
//             $notebook = new Notebook($equip["id"], $equip["marca"], $equip["modelo"], $equip["status"], 
//             $equip["processador"], $equip["ram"]);
//             $equipamentos[] = $notebook;
//             break;
//         case "Servidor":
//             $servidor = new Servidor($equip["id"], $equip["marca"], $equip["modelo"], $equip["status"], 
//             $equip["capacidadeStorageTB"], $equip["qtdBaias"]);
//             $equipamentos[] = $servidor;
//             break;
//     }
// }

// Reescrito para usar array associativa (usando o ID como chave):
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