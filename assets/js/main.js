async function validaLogin() {
    event.preventDefault();

    try {
        const user = document.getElementById('txtUsuario').value;
        const senha = document.getElementById('txtSenha').value;

        const response = await fetch('http://localhost/Desenvolvimento-Para-Servidores-II/usuario/logar', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({usuario: user, senha: senha})
        });

        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }

        const result = await response.json();

        if (result.codigo == 1) {
            Swal.fire('Sucesso!', result.msg, 'success');
            window.location.href = 'http://localhost/Desenvolvimento-Para-Servidores-II/Funcoes/indexPagina';
        } else {
            const mensagemDeErro = result.erros.map(erro => {
                return `<p><strong>[${erro.campo ?? erro.codigo}]</strong> ${erro.msg}</p>`;
            }).join('');

            Swal.fire({title: 'Houve(ram) erro(s) de validação:', html: mensagemDeErro, icon: 'error', confirmButtonText: 'Fechar'});
        }
    } catch (error) {
        console.error('Errou', error);
    }
}

const tooglePassword = document.getElementById('togglePassword');
const passwordField = document.getElementById('txtSenha');

tooglePassword.addEventListener('click', function() {
    const type = passwordField.type == 'password' ? 'text' : 'password';
    passwordField.type = type;
    this.classList.toggle('fa-eye-slash');
});