<?php

require "config/Conexao.php";
require "interfaces/DAOInterface.php";

class RoteiroDAO implements DAOInterface {

  private $conexao;

  public function RoteiroDAO() {
    $conexao = Conexao::getShared();
    $this->conexao = $conexao->getDB();
  }

  public function getAll() {
    $sql = "SELECT * FROM roteiro;";
    $resultado = $this->conexao->query($sql);
    if($resultado->num_rows == 0){
        return [];
    } else {
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }
  }

  public function getById($id) {
    $sql = "SELECT * FROM roteiro WHERE id = ". $id;
    $result = $this->conexao->query($sql);
    if($result->num_rows == 0) {
      return null;
    } else {
      return $result->fetch_assoc();
    }
  }

  public function save($object) {
    $sql = "INSERT INTO roteiro (intervalo_entregas, progresso, template_id, time_id) VALUES ("
            .$object->getIntervaloEntregas().", "
            .$object->getProgresso().", "
            .$object->getTemplateId().", "
            .$object->getTimeId().")";

    $resultado = $this->conexao->query($sql);
    if(!$resultado){
      return null;
    } else {
      $id = $this->conexao->insert_id;
      return $this->getById($id);
    }
  }

  public function update($object, $id) {
    $sql = "UPDATE roteiro SET "
            ."intervalo_entregas = ".$object->getIntervaloEntregas().", "
            ."progresso = ".$object->getProgresso().", "
            ."template_id = ".$object->getTemplateId().", "
            ."time_id = ".$object->getTimeId()." WHERE id = $id";

    $resultado = $this->conexao->query($sql);
    if(!$resultado){
        return null;
    } else {
        return $this->getById($id);
    }
  }

  public function delete($id) {
    $sql = "DELETE FROM roteiro WHERE id = ". $id;
    return $this->conexao->query($sql);
  }

  public function model() {
    $data = new stdClass();
    $data->nome = 'Roteiro';
    $data->endpoint = 'http://localhost:8080/padawan-ideas-api/roteiro';

    $recursos = new stdClass();
    $recursos = [
      array('action' => 'POST',
        'fields' =>
          [
            array('field' => 'intervalo_entregas', 'type' => 'int', 'required' => true),
            array('field' => 'progresso', 'type' => 'float', 'required' => true),
            array('field' => 'template_id', 'type' => 'int', 'required' => false),
            array('field' => 'time_id', 'type' => 'int', 'required' => true)
          ]
      ),
      array('action' => 'GET',
        'fields' =>
          [
            array('field' => 'id', 'type' => 'int', 'required' => false),
          ]
      ),
      array('action' => 'PUT',
        'fields' =>
          [
            array('field' => 'intervalo_entregas', 'type' => 'int', 'required' => false),
            array('field' => 'progresso', 'type' => 'float', 'required' => false),
            array('field' => 'template_id', 'type' => 'int', 'required' => false),
            array('field' => 'time_id', 'type' => 'int', 'required' => false)
          ]
      ),
    ];

    $data->recursos = $recursos;
    return $data;
  }
}
