<?php

namespace DAO;

require_once 'Database.php';
require_once '../model/AreaModel.php';

use MODEL\AreaModel;

class Area extends Database
{

    const TABLE = 'area';
    protected static $oInstance;

    public function salvarArea(AreaModel $obj)
    {
        if (!$this->checaExistenciaArea($obj)) { //checa se já existe alguma area igual cadastrada, caso contrario, faz o cadastro.         
            $dbst = $this->db->prepare(" INSERT INTO Area(descricao) VALUES (:descricao) ");
            $dbst->bindValue(':descricao', $obj->getDescricao(), \PDO::PARAM_STR);
            $this->execute($dbst);
        } else {
            echo 'ÀREA INFORMADA JÁ EXISTE NO BANCO DE DADOS!';
        }
    }

    private function checaExistenciaArea(AreaModel $obj)
    {
        $dbst = $this->db->prepare(" SELECT id FROM Area WHERE descricao = :descricao ");
        $dbst->bindValue(':descricao', $obj->getDescricao(), \PDO::PARAM_STR);
        $retorno = $this->execute($dbst);
        if (sizeof($retorno) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function atualizarArea(AreaModel $obj)
    {
        $dbst = $this->db->prepare(" UPDATE Area SET descricao = :descricao WHERE id = :id");
        $dbst->bindValue(':descricao', $obj->getDescricao(), \PDO::PARAM_STR);
        $dbst->bindValue(':id', $obj->getId(), \PDO::PARAM_INT);
        $this->execute($dbst);
    }

    public function removerArea(AreaModel $obj)
    {
        if (!$this->checaTarefaExistenteComArea($obj)) { //implementando a proteção para remoção de àreas ja com tarefas cadastradas.
            $dbst = $this->db->prepare(" DELETE FROM Area WHERE id = :id");
            $dbst->bindValue(':id', $obj->getId(), \PDO::PARAM_INT);
            $this->execute($dbst);
        } else {
            echo 'Impossível efetuar a remoção de uma àrea que possui tarefas cadastradas!';
        }
    }

    public function checaTarefaExistenteComArea(AreaModel $obj)
    {
        $dbst = $this->db->prepare(" SELECT melhorias.id FROM Melhorias inner join Area on melhorias.area = area.id WHERE melhorias.area = :id");
        $dbst->bindValue(':id', $obj->getId(), \PDO::PARAM_INT);
        return $this->execute($dbst);
    }
}
