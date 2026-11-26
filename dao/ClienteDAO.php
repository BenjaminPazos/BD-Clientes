<?php
require_once("modelo/Cliente.php");
require_once("modelo/ClientePF.php");
require_once("modelo/ClientePJ.php");
require_once("util/Conexao.php");

class ClienteDAO
{
    public function inserir(Cliente $cliente)
    {
        $sql = "INSERT INTO clientes
                    (tipo, nome_social, email, 
                    nome_completo, cpf, razao_social, cnpj)
                VALUES(?, ?, ?, ?, ?, ?, ?)";
        $conexao = Conexao::getConexao();
        $stm = $conexao->prepare($sql);
        if ($cliente instanceof ClientePF) {
            $stm->execute(array(
                $cliente->getTipo(),
                $cliente->getNomeSocial(),
                $cliente->getEmail(),
                $cliente->getNomeCompleto(),
                $cliente->getCpf(),
                null,
                null
            ));
        } else if ($cliente instanceof ClientePJ) {
                $stm->execute(array(
                $cliente->getTipo(),
                $cliente->getNomeSocial(),
                $cliente->getEmail(),
                null,
                null,
                $cliente->getRazaoSocial(),
                $cliente->getCnpj()
            ));
        }
    }
    public function listar()
    {
        $sql = "SELECT * FROM clientes";
        $conexao = Conexao::getConexao();
        $stm = $conexao->prepare($sql);
        $stm->execute();
        $dados = $stm->fetchAll();
        $clientes = $this->mapear($dados);
        return $clientes;
    }
    private function mapear(array $dados): array
    {
        $clientes = [];

        foreach ($dados as $d) {
            $tipo = $d['tipo'];
            $cliente = null;
            if ($tipo == "F") {
                $cliente = new ClientePF();
                $cliente->setCpf($d["cpf"]);
                $cliente->setNomeCompleto($d["nome_completo"]);
            } else if ($tipo == "J") {
                $cliente = new ClientePJ();
                $cliente->setRazaoSocial($d["razao_social"]);
                $cliente->setCnpj($d["cnpj"]);
            } else {
                continue;
            }
            $cliente->setId($d["id"]);
            $cliente->setNomeSocial($d["nome_social"]);
            $cliente->setEmail($d["email"]);
            array_push($clientes, $cliente);
        }

        return $clientes;
    }

    public function buscarPorId(int $idCliente){
        $sql = "SELECT * FROM clientes WHERE id = $idCliente";
        $conexao = Conexao::getConexao();
        $stm = $conexao->prepare($sql);
        $stm->execute();
        $dados = $stm->fetchAll();
        if (count($dados) == 1){
            $cliente = $this->mapear($dados);
            return $cliente[0];
        } else {
            return null;
        }
    }
    public function excluirPorId(int $idCliente){
        $sql = "DELETE FROM clientes WHERE id = $idCliente";
        $conexao = Conexao::getConexao();
        $stm = $conexao->prepare($sql);
        $stm->execute();
    }
}
