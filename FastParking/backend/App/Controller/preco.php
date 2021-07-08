<?php

use App\Core\Controller;

class preco extends Controller {

    public function index() {
        $precoModel = $this->model("preco");

        $dados= $precoModel->listarTodas();
        echo $dados;
    }

    public function store()
    {
        $json = file_get_contents("php://input");
        $novoPreco = json_decode($json);

        $precoModel = $this->model("preco");
        $precoModel->precoInicial = $novoPreco->precoInicial;
        $precoModel->precoAdicionalPorHora = $novoPreco->precoAdicionalPorHora;
        $precoModel = $precoModel->inserir();

        if($precoModel){
            http_response_code(201);
            echo json_encode($precoModel, JSON_UNESCAPED_UNICODE);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao inserir os preços"]);
        }
    }

    public function update($id){

        $json = file_get_contents("php://input");
        $precoEditar = json_decode($json);
        $precoModel = $this->model("Preco");

        if(!$precoModel){
            http_response_code(404);
            echo json_encode(["erro" => "Preço não encontrado"]);
            exit;
        }
            
        $precoModel->id = $id;
        $precoModel->precoInicial = $precoEditar->precoInicial;
        $precoModel->precoAdicionalPorHora = $precoEditar->precoAdicionalPorHora;
           
        if($precoModel){
            http_response_code(204);
            echo json_encode($precoModel, JSON_UNESCAPED_UNICODE);
        }else{
            http_response_code(500);
            echo json_encode(["erro" => "Problemas ao atualizar os preços."]);
        }
    }


}