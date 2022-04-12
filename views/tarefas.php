<?php

use DAO\Melhoria;
use DAO\Area;
use DAO\Urgencia;
use DAO\Gravidade;
use DAO\Tendencia;


$areas = Area::getInstance()->order('id')->getAll();
$gravidades = Gravidade::getInstance()->order('id')->getAll();
$urgencias = Urgencia::getInstance()->order('id')->getAll();
$tendencias = Tendencia::getInstance()->order('id')->getAll();
$melhorias = Melhoria::getInstance()->order('id')->getAll();
$meses = [];

for ($m = 1; $m <= 12; $m++) {
  $meses[] = (object)[
    'id'         => $m,
    'descricao'  => date('F', mktime(0, 0, 0, $m)),
  ];
}

?>
<div class="container" id="cadastroTarefa">
  <form id="formTarefa" class="col-sm-12 col-md-6" method="POST" action="../controller/MelhoriaController.php?acao=save">
    <h4>Cadastro de Tarefas</h4>
    <div class="form-row">
      <div class="form-group col-sm-12">
        <label for="area">Áreas</label>
        <select class="form-control" id="area" name="area" required>
          <option value="">Selecione</option>
          <?php foreach ($areas as $area) : ?>
            <option value="<?php echo $area->id; ?>"><?php echo $area->descricao; ?></option>
          <?php endforeach; ?>
        </select>
        <small id="areaHelp" class="form-text text-muted">Area de negócio da tarefa.</small>
      </div>
    </div>

    <div class="form-row">
      <input type="hidden" id="campoID" name="campoID" />
      <div class="form-group col-sm-12">
        <label for="descricao">Descrição</label>
        <input type="text" class="form-control" name="descricao" id="descricao" required />
        <small id="areaHelp" class="form-text text-muted">Descrição do negócio da tarefa.</small>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-sm-12 col-md-5">
        <label for="prazo_acordado">Prazo Acordado</label>
        <input type="text" class="form-control" id="prazo_acordado" name="prazo_acordado" required>
      </div>
      <div class="form-group col-sm-12 col-md-5">
        <label for="prazo_legal">Prazo Legal</label>
        <input type="text" class="form-control" id="prazo_legal" name="prazo_legal">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-sm-7 col-md-3">
        <label for="gravidade">Gravidade</label>
        <select class="form-control" id="gravidade" name="gravidade">
          <option value="">Selecione</option>
          <?php foreach ($gravidades as $gravidade) : ?>
            <option value="<?php echo $gravidade->id ?>"><?php echo $gravidade->descricao ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group col-sm-7 col-md-3">
        <label for="urgencia">Urgência</label>
        <select class="form-control" id="urgencia" name="urgencia">
          <option value="">Selecione</option>
          <?php foreach ($urgencias as $urgencia) : ?>
            <option value="<?php echo $urgencia->id ?>"><?php echo $urgencia->descricao ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group col-sm-7 col-md-3">
        <label for="tendencia">Tendência</label>
        <select class="form-control" id="tendencia" name="tendencia">
          <option value="">Selecione</option>
          <?php foreach ($tendencias as $tendencia) : ?>
            <option value="<?php echo $tendencia->id ?>"><?php echo $tendencia->descricao ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-12">
        <span id="validacaoMsg"></span>
      </div>
    </div>
    <div class="form-row">
      <button type="submit" id="btn_cadastrar" class="btn btn-primary">Salvar</button>
      <button type="reset" id="btn_limpar" class="btn btn-warning">Novo</button>
    </div>
  </form>
</div>
<div class="container" id="remoçãoAlteracaoArea">
  <table border="1" width="80%">
    <tr>
      <th>ID</th>
      <th>Descrição</th>
      <th>Àrea</th>
      <th>Prazo Acordado</th>
      <th>Prazo Legal</th>
      <th>Gravidade</th>
      <th>Urgência</th>
      <th>Tendência</th>
      <th>Opções</th>
    </tr>
    <?php
    // Bloco que realiza a geração da tabela dos registros de àreas
    try {
      foreach ($melhorias as $rs) {

        $descGrav = Melhoria::getInstance()->retornaDescGravidade($rs->gravidade);
        $descUrge = Melhoria::getInstance()->retornaDescUrgencia($rs->urgencia);
        $descTend = Melhoria::getInstance()->retornaDescTendencia($rs->tendencia);

        if ($descGrav == "") {
          $descGrav = "Não informado";
        } else {
          $descGrav = Melhoria::getInstance()->retornaDescGravidade($rs->gravidade)->descricao;
        }

        if ($descUrge == "") {
          $descUrge = "Não informado";
        } else {
          $descUrge = Melhoria::getInstance()->retornaDescUrgencia($rs->urgencia)->descricao;
        }

        if ($descTend == "") {
          $descTend = "Não informado";
        } else {
          $descTend = Melhoria::getInstance()->retornaDescTendencia($rs->tendencia)->descricao;
        }

        $pa = date("d/m/Y", strtotime($rs->prazo_acordado));

        if ($rs->prazo_legal == "") {
          $pl = "Não Informado";
        } else {
          $pl = date("d/m/Y", strtotime($rs->prazo_legal));
        }


        echo "<tr>";
        echo "<td>" . $rs->id . "</td><td id='colDesc" . $rs->id . "' >" . $rs->descricao . "</td>"
          . "<td> " . Melhoria::getInstance()->retornaDescArea($rs->area)->descricao . "</td><td id='colPa" . $rs->id . "'>" . $pa . "</td>"
          . "<td id='colPl" . $rs->id . "'> " . $pl . "</td><td id='colGrav" . $rs->id . "'>" . $descGrav . "</td>"
          . "<td id='colUrge" . $rs->id . "'>" . $descUrge . "</td>"
          . "<td id='colTend" . $rs->id . "'>" . $descTend . "</td>"
          . "<td><center><button type='button' onclick='alteracao(" . $rs->id . "," . $rs->area . ")' class='btn btn-secondary'> Alterar </button>"
          . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
          . "<a class='btn-danger' href=\"../controller/MelhoriaController.php?acao=del&id=" . $rs->id . "\">[Excluir]</a></center></td>";
        echo "</tr>";
      }
    } catch (PDOException $erro) {
      echo "Erro: " . $erro->getMessage();
    }
    ?>
  </table>
</div>
<script type="text/javascript">
  function alteracao(id, area) {
    document.getElementById("campoID").value = id;
    document.getElementById("descricao").value = document.getElementById("colDesc" + id).textContent;
    document.getElementById("prazo_acordado").value = document.getElementById("colPa" + id).textContent;
    document.getElementById("area").selectedIndex = area;
    document.getElementById("prazo_legal").value = document.getElementById("colPl" + id).textContent == " Não Informado" ? "" : document.getElementById("colPl" + id).textContent;
    document.getElementById("gravidade").selectedIndex = document.getElementById("colGrav" + id).textContent == "Não Informado" ? "" : document.getElementById("colGrav" + id).textContent;
    document.getElementById("urgencia").selectedIndex = document.getElementById("colUrge" + id).textContent == "Não Informado" ? "" : document.getElementById("colUrge" + id).textContent;
    document.getElementById("tendencia").selectedIndex = document.getElementById("colTend" + id).textContent == "Não Informado" ? "" : document.getElementById("colTend" + id).textContent;

  }
</script>