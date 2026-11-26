<?php

// require_once("util/Conexao.php");
// $conexao = Conexao::getConexao();
// print_r($conexao);

require_once("modelo/ClientePF.php");
require_once("modelo/ClientePJ.php");
require_once("dao/ClienteDAO.php");

$clienteDAO = new ClienteDAO();

do {
    echo "\n\n---CADASTRO DE CLIENTES---\n\n";
    echo "1- Inserir Cliente PF\n";
    echo "2- Inserir Cliente PJ\n";
    echo "3- Listar Clientes\n";
    echo "4- Buscar Clientes\n";
    echo "5- Excluir Clientes\n";
    echo "0- Sair \n\n";
    $opcao =  readline("Opção: ");
    switch ($opcao) {
        case '1':
            $clientePF = new ClientePF();
            $clientePF->setNomeCompleto(readline("Nome Completo: "));
            $clientePF->setNomeSocial(readline("Nome Social: "));
            $clientePF->setCpf(readline("Informe seu CPF: "));
            $clientePF->setEmail(readline("Informe seu e-mail: "));

            $clienteDAO->inserir($clientePF);
            break;
        case '2':
            $clientePJ = new ClientePJ();
            $clientePJ->setRazaoSocial(readline("Razão Social: "));
            $clientePJ->setNomeSocial(readline("Nome Social: "));
            $clientePJ->setCnpj(readline("Informe seu CNPJ: "));
            $clientePJ->setEmail(readline("Informe seu e-mail: "));
            $clienteDAO->inserir($clientePJ);
            break;
        case '3':
            $clientes = $clienteDAO->listar();
            if ($clientes == null) {
                echo "\n Nenhum cliente cadastrado \n";
            } else {
                foreach ($clientes as $c) {
                    echo "\n" . $c . "\n";
                }
            }
            break;
        case '4':
            $idCliente = readline("Qual ID deseja buscar? ");
            $cliente = $clienteDAO->buscarPorId($idCliente);
            if ($cliente == null) {
                echo "\n ID não existe \n";
            } else {
                echo "\n" .  $cliente . "\n";
            }
            break;
        case '5':
            $idCliente = readline("Qual ID deseja excluir? ");
            $cliente = $clienteDAO->buscarPorId($idCliente);
            if ($cliente == null) {
                echo "\n ID não existe \n";
            } else {
                $clienteDAO->excluirPorId($idCliente);
                echo "\n Cliente excluido \n";
            }
            break;
        case '0':
            echo "Progama encerrado \n";
            break;
        default:
            echo "Opção inválida \n";
            break;
    }
} while ($opcao != 0);
