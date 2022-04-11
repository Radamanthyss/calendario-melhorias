<?php

namespace MODEL;

class AreaModel
{
    public $id = 0;
    public $descricao = "";

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
