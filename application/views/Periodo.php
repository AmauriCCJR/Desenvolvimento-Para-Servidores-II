<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include '../../htdocs/Desenvolvimento-Para-Servidores-II/assets/includes/head.php'; ?>
    <link rel="stylesheet" href="../../Desenvolvimento-Para-Servidores-II/assets/css/professor.css">
    <title>Periodo</title>
</head>

<body>
    <?php include '../Desenvolvimento-Para-Servidores-II/assets/includes/header.php' ?>


    <main>
        <section class="secao4" id="cadastroPeriodo">
            <div id="btnCadastroModal">
                <input type="text" id="inputPesquisa" class="form-control" placeholder="Pesquisar" onkeyup="filtrarTabela()">

                <button class="btn btn-outline-primary btnAcao modalBtn" id="botaoModal" type="button" data-bs-toggle="modal" 
                data-bs-target="#cadastroPeriodoModal">Cadastrar novo Periodo</button>
            </div>
        </section>
    </main>

    <div class="modal fade" id="cadastroPeriodoModal" tabindex="-1" role="dialog" aria-labelledby="cadastroPeriodoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroPeriodoModalLabel">Cadastrar Novo Periodo</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCadastroPeriodo" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="codigo" class="col-form-label">Código</label>
                            <input type="number" id="codigo" name="codigo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="descricao" class="col-form-label">Descrição</label>
                            <input type="text" id="descricao" name="descricao" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="horaIni" class="col-form-label">Hora Inicial</label>
                            <input type="time" id="horaIni" name="horaIni" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="horaFim" class="col-form-label">Hora Final</label>
                            <input type="time" id="horaFim" name="horaFim" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnAcao" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btnAcao" onclick="cadastro();">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Periodo</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditPeriodo" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="editId">
                        <div class="form-group">
                            <label for="editDescricao">Descrição</label>
                            <input type="text" id="editDescricao" name="editDescricao" class="form-control" required>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="editHoraIni" class="col-form-label">Hora Inicial</label>
                                <input type="time" id="editHoraIni" name="editHoraIni" class="form-control" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="editHoraFim" class="col-form-label">Hora Final</label>
                                <input type="time" id="editHoraFim" name="editHoraFim" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnAcao" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btnAcao" onclick="editarPeriodo();">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <section id="mostrarCadastro">
            <div class="table-responsive">
                <table class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Hora Inicial</th>
                            <th>Hora Final</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="conteudo-Periodo">

                    </tbody>
                </table>
            </div>
        </section>

    <script src="../assets/js/periodo.js"></script>
</body>

</html>