<?php
require_once '../dao/Melhoria.php';
require_once '../model/MelhoriaModel.php';

use DAO\Melhoria;
use MODEL\MelhoriaModel;



$id = stripslashes(htmlspecialchars(filter_input(INPUT_POST, 'campoID', FILTER_SANITIZE_NUMBER_INT)));
$area = stripslashes(htmlspecialchars(filter_input(INPUT_POST, 'area', FILTER_SANITIZE_NUMBER_INT)));
$descricao = stripslashes(htmlspecialchars(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING)));
$prazo_acordado = stripslashes(htmlspecialchars(filter_input(INPUT_POST, 'prazo_acordado', FILTER_SANITIZE_STRING)));
$prazo_legal = stripslashes(htmlspecialchars(filter_input(INPUT_POST, 'prazo_legal', FILTER_SANITIZE_STRING)));
$gravidade = stripslashes(htmlspecialchars(filter_input(INPUT_POST, 'gravidade', FILTER_SANITIZE_NUMBER_INT)));
$urgencia = stripslashes(htmlspecialchars(filter_input(INPUT_POST, 'urgencia', FILTER_SANITIZE_NUMBER_INT)));
$tendencia = stripslashes(htmlspecialchars(filter_input(INPUT_POST, 'tendencia', FILTER_SANITIZE_NUMBER_INT)));


if ($_GET["acao"] == "save") {
    $melhoria = new MelhoriaModel();
    if ($id > 0) {
        $melhoria->setId($id);
    }
    $melhoria->setArea($area);
    $melhoria->setDescricao($descricao);
    $melhoria->setPrazo_acordado($prazo_acordado);
    $melhoria->setPrazo_legal($prazo_legal);
    $melhoria->setGravidade($gravidade);
    $melhoria->setUrgencia($urgencia);
    $melhoria->setTendencia($tendencia);

    Melhoria::getInstance()->salvarAtualizarMelhoria($melhoria);
    //header("location: ../index.php?path=tarefas");
}

if ($_GET["acao"] == "del" && $_GET['id'] > 0) {
    try {
        $melhoria = new MelhoriaModel();
        $melhoria->setId($_GET['id']);
        Melhoria::getInstance()->removerMelhoria($melhoria);
        header("location: ../index.php?path=tarefas");
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}
