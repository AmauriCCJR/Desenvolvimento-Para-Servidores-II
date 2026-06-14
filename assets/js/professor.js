async function cadastro() {
    event.preventDefault();

    try{
        const codigo = document.getElementById('codigo').value;
        const nome = document.getElementById('nome').value;
        const cpf = document.getElementById('cpf').value;
        const tipo = document.getElementById('tipo').value;

        const response = await fetch('../Professor/inserir', {method:'POST', headers: {'Content-Type': 'application/json'}, 
            body: JSON.stringify({codigo: codigo, nome: nome, cpf: cpf, tipo: tipo})});
        const result = await response.json();

            if (result.codigo == 1){
                bootstrap.Modal.getInstance(document.getElementById('cadastroProfessorModal')).hide();
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

    } catch (error){
        console.error('Erro ao cadastrar o professor: ', error);
        Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição. ', 'error');
    }
}

async function carregarDados() {
    try {
        const response = await fetch('../Professor/consultar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                codigo: '',
                nome: '',
                cpf: '',
                tipo: ''
            })
        });
        const data = await response.json();
        const conteudoAcesso = document.getElementById('conteudo-professor');

        conteudoAcesso.innerHTML = '';

        if (!data.sucesso || !data.dados || data.dados.length === 0) {
            conteudoAcesso.innerHTML = '<tr><td colspan="4">Nenhum registro encontrado.</td></tr>';
            return;
        }

        data.dados.forEach(item => {
            let tipo = item.tipo;
            if (tipo == 'F'){
                tipo = 'Funcionário'
            } else {
                tipo = 'Carta Convite'
            }

            const codigo = item.codigo;
            conteudoAcesso.innerHTML += `
            <tr class="alert alert-warning">
                <td>${item.nome}</td>
                <td>${item.cpf}</td>
                <td>${tipo}</td>
                <td>
                    <div class="row">
                    <button class="btn btn-warning btnAcao" onclick="openEditModal(${item.codigo}, this)">
                        <i class="fas fa-pencil"></i>
                    </button>
                    <button class="btn btn-danger btnAcao btnAcaoExcluir" onclick="deletarProfessor(${item.codigo})">
                        <i class="fas fa-trash"></i>
                    </button>
                    </div>
                </td>
            </tr>
            `;
        });

    } catch (error){
        console.error('Erro ao carregar os dados: ', error);
    }
}

document.addEventListener('DOMContentLoaded', function(){
    carregarDados();

    const cadastroModal = document.getElementById('cadastroProfessorModal');
    cadastroModal.addEventListener('show.bs.modal', function(){
        document.getElementById('formCadastroProfessor').reset();
    });
});

function openEditModal(codigo, button){
    const row = button.closest('tr');

    const nome = row.cells[0].innerText;
    const cpf = row.cells[1].innerText;
    const tipo = row.cells[2].innerText.charAt(0);

    document.getElementById('editId').value = codigo;
    document.getElementById('editNome').value = nome;
    document.getElementById('editCpf').value = cpf;
    document.getElementById('editTipo').value = tipo;

    new bootstrap.Modal(document.getElementById('editModal')).show();
}

async function editarProfessor(){
    event.preventDefault();

    try{
        const codigo = document.getElementById('editId').value;
        const nome = document.getElementById('editNome').value;
        const cpf = document.getElementById('editCpf').value;
        const tipo = document.getElementById('editTipo').value;

        const response = await fetch('../Professor/alterar', {method:'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({
            codigo: codigo,
            nome: nome,
            cpf: cpf,
            tipo: tipo
        })});

        const result = await response.json();

        if (result.codigo == 1){
            bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
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

    } catch(error){
        console.error('Erro ao editar o professor: ', error);
        Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição.', 'error');
    }
}


async function deletarProfessor(codigo){
    Swal.fire({
        title: 'Atenção!',
        text: 'Tem certeza que deseja remover esse professor?',
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
    }).then(async function(res){
        if (res.isConfirmed){
            const config = {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    codigo: codigo
                })
            };
            const request = await fetch('../Professor/desativar', config);
            const response = await request.json();

            Swal.fire({
                title: 'Atenção!',
                text: response.msg,
                icon: response.codigo == 1? 'success' : 'error',
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
    });
}


function filtrarTabela(){
    const input = document.getElementById('inputPesquisa');
    const filter = input.value.toLowerCase();
    const tabela = document.getElementById('conteudo-professor');
    const linhas = tabela.getElementsByTagName('tr');

    for (let i=0; i< linhas.length; i++){
        const colProfessor = linhas[i].getElementsByTagName("td")[0]; 
        const colCpf = linhas[i].getElementsByTagName('td')[1];
        const colTipo = linhas[i].getElementsByTagName('td')[2];

        if (colProfessor){
            const professorTexto = colProfessor.textContent || colProfessor.innerText;
            const tipoTexto = colTipo.textContent || colTipo.innerText;
            const cpfTexto = colCpf.textContent || colCpf.innerText;

            if (professorTexto.toLowerCase().indexOf(filter) > -1 || tipoTexto.toLocaleLowerCase().indexOf(filter) > -1){
                linhas[i].style.display = "";
            } else {
                linhas[i].style.display = "none";
            }
        }
    }
}