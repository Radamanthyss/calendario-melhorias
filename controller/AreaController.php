<?php
require_once '../dao/Area.php';
require_once '../model/AreaModel.php';

use DAO\Area;
use MODEL\AreaModel;


if (isset($_POST["campoID"]) && $_POST["campoID"] > 0) {
    $idF = stripslashes(htmlspecialchars(filter_input(INPUT_POST, 'campoID', FILTER_SANITIZE_NUMBER_INT)));
} else {
    $idF = 0;
}

if (isset($_POST["descricao"])) {
    $descricao = stripslashes(htmlspecialchars(filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_STRING)));
}

if (!$_GET["acao"] && $idF == 0) {
    $area = new AreaModel();
    $area->setDescricao($descricao);
    Area::getInstance()->salvarArea($area);
    header("location: ../index.php?path=areas");
}

if (!$_GET["acao"] && $idF != 0) {
    try {
        $area = new AreaModel();
        $area->setId($idF);
        $area->setDescricao($descricao);
        Area::getInstance()->atualizarArea($area);
        header("location: ../index.php?path=areas");
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}

if ($_GET["acao"] == "del" && $_GET['id'] > 0) {
    try {
        $area = new AreaModel();
        $area->setId($_GET['id']);
        Area::getInstance()->removerArea($area);
        header("location: ../index.php?path=areas");
    } catch (PDOException $erro) {
        echo "Erro: " . $erro->getMessage();
    }
}
