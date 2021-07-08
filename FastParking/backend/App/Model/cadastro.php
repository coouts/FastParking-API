<?php

use App\Core\Model;
  
class cadastro{

    public $idCadastro;
    public $nomeCliente;
    public $horaEntrada;
    public $horaSaida;
    public $idPreco;
    public $placa;
    public $dataEntrada;
    public $dataSaida;
    
    public function ListarTodas()
    {
      $sql = " SELECT idCadastro, 
          nomeCliente, 
          placa, 
          date_format(dataEntrada, '%d/%m/%Y') as dataEntrada,
          time_format(horaEntrada, '%H:%i') as horaEntrada,
          time_format(horaSaida, '%H:%i') as horaSaida,
          valor,
          idPreco
          FROM tblCarros ";
  
      $stmt = Model::getConexao()->prepare($sql);
      $stmt->execute();
  
      if ($stmt->rowCount() > 0) {
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
  
        return $result;
      } else {
        return [];
      }
    }
    public function insert()
    {
      $sql = " INSERT INTO tblCadastro
                   (dataEntrada, horaEntrada, nomeCliente, placa,idPreco)
                   VALUES
                   (curdate(), curtime(), ?, ?, ?) ";
  
      $stmt = Model::getConexao()->prepare($sql);
      $stmt->bindValue(1, $this->nome);
      $stmt->bindValue(2, $this->placa);
      $stmt->bindValue(3, $this->idPreco);
  
      if ($stmt->execute()) {
        $this->idCarro = Model::getConexao()->lastInsertId();
        return $this;
      } else {
        return false;
      }
    }
    public function preco()
    {
      $sql = " SELECT MAX(idPreco) as idPreco, primeiraHora, precoAdicionalPorHora  FROM tblPrecos ";
  
      $stmt = Model::getConexao()->prepare($sql);
      $stmt->execute();
  
      if ($stmt->rowCount() > 0) {
        $preco = $stmt->fetch(PDO::FETCH_OBJ);
  
        return $preco;
      } else {
        return [];
      }
    }
  
    public function buscarId($id)
    {
      $sql = " SELECT idCadastro, 
          nomeCliente, 
          placa, 
          date_format(dataEntrada, '%d/%m/%Y') as dataEntrada,
          time_format(horaEntrada, '%H:%i') as horaEntrada,
          time_format(horaSaida, '%H:%i') as horaSaida,
          valor,
          idPreco
          FROM tblCadastro WHERE idCadastro = ? ";
  
      $stmt = Model::getConexao()->prepare($sql);
      $stmt->bindValue(1, $id);
      $stmt->execute();
  
      if ($stmt->rowCount() > 0) {
        $cadastro = $stmt->fetch(PDO::FETCH_OBJ);
  
        $this->idCadastro = $cadastro->idCadastro;
        $this->nome = $cadastro->nome;
        $this->placa = $cadastro->placa;
        $this->dataEntrada = $cadastro->dataEntrada;
        $this->horaEntrada = $cadastro->horaEntrada;
        $this->horaSaida = $cadastro->horaSaida;
        $this->valor = $cadastro->valor;
        $this->idPreco = $cadastro->idPreco;
  
        return $this;
      } else {
        return false;
      }
    }
    public function atualizar()
    {
      $sql =  "UPDATE tblCadastro
          SET nome = ?, placa = ? 
          WHERE idCadastro = ? ";
  
      $stmt = Model::getConexao()->prepare($sql);
      $stmt->bindValue(1, $this->nome);
      $stmt->bindValue(2, $this->placa);
      $stmt->bindValue(3, $this->idCadastro);
  
      return $stmt->execute();
    }
    public function delete()
    {
        $sql = " UPDATE tblCadastro
                 SET horaSaida = curtime(), valoro = ? 
                 WHERE idCarro = ? ";
  
        $stmt = Model::getConexao()->prepare($sql);
        $stmt->bindValue(1, $this->valor);
        $stmt->bindValue(2, $this->idCadastro);
  
        return $stmt->execute();
    }
  }