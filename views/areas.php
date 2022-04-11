<?php

use DAO\Area;

$areas = Area::getInstance()->order('id')->getAll();

?>
<div class="container" id="cadastroArea">
    <form class="col-sm-12 col-md-6" method="POST" action='../controller/AreaController.php'>
        <h4>Cadastro de Áreas</h4>
        <div class="form-row">
            <input type="hidden" id="campoID" name="campoID" />
            <div class="form-group col-sm-12">
                <label for="descricao">Descrição</label>
                <input type="text" class="form-control" name="descricao" id="descricao" />
                <small id="areaHelp" class="form-text text-muted">Area de negócio da tarefa.</small>
            </div>
        </div>
        <div class="form-group col-md-12">
            <span id="validacaoMsg"></span>
        </div>
        <button type="submit" id="btn_cadastrar" class="btn btn-primary">Salvar</button>
        <button type="reset" id="btn_alterar" class="btn btn-warning">Novo</button>
    </form>
</div>
<div class="container" id="remoçãoAlteracaoArea">
    <table border="1" width="80%">
        <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th>Opções</th>
        </tr>
        <?php
        // Bloco que realiza a geração da tabela dos registros de àreas
        try {
            foreach ($areas as $rs) {
                echo "<tr>";
                echo "<td id='colID'>" . $rs->id . "</td><td id='colDesc" . $rs->id . "'>" . $rs->descricao . "</td>"
                    . "<td><center><button type='button' onclick='alteracao(" . $rs->id . ")' class='btn btn-secondary'> Alterar </button>"
                    . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                    . "<a class='btn-danger' href=\"../controller/AreaController.php?acao=del&id=" . $rs->id . "\">[Excluir]</a></center></td>";
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