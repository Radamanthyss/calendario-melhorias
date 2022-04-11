<?php

use DAO\Area;
use DAO\Melhoria;

$areas = Area::getInstance()->order('id')->getAll();
$meses = [];

for ($m = 1; $m <= 12; $m++) {
    $meses[] = (object)[
        'id'         => $m,
        'descricao'  => date('F', mktime(0, 0, 0, $m)),
    ];
}

?>

<div class="container" id="cadastroTarefa">
    <form id="formTarefa" class="col-sm-12 col-md-6" method="POST" action="../controller/MelhoriaController.php">
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
                <select class="form-control" id="prazo_acordado" name="prazo_acordado" required>
                    <option value="">Selecione</option>
                    <?php foreach ($meses as $mes) : ?>
                        <option value="<?php echo $mes->id; ?>"><?php echo $mes->descricao; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-sm-12 col-md-5">
                <label for="prazo_legal">Prazo Legal</label>
                <select class="form-control" id="prazo_legal" name="prazo_legal" required>
                    <option value="">Selecione</option>
                    <?php foreach ($meses as $mes) : ?>
                        <option value="<?php echo $mes->id; ?>"><?php echo $mes->descricao; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-7 col-md-3">
                <label for="gravidade">Gravidade</label>
                <select class="form-control" id="gravidade" name="gravidade" required>
                    <option value="">Selecione</option>
                    <?php for ($i = 0; $i <= 5; $i++) : ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group col-sm-7 col-md-3">
                <label for="urgencia">Urgência</label>
                <select class="form-control" id="urgencia" name="urgencia" required>
                    <option value="">Selecione</option>
                    <?php for ($i = 0; $i <= 5; $i++) : ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group col-sm-7 col-md-3">
                <label for="tendencia">Tendência</label>
                <select class="form-control" id="tendencia" name="tendencia" required>
                    <option value="">Selecione</option>
                    <?php for ($i = 0; $i <= 5; $i++) : ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
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
<!-- <script type="text/javascript">
    document.querySelector("form").addEventListener("submit", function(e) {
        if (!isValid) {
            e.preventDefault(); //stop form from submitting
        }
        //do whatever an submit the form
        var form = document.querySelector('form');
        var fields = form.elements;
        var qryString = '?path=agenda';
        var filtroMeses = [];

        for (let field of fields) {

            /* if (field.value > 0) {

                switch (field.id) {
                    case 'area':

                        qryString += '&';
                        qryString += field.id;
                        qryString += '=';
                        qryString += field.value;
                        break;

                    case 'mes_inicio':
                    case 'mes_fim':
                        filtroMeses.push(field.value);
                        break;
                }
            } */
        }

        //if (filtroMeses.length > 0) {
        //    qryString += '&meses='
        //    qryString += filtroMeses.join('-');
        //}

        prazoAcordado = fields[3].value;
        prazoLegal = fields[4].value;
        console.log(fields);

        if (prazoAcordado > prazoLegal) { // CORREÇÃO DO ERRO Validaçao de período      
            document.getElementById("validacaoMsg").textContent = "O Prazo Acordado informado é maior que o prazo legal!";
        } else {
            //location.href = qryString;
            //form.submit();            
        }
    });

    function alteracao(id) {
        document.getElementById("campoID").value = id;
        document.getElementById("descricao").value = document.getElementById("colDesc" + id).textContent;
    }
</script> -->