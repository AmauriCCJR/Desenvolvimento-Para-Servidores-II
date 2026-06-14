<!DOCTYPE html>
<html lang="pt-br">

<head>
    <?php include '../../htdocs/Desenvolvimento-Para-Servidores-II/assets/includes/head.php'; ?>
    <link rel="stylesheet" href="../../Desenvolvimento-Para-Servidores-II/assets/css/sala.css">
    <title>Sala</title>
</head>

<body>
    <header>
        <div id="headerMenu">
            <a href="../Funcoes/indexPagina">
                <h1 id="headerTitle">Mapeamento de Salas</h1>
            </a>

            <nav class="navbar navbar-expand-lg navbar-dark">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="../funcoes/abreSala">Sala de Aula</a></li>
                        <li class="nav-item"><a class="nav-link" href="../funcoes/abreProfessor">Docente</a></li>
                        <li class="nav-item"><a class="nav-link" href="../funcoes/abreTurma">Turma</a></li>
                        <li class="nav-item"><a class="nav-link" href="../funcoes/abrePeriodo">Periodo</a></li>
                        <li class="nav-item"><a class="nav-link" href="../funcoes/abreMapa">Reservas</a></li>
                        <li class="nav-item"><a class="nav-link" href="../funcoes/abreRelatorio">Relatorio</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>


    <main>
        <section class="secao4" id="cadastroSala">
            <div id="btnCadastroModal">
                <input type="text" id="inputPesquisa" class="form-control" placeholder="Pesquisar" onkeyup="filtrarTabela()">
                <button class="btn btn-outline-primary btnAcao modalBtn" id="botaoModal" type="button" data-bs-toggle="modal" data-bs-target="#cadastroSalaModal">Cadastrar Nova Sala</button>
            </div>

            <div class="modal fade" id="cadastroSalaModal" tabindex="-1" role="dialog" aria-labelledby="cadastroSalaModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cadastroSalaModalLabel">Cadastrar nova Sala</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    <form id="formCadastroSala" method="post" class="modal-content">
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <label for="codigo" class="col-form-label">Numero</label>
                                    <input type="number" id="codigo" name="codigo" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="andar" class="col-form-label">Andar</label>
                                    <select name="andar" id="andar" class="form-control" required>
                                        <option value="selecione">Selecione</option>
                                        <option value="9">Térreo</option>
                                        <option value="1">Primeiro</option>
                                        <option value="2">Segundo</option>
                                        <option value="3">Terceiro</option>
                                        <option value="4">Quarto</option>
                                        <option value="5">Quinto</option>
                                        <option value="6">Sexto</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="capacidade" class="col-form-label">Capacidade</label>
                                    <input type="number" id="capacidade" name="capacidade" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <input type="text" id="descricao" name="descricao" class="form-control" required>
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
                            <h5 class="modal-title" id="editModalLabel">Editar Sala</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Fechar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="formEditSala" method="post">
                            <div class="modal-body">
                                <input type="hidden" id="editId" name="editId">
                                <div class="form-group">
                                    <label for="editDescricao">Descrição</label>
                                    <input type="text" id="editDescricao" name="descricao" class="form-control" required>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <label for="editAndar">Andar</label>
                                        <select name="andar" id="editAndar" class="form-control" required>
                                            <option value="selecione">Selecione</option>
                                            <option value="9">Térreo</option>
                                            <option value="1">Primeiro</option>
                                            <option value="2">Segundo</option>
                                            <option value="3">Terceiro</option>
                                            <option value="4">Quarto</option>
                                            <option value="5">Quinto</option>
                                            <option value="6">Sexto</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="editCapacidade" class="col-form-label">Capacidade</label>
                                        <input type="number" id="editCapacidade" name="editCapacidade" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btnAcao" data-bs-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btnAcao" onclick="editarSala();">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>



        <section id="mostrarCadastro">
            <div class="table-responsive tabela-scroll">
                <table class="table table-condensed table-hover">
                    <thead>
                        <tr>
                            <th>Sala</th>
                            <th>Descrição</th>
                            <th>Andar</th>
                            <th>Capacidade</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="conteudo-sala">

                    </tbody>
                </table>
            </div>
        </section>
    </main>



</body>

</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/js/sala.js" defer></script>