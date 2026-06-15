// Função de Cadastro
async function cadastro(event) {
    event.preventDefault();

    try {
        const sala = document.getElementById('selectSalas').value;
        const turma = document.getElementById('selectTurma').value;
        const professor = document.getElementById('selectProfessor').value;
        const horario = document.getElementById('selectHorario').value;
        const dataReserva = document.getElementById('dataFim').value;

        const response = await fetch('../Mapa/inserir', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                codSala: sala,
                codHorario: horario,
                codTurma: turma,
                codProfessor: professor,
                dataReserva: dataReserva
            })
        });
        const result = await response.json();
        console.log('Dados: ', result);

        if (result.sucesso) {
            $('#cadastroMapeamentoModal').modal('hide');
            Swal.fire('Sucesso!', result.msg, 'success');
            carregarDados();
        } else {
            const mensagemDeErro = result.erros.map(erro => {
                return `<p><strong>[${erro.campo ?? erro.codigo}]</strong> ${erro.msg}</p>`;
            }).join('');

            Swal.fire({
                title: 'Houve(ram) erro(s) de validação: ',
                html: mensagemDeErro,
                icon: 'error',
                confirmButtonText: 'Fechar'
            });
        }

    } catch (error) {
        console.error('Erro ao cadastrar Reserva: ', error);
        Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição. ', 'error');
    }
}

const spinner = document.getElementById('spinner');

// Função para Carregar os Dados na Tabela
async function carregarDados() {
    try {
        spinner.style.display = 'block';

        // Enviando filtros vazios para trazer todos os dados do mapa
        const response = await fetch('../Mapa/consultar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                codSala: '',
                codHorario: '',
                codTurma: '',
                codProfessor: '',
                dataReserva: ''
            })
        });

        const data = await response.json();
        const conteudoAcesso = document.getElementById('conteudo-Mapeamento');

        conteudoAcesso.innerHTML = '';

        if (!data.sucesso || !data.dados || data.dados.length === 0) {
            conteudoAcesso.innerHTML = '<tr><td colspan="7" class="text-center">Nenhum registro encontrado.</td></tr>';
            return;
        }

        const fragmento = document.createDocumentFragment();

        data.dados.forEach(item => {
            const linha = document.createElement('tr');
            linha.classList.add('alert', 'alert-warning');
            linha.innerHTML = `
                <td style="display:none"><input type="checkbox" class="selecionar-item" value="${item.codigo}"></td>
                <td>${item.sala}</td>
                <td>${item.descsala}</td>
                <td>${item.descturma}</td>
                <td style="display:none">${item.codigo_turma}</td>
                <td>${item.nome_professor}</td>
                <td style="display:none">${item.codigo_professor}</td>
                <td>${item.datareserva}</td>
                <td style="display:none">${item.datareserva}</td>
                <td>${item.deschorario}</td>
                <td style="display: none">${item.codigo_horario}</td>
                <td>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <button class="btn btn-warning btnAcao" onclick="openEditModal(this, ${item.codigo})">
                            <i class="fas fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btnAcao btnAcaoExcluir" onclick="deletarMapeamento(${item.codigo})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>`;
            fragmento.appendChild(linha);
        });

        conteudoAcesso.appendChild(fragmento);

    } catch (error) {
        console.error('Erro ao carregar os dados: ', error);
    } finally {
        spinner.style.display = 'none';
    }
}

// Debounce para pesquisa
function debounce(func, delay) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => func(...args), delay);
    };
}

const carregarDadosDebounced = debounce(carregarDados, 300);

// Abrir Modal de Edição preenchendo os campos
function openEditModal(button, codigo) {
    const row = button.closest('tr');

    const sala = row.cells[1].innerText;
    const turma = row.cells[4].innerText;
    const professor = row.cells[6].innerText;
    const dataMapeamento = row.cells[8].innerText;
    const horario = row.cells[10].innerText;

    document.getElementById('editId').value = codigo;
    document.getElementById('editSelectSalas').value = sala;
    document.getElementById('editSelectTurma').value = turma;
    document.getElementById('editSelectProfessor').value = professor;
    document.getElementById('dataEditar').value = dataMapeamento;
    document.getElementById('editSelectHorario').value = horario;

    $('#editModal').modal('show');
}

// Salvar Edição
async function editarMapeamento(event) {
    event.preventDefault();

    try {
        const codigo = document.getElementById('editId').value;
        const sala = document.getElementById('editSelectSalas').value;
        const turma = document.getElementById('editSelectTurma').value;
        const professor = document.getElementById('editSelectProfessor').value;
        const dataMapeamento = document.getElementById('dataEditar').value;
        const horario = document.getElementById('editSelectHorario').value;

        const response = await fetch('../Mapa/alterar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                codigo: codigo,
                codSala: sala,
                codHorario: horario,
                codTurma: turma,
                codProfessor: professor,
                dataReserva: dataMapeamento // Corrigido aqui
            })
        });

        const result = await response.json();

        if (result.sucesso) {
            $('#editModal').modal('hide');
            Swal.fire('Sucesso!', result.msg, 'success');
            carregarDados();
        } else {
            const mensagemDeErro = result.erros.map(erro => {
                return `<p><strong>[${erro.campo ?? erro.codigo}]</strong> ${erro.msg}</p>`;
            }).join('');

            Swal.fire({
                title: 'Houve(ram) erros(s) de validação: ',
                html: mensagemDeErro,
                icon: 'error',
                confirmButtonText: 'Fechar'
            });
        }

    } catch (error) {
        console.error('Erro ao editar a reserva: ', error);
        Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição.', 'error');
    }
}

// Deletar múltiplos
async function deletarMapeamentoMultiplos(codigo) {
    Swal.fire({
        title: 'Atenção!',
        text: 'Tem certeza que deseja remover essas reservas?',
        icon: 'question',
        showConfirmButton: true,
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-danger btnAcao',
            cancelButton: 'btn btn-secondary btnAcao'
        },
        buttonsStyling: false,
    }).then(async function (res) {
        if (res.isConfirmed) {
            const config = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ codigo: codigo })
            };
            const request = await fetch('../Mapa/desativarMultiplos', config);
            const response = await request.json();

            Swal.fire({
                title: 'Atenção!',
                text: response.msg,
                icon: response.sucesso ? 'success' : 'error',
                customClass: { confirmButton: 'btn btn-primary btnAcao' },
                buttonsStyling: false
            });
            carregarDados();
        }
    });
}

// Deletar um único registro
async function deletarMapeamento(codigo) {
    Swal.fire({
        title: 'Atenção!',
        text: 'Tem certeza que deseja remover essa reserva?',
        icon: 'question',
        showConfirmButton: true,
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-danger btnAcao',
            cancelButton: 'btn btn-secondary btnAcao'
        },
        buttonsStyling: false,
    }).then(async function (res) {
        if (res.isConfirmed) {
            const config = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ codigo: codigo })
            };
            const request = await fetch('../Mapa/desativar', config);
            const response = await request.json();

            Swal.fire({
                title: 'Atenção!',
                text: response.msg,
                icon: response.sucesso ? 'success' : 'error',
                customClass: { confirmButton: 'btn btn-primary btnAcao' },
                buttonsStyling: false
            });
            carregarDados();
        }
    });
}

// Filtro local na tabela
function filtrarTabela() {
    const input = document.getElementById('inputPesquisa');
    const filter = input.value.toLowerCase();
    const tabela = document.getElementById('conteudo-Mapeamento');
    const linhas = tabela.getElementsByTagName('tr');

    for (let linha of linhas) {
        const celulas = linha.getElementsByTagName("td");

        if (celulas.length > 0) {
            const conteudoLinha = Array.from(celulas)
                .map(celula => celula.textContent.trim().toLowerCase()).join(" ");

            linha.style.display = conteudoLinha.includes(filter) ? "" : "none";
        }
    }
}

// Execuções do Document Ready (Cargas de selects base)
$(document).ready(function () {
    carregarDados();

    $('#cadastroMapeamentoModal').on('show.bs.modal', function () {
        $('#formCadastroMapeamento')[0].reset();
    });

    // Escuta do envio do formulário de cadastro
    $('#formCadastroMapeamento').on('submit', function (e) {
        cadastro(e);
    });

    // Escuta do envio do formulário de edição
    $('#formEditMapeamento').on('submit', function (e) {
        editarMapeamento(e);
    });

    // Requisições AJAX de listagem para os Selects...
    // Salas
    $.ajax({
        url: '../Sala/consultar',
        method: "POST",
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({ codigo: '', descricao: '', andar: '', capacidade: '' }),
        success: function (retorno) {
            if (retorno.codigo == 1) {
                $.each(retorno.dados, function (index, item) {
                    const option = $('<option>', { value: item.codigo, text: item.codigo + " - " + item.descricao });
                    $('#selectSalas').append(option.clone());
                    $('#editSelectSalas').append(option);
                });
            }
        }
    });

    // Professores
    $.ajax({
        url: '../Professor/consultar',
        method: "POST",
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({ codigo: '', nome: '', cpf: '', tipo: '' }),
        success: function (retorno) {
            if (retorno.codigo == 1) {
                $.each(retorno.dados, function (index, item) {
                    const option = $('<option>', { value: item.codigo, text: item.nome });
                    $('#selectProfessor').append(option.clone());
                    $('#editSelectProfessor').append(option);
                });
            }
        }
    });

    // Turmas
    $.ajax({
        url: '../Turma/consultar',
        method: "POST",
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({ codigo: '', descricao: '', capacidade: '', dataInicio: '' }),
        success: function (retorno) {
            if (retorno.codigo == 1) {
                $.each(retorno.dados, function (index, item) {
                    const option = $('<option>', { value: item.codigo, text: item.descricao });
                    $('#selectTurma').append(option.clone());
                    $('#editSelectTurma').append(option);
                });
            }
        }
    });

    // Horários
    $.ajax({
        url: '../Horario/consultar',
        method: "POST",
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify({ codigo: '', descricao: '', horaInicial: '', horaFinal: '' }),
        success: function (retorno) {
            if (retorno.codigo == 1) {
                $.each(retorno.dados, function (index, item) {
                    const option = $('<option>', { value: item.codigo, text: item.descricao });
                    $('#selectHorario').append(option.clone());
                    $('#editSelectHorario').append(option);
                });
            }
        }
    });
});