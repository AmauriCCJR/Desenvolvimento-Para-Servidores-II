async function cadastro() {
    event.preventDefault();

    try{
        const codigo = document.getElementById('codigo').value;
        const descricao = document.getElementById('descricao').value;
        const horaIni = document.getElementById('horaIni').value;
        const horaFim = document.getElementById('horaFim').value;

        const response = await fetch('../Horario/inserir', {method:'POST', headers: {'Content-Type': 'application/json'}, 
            body: JSON.stringify({codigo: codigo, descricao: descricao, horaInicial: horaIni, horaFinal: horaFim})});
        const result = await response.json();
        console.log('Dados: ', result)
        if (result.sucesso){
            $('#cadastroPeriodoModal').modal('hide');
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
        console.error('Erro ao cadastrar Periodo: ', error);
        Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição. ', 'error');
    }
}

async function carregarDados() {
    try {
        const response = await fetch('../Horario/consultar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                codigo: '',
                descricao: '',
                horaInicial: '',
                horaFinal: ''
            })
        });
        const data = await response.json();
        const conteudoAcesso = document.getElementById('conteudo-Periodo');

        conteudoAcesso.innerHTML = '';

        if (!data.sucesso || !data.dados || data.dados.length === 0) {
            conteudoAcesso.innerHTML = '<tr><td colspan="5">Nenhum registro encontrado.</td></tr>';
            return;
        }

        data.dados.forEach(item => {
            
            conteudoAcesso.innerHTML += `
            <tr class="alert alert-warning">
                <td>${item.codigo}</td>
                <td>${item.descricao}</td>
                <td>${item.hora_ini}</td>
                <td>${item.hora_fim}</td>
                <td>
                    <div class="row">
                    <button class="btn btn-warning btnAcao" onclick="openEditModal(this)">
                        <i class="fas fa-pencil"></i>
                    </button>
                    <button class="btn btn-danger btnAcao btnAcaoExcluir" onclick="deletarPeriodo(${item.codigo})">
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

$(document).ready(function(){
    carregarDados();

    $('#cadastroPeriodoModal').on('show.bs.modal', function(){
        $('#formCadastroPeriodo')[0].reset();
    });
});


function openEditModal(button){
    const row = button.closest('tr');

    const codigo = row.cells[0].innerText;
    const descricao = row.cells[1].innerText;
    const horaIni = row.cells[2].innerText;
    const horaFim = row.cells[3].innerText;

    document.getElementById('editId').value = codigo;
    document.getElementById('editDescricao').value = descricao;
    document.getElementById('editHoraIni').value = horaIni;
    document.getElementById('editHoraFim').value = horaFim;

    $('#editModal').modal('show');
}

async function editarPeriodo(){
    event.preventDefault();

    try{
        const codigo = document.getElementById('editId').value;
        const descricao = document.getElementById('editDescricao').value;
        const horaIni = document.getElementById('editHoraIni').value;
        const horaFim = document.getElementById('editHoraFim').value;

        const response = await fetch('../Horario/alterar', {method:'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({
            codigo: codigo,
            descricao: descricao,
            horaInicial: horaIni,
            horaFinal: horaFim
        })});

        const result = await response.json();

        if (result.sucesso){
            $('#editModal').modal('hide');
            Swal.fire('Sucesso!', result.msg, 'success');
            carregarDados();
        } else {
            const mensagemDeErro = result.Erros.map(erro => {
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
        console.error('Erro ao editar o periodo: ', error);
        Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição.', 'error');
    }
}


async function deletarPeriodo(codigo){
    Swal.fire({
        title: 'Atenção!',
        text: 'Tem certeza que deseja remover esse periodo?',
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
            const request = await fetch('../Horario/desativar', config);
            const response = await request.json();

            Swal.fire({
                title: 'Atenção!',
                text: response.msg,
                icon: response.sucesso ? 'success' : 'error',
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
    const tabela = document.getElementById('conteudo-Periodo');
    const linhas = tabela.getElementsByTagName('tr');

    for (let i=0; i< linhas.length; i++){
        const colDescricao = linhas[i].getElementsByTagName("td")[1]; 
        const colHoraIni = linhas[i].getElementsByTagName("td")[2];
        const colHoraFim = linhas[i].getElementsByTagName("td")[3];

        if (colDescricao){
            const descricaoTexto = colDescricao.textContent || colDescricao.innerText;
            const hrIniTexto = colHoraIni.textContent || colHoraIni.innerText;
            const hrFimTexto = colHoraFim.textContent || colHoraFim.innerText;

            if ((descricaoTexto.toLowerCase().indexOf(filter) > -1) || (hrIniTexto.toLocaleLowerCase().indexOf(filter) > -1) ||
                (hrFimTexto.toLowerCase().indexOf(filter) > -1)){
                linhas[i].style.display = "";
            } else {
                linhas[i].style.display = "none";
            }
        }
    }
}