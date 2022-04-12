<?php

namespace MODEL;

class AreaModel
{
    public $id = 0;
    public $descricao = "";

    public function __contruct($desc)
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($desc)
    {
        $this->descricao = $desc;
    }
}
