async function cadastro() {
    event.preventDefault();

    try{
        const nome = document.getElementById('nome').value;
        const cpf = document.getElementById('cpf').value;
        const tipo = document.getElementById('tipo').value;

        const response = await fetch('../Professor/inserir', {method:'POST', headers: {'Content-Type': 'application/json'}, 
            body: JSON.stringify({nome: nome, cpf: cpf, tipo: tipo})});
        const result = await response.json();

            if (result.codigo == 1){
                $('#cadastroProfessorModal').modal('hide');
                Swal.fire('Sucesso!', result.msg, 'success');
                carregarDados();
            }

    } catch (error){

    }
}