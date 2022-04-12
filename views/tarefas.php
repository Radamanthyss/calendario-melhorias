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
        <textarea class="form-control" name="descricao" id="descricao" required></textarea>
        <small id="areaHelp" class="form-text text-muted">Descrição do negócio da tarefa.</small>
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-sm-12 col-md-5">
        <label for="prazo_acordado">Prazo Acordado</label>
        <input type="date" class="form-control" id="prazo_acordado" name="prazo_acordado" required>
      </div>
      <div class="form-group col-sm-12 col-md-5">
        <label for="prazo_legal">Prazo Legal</label>
        <input type="date" class="form-control" id="prazo_legal" name="prazo_legal">
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
      <div class="form-group col-sm-6 col-md-8">
        <label for="demanda_legal">Demanda Legal</label>
        <input type="checkbox" class="form-control" id="demanda_legal" name="demanda_legal">
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
      <th>Demanda Legal</th>
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

        if ($rs->demanda_legal == 1) {
          $demanda = 'SIM';
        } else {
          $demanda = 'NÃO';
        }

        echo "<tr>";
        echo "<td>" . $rs->id . "</td><td id='colDesc" . $rs->id . "' >" . $rs->descricao . "</td>"
          . "<td> " . Melhoria::getInstance()->retornaDescArea($rs->area)->descricao . "</td><td id='colPa" . $rs->id . "'>" . $pa . "</td>"
          . "<td id='colPl" . $rs->id . "'> " . $pl . "</td><td><input type='hidden' id='colGrav" . $rs->id . "' value='" . $rs->gravidade . "' />" . $descGrav . "</td>"
          . "<td><input type='hidden' id='colUrge" . $rs->id . "' value='" . $rs->urgencia . "' />" . $descUrge . "</td>"
          . "<td><input type='hidden' id='colTend" . $rs->id . "' value='" . $rs->tendencia . "' />" . $descTend . "</td>"
          . "<td id='colDema" . $rs->id . "'>" . $demanda . "</td>"
          . "<td><center><button type='button' onclick='alteracao(" . $rs->id . "," . $rs->area . ")' class='btn btn-secondary'> Alterar </button>"
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
    //declaração e atribuição de variaveis responsaveis por controlar os dados pra popular o form e possibilitar o update!
    var tdPa = document.getElementById("colPa" + id).textContent;
    var tdPl = document.getElementById("colPl" + id).textContent;
    var tdGrav = document.getElementById("colGrav" + id).value;
    var tdUrge = document.getElementById("colUrge" + id).value;
    var tdTend = document.getElementById("colTend" + id).value;
    var tdDema = document.getElementById("colDema" + id).textContent;


    document.getElementById("campoID").value = id;
    document.getElementById("descricao").value = document.getElementById("colDesc" + id).textContent;
    document.getElementById("prazo_acordado").value = FormataStringData(tdPa);
    document.getElementById("area").selectedIndex = area;
    document.getElementById("prazo_legal").value = tdPl == " Não Informado" ? "" : FormataStringData(tdPl);
    document.getElementById("gravidade").selectedIndex = tdGrav == "" ? "" : tdGrav;
    document.getElementById("urgencia").selectedIndex = tdUrge == "" ? "" : tdUrge;
    document.getElementById("tendencia").selectedIndex = tdTend == "" ? "" : tdTend;
    document.getElementById("demanda_legal").checked = tdDema == "SIM" ? true : false;
  }

  function FormataStringData(data) {
    var dia = data.split("/")[0];
    var mes = data.split("/")[1];
    var ano = data.split("/")[2];

    return ano + '-' + ("0" + mes).slice(-2) + '-' + ("0" + dia).slice(-2);
    // Utilizo o .slice(-2) para garantir o formato com 2 digitos.
  }
</script>