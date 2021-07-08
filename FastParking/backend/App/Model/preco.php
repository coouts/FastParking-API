<?php

use App\Core\Model;

class preco {

    public $id;
    public $precoInicial;
    public $precoAdicionalPorHora;

    public function listarTodas() {
        $sql = " SELECT * FROM tblPrecos ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $resultado = $stmt->fetchAll(\PDO::FETCH_OBJ);

            return $resultado;
        } else {
            return [];
        }
    }

    public function inserir() {
        $sql = " INSERT INTO tblPrecos (precoInicial, precoAdicionalPorHora) VALUES (?, ?) ";

        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $this->precoInicial);
        $stmt->bindValue(2, $this->precoAdicionalPorHora);
        if ($stmt->execute()) {
            $this->id = Model::getConexao()->lastInsertId();
            return $this;
        } else {
            return false;
        }
    }

    public function atualizar() {

        $sql = " UPDATE tblPrecos SET
                  precoInicial = ?, precoAdicionalPorHora = ? WHERE idPreco = ? ";
        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $this->precoInicial);
        $stmt->bindValue(2, $this->demaisHoras);
        $stmt->bindValue(3, $this->id);
        return $stmt->execute();
    }
}