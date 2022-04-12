<?php

use DAO\Melhoria;
use DAO\Area;
use DAO\Urgencia;
use DAO\Gravidade;
use DAO\Tendencia;


$areas = Area::getInstance()->order('descricao')->getAll();
$gravidades = Gravidade::getInstance()->order('descricao')->getAll();
$urgencias = Urgencia::getInstance()->order('descricao')->getAll();
$tendencias = Tendencia::getInstance()->order('descricao')->getAll();
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
        <input type="date" class="form-control" id="prazo_acordado" name="prazo_acordado" required>
      </div>
      <div class="form-group col-sm-12 col-md-5">
        <label for="prazo_legal">Prazo Legal</label>
        <input type="date" class="form-control" id="prazo_legal" name="prazo_legal" >
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
      <th>Opções</th>
    </tr>
    <?php
    // Bloco que realiza a geração da tabela dos registros de àreas
    try {
      foreach ($melhorias as $rs) {
        echo "<tr>";
        echo "<td id='colID'>" . $rs->id . "</td><td id='colDesc" . $rs->id . "'>" . $rs->descricao . "</td>"
          . "<td id='colArea'> " . Melhoria::getInstance()->retornaDescArea($rs->area)->descricao . "</td><td id=''>" . $rs->prazo_acordado . "</td>"
          . "<td><center><button type='button' onclick='alteracao(" . $rs->id . ")' class='btn btn-secondary'> Alterar </button>"
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
  function alteracao(id) {
    document.getElementById("campoID").value = id;
    document.getElementById("descricao").value = document.getElementById("colDesc" + id).textContent;
  }
</script>