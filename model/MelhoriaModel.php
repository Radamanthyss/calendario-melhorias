<?php

namespace MODEL;

class MelhoriaModel
{
    public $id;
    public $descricao;
    public $tarefa;
    public $demanda_legal =false;
    public $prazo_acordado;
    public $prazo_legal;
    public $gravidade;
    public $urgencia;
    public $tendencia;
    public $area;

    public function __contruct()
    {
    }    

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of descricao
     */ 
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * Set the value of descricao
     *
     * @return  self
     */ 
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;

        return $this;
    }

    /**
     * Get the value of tarefa
     */ 
    public function getTarefa()
    {
        return $this->tarefa;
    }

    /**
     * Set the value of tarefa
     *
     * @return  self
     */ 
    public function setTarefa($tarefa)
    {
        $this->tarefa = $tarefa;

        return $this;
    }

    /**
     * Get the value of demanda_legal
     */ 
    public function getDemanda_legal()
    {
        return $this->demanda_legal;
    }

    /**
     * Set the value of demanda_legal
     *
     * @return  self
     */ 
    public function setDemanda_legal($demanda_legal)
    {
        $this->demanda_legal = $demanda_legal;

        return $this;
    }

    /**
     * Get the value of prazo_acordado
     */ 
    public function getPrazo_acordado()
    {
        return $this->prazo_acordado;
    }

    /**
     * Set the value of prazo_acordado
     *
     * @return  self
     */ 
    public function setPrazo_acordado($prazo_acordado)
    {
        $this->prazo_acordado = $prazo_acordado;

        return $this;
    }

    /**
     * Get the value of prazo_legal
     */ 
    public function getPrazo_legal()
    {
        return $this->prazo_legal;
    }

    /**
     * Set the value of prazo_legal
     *
     * @return  self
     */ 
    public function setPrazo_legal($prazo_legal)
    {
        $this->prazo_legal = $prazo_legal;

        return $this;
    }

    /**
     * Get the value of gravidade
     */ 
    public function getGravidade()
    {
        return $this->gravidade;
    }

    /**
     * Set the value of gravidade
     *
     * @return  self
     */ 
    public function setGravidade($gravidade)
    {
        $this->gravidade = $gravidade;

        return $this;
    }

    /**
     * Get the value of urgencia
     */ 
    public function getUrgencia()
    {
        return $this->urgencia;
    }

    /**
     * Set the value of urgencia
     *
     * @return  self
     */ 
    public function setUrgencia($urgencia)
    {
        $this->urgencia = $urgencia;

        return $this;
    }

    /**
     * Get the value of tendencia
     */ 
    public function getTendencia()
    {
        return $this->tendencia;
    }

    /**
     * Set the value of tendencia
     *
     * @return  self
     */ 
    public function setTendencia($tendencia)
    {
        $this->tendencia = $tendencia;

        return $this;
    }

    /**
     * Get the value of area
     */ 
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set the value of area
     *
     * @return  self
     */ 
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }
}
