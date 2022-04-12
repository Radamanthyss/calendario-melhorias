<?php

namespace DAO;

require_once 'Database.php';
require_once './model/MelhoriaModel.php';

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

    public function salvarAtualizarMelhoria(MelhoriaModel $obj)
    {

        if (!$obj->getGravidade() && !$obj->getUrgencia() && !$obj->getTendencia()) {
            echo ("É necessario informar PELO MENOS um dos itens do GUT (Gravidade, Urgência, Tendência)");
        } else {
            if ($obj->getId() > 0) {
                $dbst = $this->db->prepare("UPDATE Melhorias SET tarefa = :tarefa, descricao = :descricao, 
                prazo_legal = :prazo_legal, prazo_acordado = :prazo_acordado, area = :area, tendencia = :tendencia,
                urgencia = :urgencia, gravidade = :gravidade, demanda_legal = :demanda_legal
                WHERE id = :id");
                $dbst->bindValue(':id', $obj->getId(), \PDO::PARAM_INT);
            } else {
                $dbst = $this->db->prepare("INSERT INTO Melhorias (tarefa, descricao,prazo_legal, prazo_acordado,
                area, tendencia, urgencia, gravidade, demanda_legal) VALUES (:tarefa, :descricao, :prazo_legal, :prazo_acordado,
                :area, :tendencia, :urgencia, :gravidade, :demanda_legal)");
            }
            //checaDataPrazos retorna true se as datas dos prazos informados correspoderem a regra de estarem dentro dos dias atuais!e não no passado.
            if ($this->checaDataPrazos($obj)) { //O prazo acordado e o prazo legal devem estar entre a data atual e o último dia do ano corrente.

                if ($obj->prazo_legal != "") {
                    $dbst->bindValue(':prazo_legal', $obj->prazo_legal, \PDO::PARAM_STR);
                } else {
                    $dbst->bindValue(':prazo_legal', $obj->prazo_legal, \PDO::PARAM_NULL);
                }

                $dbst->bindValue(':prazo_acordado', $obj->prazo_acordado, \PDO::PARAM_STR);

                if ($obj->tendencia != "") {
                    $dbst->bindValue(':tendencia', $obj->tendencia, \PDO::PARAM_INT);
                } else {
                    $dbst->bindValue(':tendencia', $obj->tendencia, \PDO::PARAM_NULL);
                }

                if ($obj->urgencia != "") {
                    $dbst->bindValue(':urgencia', $obj->urgencia, \PDO::PARAM_INT);
                } else {
                    $dbst->bindValue(':urgencia', $obj->urgencia, \PDO::PARAM_NULL);
                }

                if ($obj->gravidade != "") {
                    $dbst->bindValue(':gravidade', $obj->gravidade, \PDO::PARAM_INT);
                } else {
                    $dbst->bindValue(':gravidade', $obj->gravidade, \PDO::PARAM_NULL);
                }

                echo '$obj->demanda_legal= ' . PHP_EOL . $obj->demanda_legal;
                $dbst->bindValue(':tarefa', $obj->descricao, \PDO::PARAM_STR);
                $dbst->bindValue(':descricao', $obj->descricao, \PDO::PARAM_STR);
                $dbst->bindValue(':area', $obj->area, \PDO::PARAM_INT);
                $dbst->bindValue(':demanda_legal', $obj->demanda_legal, \PDO::PARAM_BOOL);

                if ($dbst->execute() === false) {
                    echo "Deu pau ao executar o statement " . print_r($dbst->errorInfo());
                };
            } else {
                echo 'Não é possivel cadastrar estes Prazos, pois devem estar entre a data atual e o último dia do ano corrente!';
            }
        }
    }

    public function removerMelhoria(MelhoriaModel $obj)
    {
        $dbst = $this->db->prepare(" DELETE FROM " . static::TABLE . " WHERE id = :id");
        $dbst->bindValue(':id', $obj->getId(), \PDO::PARAM_INT);
        $this->execute($dbst);
    }

    public function checaDataPrazos(MelhoriaModel $obj) // retorna TRUE se estiver dentro do prazo de data atual!
    {
        $retorno = false;
        $pa = $obj->getPrazo_acordado();
        $pl = $obj->getPrazo_legal();
        $dataAtual = date('Y-m-d');

        if ($pa >= $dataAtual) { //a lógica é, atraves da var $retorno armazenar se a $pa esta dentro da data atual,se sim seta true
            $retorno = true; //pode ou n ter sido informado a $pl, independente disto o pa tem q ser validado antes pois se estiver correto já fica como true!
            if ($pl != "" && $pl < $dataAtual) { //checa a var $pl, se estiver declarada mas fora da data atual(anterior a data atual), ai seta false pro retorno
                $retorno = false;
            }
        }

        return $retorno;
    }

    public function retornaDescArea($id)
    {
        $dbst = $this->db->prepare(" SELECT descricao FROM Area WHERE id = :id");
        $dbst->bindValue(':id', $id, \PDO::PARAM_INT);
        return $this->execute($dbst);
    }

    public function retornaDescGravidade($id)
    {
        $dbst = $this->db->prepare(" SELECT descricao FROM config.Gravidade WHERE id = :id");
        $dbst->bindValue(':id', $id, \PDO::PARAM_INT);
        return $this->execute($dbst);
    }

    public function retornaDescUrgencia($id)
    {
        $dbst = $this->db->prepare(" SELECT descricao FROM config.Urgencia WHERE id = :id");
        $dbst->bindValue(':id', $id, \PDO::PARAM_INT);
        return $this->execute($dbst);
    }

    public function retornaDescTendencia($id)
    {
        $dbst = $this->db->prepare(" SELECT descricao FROM config.Tendencia WHERE id = :id");
        $dbst->bindValue(':id', $id, \PDO::PARAM_INT);
        return $this->execute($dbst);
    }
}
