<?php

use App\Core\Controller;

class Clientes extends Controller {

    public function index() {

        $cadastroModel = $this->model('cadastro');
        $dados = $cadastroModel->listarTodas();

        echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    }

    public function store() {

        $json = file_get_contents('php://input');

        $novoCadastro = json_decode($json);

        $cadastroModel = $this->model('cadastro');
        $cadastroModel->nomeCliente = $novoCadastro->nomeCliente;
        $cadastroModel->placa = $novoCadastro->placa;

        $cadastroModel = $cadastroModel->inserir();

        if ($cadastroModel) {
            http_response_code(201);
            echo json_encode($cadastroModel, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao inserir cadastro"]);
        }
    }

    public function calculaPreco($id) {

        $cadastroSaida = $this->model("Cliente");
        $dados = $cadastroSaida->getDadosValorApagar($id);

        if (!$dados) {
            echo json_encode($dados, JSON_UNESCAPED_UNICODE);
            http_response_code(400);
            echo json_encode(["erro" => "Cadastro não encontrado"], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $precoModel = $this->model('Preco');
        $dadosPreco = $precoModel->listarTodas();

        $totalDias = $dados[0]->totalDiasEstacionado;
        $totalHoras = $dados[0]->totalHorasEstacionado;

        $precoInicial = $dadosPreco[0]->precoInicial;
        $precoAdicionalPorHora = $dadosPreco[0]->precoAdicionalPorHora;

        if ($totalDias < 0) {
            $preco = ($precoAdicionalPorHora * (idate('H', strtotime($totalHoras))) + $precoInicial);
        }else{
            $preco = ($precoAdicionalPorHora * $totalDias * 24) + $precoInicial;
        }
       
        return $preco;
    }

    public function update($id)
    {
        $json = file_get_contents("php://input");

        $cadastroEditar = json_decode($json);

        $cadastroModel = $this->model('cadastro');

        $cadastroModel->id = $id;
        $cadastroModel->nome = $cadastroEditar->nomeCliente;
        $cadastroModel->placa = $cadastroEditar->placa;
        $cadastroModel->motivoExclusao = $cadastroEditar->motivoExclusao;
        $cadastroModel->valorPago = $this->calculaPreco($id);


        if ($cadastroModel->atualizar()) {
            http_response_code(204);

        } else {
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao atualizar o cliente"]);
        }
    }

}
