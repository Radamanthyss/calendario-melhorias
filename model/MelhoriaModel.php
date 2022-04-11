<?php

namespace MODEL;

class MelhoriaModel
{
    public $id = 0;
    public $descricao = "";
    public $tarefa = "";
    public $demanda_legal = false;
    public $prazo_acordado;
    public $prazo_legal;
    public $gravidade = 0;
    public $urgencia = 0;
    public $tendencia = 0;
    public $area;

    public function __contruct($desc)
    {
    }

    public function getAreaModelId()
    {
        return $this->id;
    }

    public function setAreaModelId($id)
    {
        $this->id = $id;
    }

    public function getAreaModelDescricao()
    {
        return $this->descricao;
    }

    public function setAreaModelDescricao($desc)
    {
        $this->descricao = $desc;
    }
}
