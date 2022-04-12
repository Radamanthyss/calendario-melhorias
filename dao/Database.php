<?php

namespace DAO;

use MODEL\AreaModel;
use MODEL\MelhoriaModel;

class Database
{

    protected $db;
    protected $order = [];

    /**
     * Representa a instancia a classe,
     * logo nas classes filhas esse atributo
     * deve ser sobrescrito de maneira que
     * mantenha em memória a instância correta
     *
     * @var Class
     * @access protected
     */
    protected static  $oInstance;

    public function __construct($dbname = 'melhorias', $host = 'dbsellerdb', $port = '5432', $user = 'postgres', $pass = '')
    {
        $dsn = "pgsql:dbname={$dbname};host={$host};port={$port}";

        $this->db = new \PDO($dsn, $user, $pass);
    }

    /**
     * Retorna a instancia do repositório
     *
     * @return static
     */
    public static function getInstance()
    {
        if (empty(static::$oInstance)) {
            static::$oInstance = new static;
        }

        return static::$oInstance;
    }

    public function filtrarPorId($id, $fields = null)
    {
        $fields = $this->prepareFields($fields);

        $dbst = $this->db->prepare(" SELECT $fields FROM " . static::TABLE . " WHERE id = :id ");
        $dbst->bindValue(':id', $id, \PDO::PARAM_STR);

        return $this->execute($dbst);
    }

    public function filtrarPorDescricao($descricao, $fields = null)
    {
        $fields = $this->prepareFields($fields);

        $dbst = $this->db->prepare(" SELECT $fields FROM " . static::TABLE . " WHERE descricao = :descricao ");
        $dbst->bindValue(':descricao', $descricao, \PDO::PARAM_STR);
        $ret = $this->execute($dbst);
        return $ret;
    }

    protected function filtrar($where, $whereValues, $fields = null)
    {
        $fields = $this->prepareFields($fields);

        $order = null;
        if (!empty($this->order)) {

            $ords = [];
            foreach ($this->order as $ord => $dir) {

                $ords[] = "{$ord} {$dir}";
            }

            $order = ' ORDER BY ' . implode(',', $ords);
        }

        $dbst   = $this->db->prepare(" SELECT {$fields} FROM " . static::TABLE . " WHERE {$where} {$order} ");

        if (is_array($whereValues) && !empty($whereValues)) {

            foreach ($whereValues as $param => $value) {

                if (strpos($value, ',') === false) {
                    $typeParam = is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
                    $dbst->bindValue(':' . $param, $value, $typeParam);
                }
            }
        }

        return $this->execute($dbst);
    }

    public function getAll($limit = null)
    {
        if (!empty($limit)) {
            $limit = ' LIMIT ' . (int)$limit;
        }

        $order = null;
        if (!empty($this->order)) {

            $ords = [];
            foreach ($this->order as $ord => $dir) {

                $ords[] = "{$ord} {$dir}";
            }

            $order = ' ORDER BY ' . implode(',', $ords);
        }

        $fields = $this->prepareFields();

        return $this->execute($this->db->prepare(" SELECT $fields FROM " . static::TABLE . " {$order} {$limit} "));
    }

    public function order($column, $direction = 'ASC')
    {
        if (!empty($column) && !empty($direction)) {
            $this->order[$column] = $direction;
        }

        return $this;
    }

    protected function execute($dbst)
    {
        $results = $dbst->execute();

        if ($results === false) {
            throw new \Exception("Não foi possível executar a consulta\n" . implode("\n", $dbst->errorInfo()));
        }

        if ($dbst->rowCount() == 0) {
            return null;
        }

        if ($dbst->rowCount() == 1) {
            return $dbst->fetchObject();
        }

        $res = [];
        while ($row = $dbst->fetch(\PDO::FETCH_ASSOC, \PDO::FETCH_ORI_NEXT)) {
            $res[] = (object)$row;
        }

        return $res;
    }

    protected function prepareFields($fields = null)
    {
        if (empty($fields)) {
            $fields = '*';
        } else {

            if (is_array($fields)) {
                $fields = implode(', ', $fields);
            }
        }

        return $fields;
    }

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

    public function checaTarefaExistenteComArea(AreaModel $obj)
    {
        $dbst = $this->db->prepare(" SELECT melhorias.id FROM Melhorias inner join Area on melhorias.area = area.id WHERE melhorias.area = :id");
        $dbst->bindValue(':id', $obj->getId(), \PDO::PARAM_INT);
        return $this->execute($dbst);
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

    public function __destruct()
    {
    }
}
