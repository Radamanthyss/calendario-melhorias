<?php

namespace DAO;

require_once 'Database.php';

use MODEL\MelhoriaModel;

class Melhoria extends Database
{

    const TABLE = 'melhorias';
    protected static $oInstance;

    public function filtrarPorUrgencia($urgencia, $fields = null)
    {
        if (is_array($urgencia)) {
            return $this->filtrar('urgencia IN (' . implode(', ', $urgencia) . ')', null, $fields);
        }

        $whereValues = [];

        $where                   = 'urgencia = :urgencia';
        $whereValues['urgencia'] = $urgencia;

        return $this->filtrar($where, $whereValues, $fields);
    }

    /* public function salvarMelhoria(MelhoriaModel $obj)
    {
        print_r($obj);
        $dbst = $this->db->prepare(" INSERT INTO ". static::Table." VALUES (:descricao) ");
        $dbst->bindValue(':descricao', $obj->getAreaModelDescricao(), \PDO::PARAM_STR);
        $this->execute($dbst);
    } */

    /* public function getArea()
    {
        return Area::getInstance()->filtrarPorId($this->area);
    }

    public function getTendencia()
    {
        return Tendencia::getInstance()->filtrarPorId($this->tendencia);
    }

    public function getUrgencia()
    {
        return Urgencia::getInstance()->filtrarPorId($this->urgencia);
    }

    public function getGravidade()
    {
        return Gravidade::getInstance()->filtrarPorId($this->gravidade);
    } */
}
