<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include '../../htdocs/Desenvolvimento-Para-Servidores-II/assets/includes/head.php'; ?>
    <link rel="stylesheet" href="../../Desenvolvimento-Para-Servidores-II/assets/css/professor.css">
    <title>Turma</title>
</head>

<body>
    <?php include '../Desenvolvimento-Para-Servidores-II/assets/includes/header.php' ?>


    <main>
        <section class="secao4" id="cadastroTurma">
            <div id="btnCadastroModal">
                <input type="text" id="inputPesquisa" class="form-control" placeholder="Pesquisar" onkeyup="filtrarTabela()">

                <button class="btn btn-outline-primary btnAcao modalBtn" id="botaoModal" type="button" data-bs-toggle="modal" 
                data-bs-target="#cadastroTurmaModal">Cadastrar nova turma</button>
            </div>
        </section>
    </main>

    <div class="modal fade" id="cadastroTurmaModal" tabindex="-1" role="dialog" aria-labelledby="cadastroTurmaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroTurmaModalLabel">Cadastrar Nova Turma</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCadastroTurma" method="post">
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
                            <label for="capacidade" class="col-form-label">Capacidade</label>
                            <input type="number" id="capacidade" name="capacidade" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="dataInicio" class="col-form-label">Data de Inicio</label>
                            <input type="date" id="dataInicio" name="dataInicio" class="form-control" required>
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
                    <h5 class="modal-title" id="editModalLabel">Editar turma</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEditTurma" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="editId">
                        <div class="form-group">
                            <label for="editDescricao">Descrição</label>
                            <input type="text" id="editDescricao" name="editDescricao" class="form-control" required>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="editCapacidade" class="col-form-label">Capacidade</label>
                                <input type="number" id="editCapacidade" name="editCapacidade" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="editDataInicio" class="col-form-label">Data de Inicio</label>
                                <input type="date" id="editDataInicio" name="editDataInicio" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnAcao" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btnAcao" onclick="editarTurma();">Salvar</button>
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
                            <th>Turma</th>
                            <th>Descrição</th>
                            <th>Capacidade</th>
                            <th>Data de Inicio</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="conteudo-Turma">

                    </tbody>
                </table>
            </div>
        </section>

    <script src="../assets/js/turma.js"></script>
</body>

</html>