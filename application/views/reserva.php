<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include '../../htdocs/Desenvolvimento-Para-Servidores-II/assets/includes/head.php'; ?>
    <link rel="stylesheet" href="../../Desenvolvimento-Para-Servidores-II/assets/css/professor.css">
    <title>Reserva</title>
</head>

<body>
    <?php include '../Desenvolvimento-Para-Servidores-II/assets/includes/header.php' ?>

    <main>
        <section class="secao4" id="cadastroMapeamento">
            <div id="btnCadastroModal">
                <input type="text" id="inputPesquisa" class="form-control" placeholder="Pesquisar" onkeyup="filtrarTabela()">
                <button class="btn btn-outline-primary btnAcao modalBtn" id="botaoModal" type="button" data-bs-toggle="modal" data-bs-target="#cadastroMapeamentoModal">Cadastrar nova Reserva</button>
            </div>
        </section>
    </main>

    <div class="modal fade" id="cadastroMapeamentoModal" tabindex="-1" role="dialog" aria-labelledby="cadastroMapeamentoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroMapeamentoModalLabel">Cadastrar Nova Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form id="formCadastroMapeamento" method="post">
                    <div class="modal-body">
                        <div class="form-group row mb-2">
                            <div class="col-md-12">
                                <label for="selectSalas" class="col-form-label">Sala</label>
                                <select id="selectSalas" name="selectSalas" class="form-control" required>
                                    <option value="">Selecione uma sala</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="selectTurma" class="col-form-label">Turma</label>
                                <select id="selectTurma" name="selectTurma" class="form-control" required>
                                    <option value="">Selecione uma turma</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-md-12">
                                <label for="selectProfessor" class="col-form-label">Professor</label>
                                <select id="selectProfessor" name="selectProfessor" class="form-control" required>
                                    <option value="">Selecione um docente</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-md-6">
                                <label for="dataFim" class="col-form-label">Data Final</label>
                                <input type="date" id="dataFim" name="dataFim" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label for="selectHorario" class="col-form-label">Horário</label>
                                <select id="selectHorario" name="selectHorario" class="form-control" required>
                                    <option value="">Selecione um horário</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Cadastrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form id="formEditMapeamento" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="editId">
                        <div class="form-group mb-2">
                            <label for="editSelectSalas" class="col-form-label">Sala</label>
                            <select id="editSelectSalas" name="editSelectSalas" class="form-control" required>
                                <option value="">Selecione uma sala</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="editSelectTurma" class="col-form-label">Turma</label>
                            <select id="editSelectTurma" name="editSelectTurma" class="form-control" required>
                                <option value="">Selecione uma turma</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="editSelectProfessor" class="col-form-label">Professor</label>
                            <select id="editSelectProfessor" name="editSelectProfessor" class="form-control" required>
                                <option value="">Selecione um Professor</option>
                            </select>
                        </div>
                        <div class="form-group row mb-2">
                            <div class="col-md-5">
                                <label for="dataEditar" class="col-form-label">Data</label>
                                <input type="date" id="dataEditar" name="dataEditar" class="form-control" required>
                            </div>
                            <div class="col-md-7">
                                <label for="editSelectHorario" class="col-form-label">Horário</label>
                                <select id="editSelectHorario" name="editSelectHorario" class="form-control" required>
                                    <option value="">Selecione um Horário</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <section id="mostrarCadastro">
        <div class="table-responsive tabela-scroll">
            <div id="spinner" style="display: none; text-align: center; margin-top: 10px">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Carregando...</span>
                </div>
            </div>
            <table class="table table-condensed table-hover">
                <thead>
                    <tr>
                        <th>Sala</th>
                        <th>Descrição da sala</th>
                        <th>Turma</th>
                        <th>Docente</th>
                        <th>Data</th>
                        <th>Horário</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="conteudo-Mapeamento"></tbody>
            </table>
        </div>
    </section>

    <script src="../assets/js/reserva.js"></script>
</body>
</html>