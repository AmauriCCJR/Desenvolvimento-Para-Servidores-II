<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include '../../htdocs/Desenvolvimento-Para-Servidores-II/assets/includes/head.php'; ?>
    <link rel="stylesheet" href="../../Desenvolvimento-Para-Servidores-II/assets/css/relatorio.css">
    <title>Relatórios</title>
</head>

<body>
    <?php include '../Desenvolvimento-Para-Servidores-II/assets/includes/header.php' ?>

    <main>
        <div id="conteudoPrincipal">
            <div class="container mt-4">
                <h2 style="color: #000;">Relatório de Reservas</h2>

                <div class="row align-items-end g-3">
                    <div class="col-auto">
                        <label for="dataRelatorio" style="color: #000;">Data da Reserva:</label>
                        <input type="date" id="dataRelatorio" class="form-control">
                    </div>
                    <div class="col-auto d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-primary btnAcao" onclick="gerarRelatorio()">Gerar Relatório</button>
                        <button class="btn btn-outline-primary btnAcao" onclick="imprimirRelatorio('tabelaRelatorio')">Imprimir Relatório de Chaves</button>
                        <button class="btn btn-outline-primary btnAcao" onclick="imprimirRelatorioVisualizacao()">Imprimir Relatório de Visualização</button>
                        <button class="btn btn-outline-primary btnAcao" onclick="imprimirRelatorioTV()">Mostrar na TV</button>
                    </div>
                </div>

                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered mt-3" id="tabelaRelatorio">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Sala</th>
                                <th>Turma</th>
                                <th>Docente</th>
                                <th>Horário</th>
                                <th>Retirada</th>
                                <th>Entrega</th>
                                <th>Visto</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="relatorioTV" style="display: none;"></div>
    </main>




</body>
<script src="../assets/js/relatorio.js"></script>

</html>