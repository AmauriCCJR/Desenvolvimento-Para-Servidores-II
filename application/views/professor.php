<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include '../../htdocs/Desenvolvimento-Para-Servidores-II/assets/includes/head.php'; ?>
    <link rel="stylesheet" href="../../Desenvolvimento-Para-Servidores-II/assets/css/professor.css">
    <title>Professor</title>
</head>

<body>
    <?php include '../Desenvolvimento-Para-Servidores-II/assets/includes/header.php' ?>


    <main>
        <section class="secao4" id="cadastroProfessor">
            <div id="btnCadastroModal">
                <input type="text" id="inputPesquisa" class="form-control" placeholder="Pesquisar" onkeyup="filtrarTabela()">
                <button class="btn btn-outline-primary btnAcao modalBtn" id="botaoModal" type="button" data-bs-toggle="modal" data-bs-target="#cadastroProfessorModal">Cadastrar novo docente</button>
            </div>
        </section>

        <section id="mostrarCadastro">
            <div class="table-responsive">
                <table class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Docente</th>
                            <th>CPF</th>
                            <th>Tipo</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody id="conteudo-professor">

                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Modal Cadastro -->
    <div class="modal fade" id="cadastroProfessorModal" tabindex="-1" role="dialog" aria-labelledby="cadastroProfessorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cadastroProfessorModalLabel">Cadastrar Novo Docente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form id="formCadastroProfessor" method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="codigo">Código</label>
                            <input type="number" id="codigo" name="codigo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="cpf" class="col-form-label">CPF</label>
                                <input type="number" id="cpf" name="cpf" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="tipo" class="col-form-label">Tipo</label>
                            <select name="tipo" id="tipo" class="form-control" required>
                                <option value="">Selecione</option>
                                <option value="F">Funcionário</option>
                                <option value="C">Carta Convite</option>
                            </select>
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

    <!-- Modal Editar -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Docente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <form id="formEditProfessor" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="editId">
                        <div class="form-group">
                            <label for="editNome">Nome</label>
                            <input type="text" id="editNome" name="editNome" class="form-control" required>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="editCpf" class="col-form-label">CPF</label>
                                <input type="number" id="editCpf" name="editCpf" class="form-control" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="editTipo" class="col-form-label">Tipo</label>
                                <select name="editTipo" id="editTipo" class="form-control" required>
                                    <option value="">Selecione</option>
                                    <option value="F">Funcionario</option>
                                    <option value="C">Carta Convite</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btnAcao" data-bs-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btnAcao" onclick="editarProfessor();">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script src="../assets/js/professor.js"></script>
</body>

</html>