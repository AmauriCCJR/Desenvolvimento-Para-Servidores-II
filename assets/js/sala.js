async function cadastro() {
    event.preventDefault();
    const codigo = document.getElementById('codigo').value;
    const descricao = document.getElementById('descricao').value;
    const andar = document.getElementById('andar').value;
    const capacidade = document.getElementById('capacidade').value;

    try {
        const response = await fetch('../Sala/inserir', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ codigo: codigo, descricao: descricao, andar: andar, capacidade: capacidade })
        });
        const result = await response.json();

        if (result.codigo == 1) {
            $('#cadastroSalaModal').modal('hide');
            Swal.fire('Sucesso!', result.msg, 'success');
            carregarDados();
        } else {
            const mensagemDeErro = result.erros?.length
                ? result.erros.map(erro => {
                    return `<p><strong>[${erro.campo ?? erro.codigo}]</strong> ${erro.msg}</p>`;
                }).join('')
                : result.msg ?? 'Erro desconhecido.';

            Swal.fire({
                title: 'Erro ao cadastrar',
                html: mensagemDeErro,
                icon: 'error',
                confirmButtonText: 'Fechar'
            });
        }
    } catch (error) {
        console.error('Erro ao cadastrar a sala:', error);
        Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição.', 'error');
    }
}

async function carregarDados() {
    try {
        const response = await fetch('../Sala/consultar', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                codigo: '',
                descricao: '',
                andar: '',
                capacidade: ''
            })
        });

        const data = await response.json();
        const conteudoAcesso = document.getElementById('conteudo-sala');

        conteudoAcesso.innerHTML = '';
        data.dados.forEach(item => {
            conteudoAcesso.innerHTML += `
            <tr class="alert alert-warning">
                <td>${item.codigo}</td>
                <td>${item.descricao}</td>
                <td>${item.andar}</td>
                <td>${item.capacidade}</td>
                <td>
                    <div class="row">
                        <button class="btn btn-warning btnAcao" onclick="openEditModal(this)">
                            <i class="fas fa-pencil"></i>
                        </button>
                        <button class="btn btn-danger btnAcao btnAcaoExcluir" onclick="deletarSala(${item.codigo})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div> 
                </td>
            </tr>`;
        });
    } catch (error) {
        console.error('Erro ao carregar os dados:', error);
    }
}

$(document).ready(function () {
    carregarDados();

    $('#cadastroSalaModal').on('show.bs.modal', function () {
        $('#formCadastroSala')[0].reset();
    });
});

function openEditModal(button) {
    const row = button.closest('tr');
    const codigo = row.cells[0].innerText;
    const descricao = row.cells[1].innerText;
    const andar = row.cells[2].innerText.charAt(0);
    const capacidade = row.cells[3].innerText;

    document.getElementById('editId').value = codigo;
    document.getElementById('editDescricao').value = descricao;
    document.getElementById('editAndar').value = andar;
    document.getElementById('editCapacidade').value = capacidade;

    $('#editModal').modal('show');
}

async function editarSala() {
    event.preventDefault();
    try {
        const codigo = document.getElementById('editId').value;
        const descricao = document.getElementById('editDescricao').value;
        const andar = document.getElementById('editAndar').value;
        const capacidade = document.getElementById('editCapacidade').value;

        const response = await fetch('../Sala/alterar', {
            method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({
                codigo: codigo,
                descricao: descricao,
                andar: andar,
                capacidade: capacidade
            })
        });

        const result = await response.json();

        if (result.codigo == 1) {
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
            })
        }
    } catch (error) {
        console.error('Erro ao cadastrar a sala: ', error);
        Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição.', 'error');
    }
}


async function deletarSala(codigo) {
    Swal.fire({
        title: 'Atenção!',
        text: 'Tem certeza que deseja remover essa sala?',
        icon: 'question',
        showConfirmButton: true,
        showCancelButton: true,
        customClass: {
            popup: 'my-swal-popup',
            title: 'my-swal-title',
            html: 'my-swal-text',
            confirmButton: 'btn btn-danger btnAcao my-swal-button',
            cancelButton: 'btn btn-secondary btnAcao my-swal-button'
        },
        buttonsStyling: false,
    }).then(async function (res) {
        if (res.isConfirmed) {
            const config = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    codigo: codigo
                })
            };
            const request = await fetch('../Sala/desativar', config);
            const response = await request.json();

            Swal.fire({
                title: 'Atenção!',
                text: response.msg,
                icon: response.codigo == 1 ? 'success' : 'error',
                customClass: {
                    popup: 'my-swal-popup',
                    title: 'my-swal-title',
                    html: 'my-swal-text',
                    confirmButton: 'btn btn-primary btnAcao'
                },
                buttonsStyling: false
            });
            carregarDados();
        }
    })
}

function filtrarTabela() {
    const input = document.getElementById('inputPesquisa');
    const filter = input.value.toLowerCase();
    const tabela = document.getElementById('conteudo-sala');
    const linhas = tabela.getElementsByTagName('tr');


    for (let i = 0; i < linhas.length; i++) {
        const colSala = linhas[i].getElementsByTagName('td')[0];
        const colDescricao = linhas[i].getElementsByTagName('td')[1];

        if (colSala || colDescricao) {
            const salaTexto = colSala.textContent || colSala.innerText;
            const descricaoTexto = colDescricao.textContent || colDescricao.innerText;
            if (salaTexto.toLowerCase().indexOf(filter) > -1 || descricaoTexto.toLocaleLowerCase().indexOf(filter) > -1) {
                linhas[i].style.display = "";
            } else {
                linhas[i].style.display = "none";
            }
        }
    }
}