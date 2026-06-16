async function gerarRelatorio() {
    try {
        const dataMapa = document.getElementById('dataRelatorio').value;
        if (!dataMapa) {
            Swal.fire('Erro', 'Por favor, informe uma data.', 'error');
            return;
        }
        const response = await fetch('../Relatorio/gerarMapaNovo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                dataMapa
            })
        });

        const result = await response.json();

        if (result.codigo == 1) {
            Swal.fire('Sucesso!', result.msg, 'success');
            preencherTabela(result.dados);
        } else {
            Swal.fire('Erro', result.msg, 'error');
            limparTabela();
        }
    } catch (error) {
        Swal.fire('Erro', 'Ocorreu um erro ao processar a requisição.', 'error');
    }
}

function preencherTabela(dados) {
    let tabela = document.getElementById('tabelaRelatorio').getElementsByTagName('tbody')[0];
    tabela.innerHTML = "";
    dados.forEach(reserva => {
        let linha = tabela.insertRow();
        linha.insertCell(0).innerText = reserva.datareserva;
        linha.insertCell(1).innerText = reserva.desc_codigo + " - " + reserva.desc_sala;
        linha.insertCell(2).innerText = reserva.desc_turma;
        linha.insertCell(3).innerText = reserva.nome_professor;
        linha.insertCell(4).innerText = reserva.desc_periodo;
        linha.insertCell(5).innerText = " ";
        linha.insertCell(6).innerText = " ";
        linha.insertCell(7).innerText = " ";
    });
}

function limparTabela() {
    document.getElementById('tabelaRelatorio').getElementsByTagName('tbody')[0].innerHTML = "";
}

function imprimirRelatorio(tabelaId) {
    let tabelaVerifica = document.getElementById(tabelaId);

    let linhas = tabelaVerifica.getElementsByTagName('tr');

    if (linhas.length <= 1) {
        Swal.fire('Erro', 'Por favor, gere o relatório primeiro, informando uma data.', 'error');
        return;
    } else {
        let tabela = document.getElementById(tabelaId).outerHTML;
        let janela = window.open('', '', 'width=900, height=600');
        janela.document.write('<html><head><title>Relatório</title>');
        janela.document.write('<style>table{width: 100%; border-collapse:collapse; } th, ');
        janela.document.write('td {border: 1px solid black; padding: 8px; text-align: left; }</style> ');
        janela.document.write('</head><body>');
        janela.document.write('<h2>Relatório de Chaves</h2>');
        janela.document.write(tabela);
        janela.document.write('</body></html>');
        janela.document.close();
        janela.print();
    }
}

function imprimirRelatorioVisualizacao() {
    let tabelaVerifica = document.getElementById('tabelaRelatorio');

    let linhas = tabelaVerifica.getElementsByTagName('tr');

    if (linhas.length <= 1) {
        Swal.fire('Erro', 'Por favor, gere um relatório primeiro, informando uma data.', 'error');
        return;
    } else {
        let tabela = document.getElementById('tabelaRelatorio').cloneNode(true);
        for (let i = 0; i < 3; i++) {
            for (let row of tabela.rows) {
                row.deleteCell(-1);
            }
        }
        let janela = window.open('', '', 'width=900, height=600');
        janela.document.write('<html><head><title>Relatório</title>');
        janela.document.write('<style>table{width: 100%; border-collapse:collapse; } th, ');
        janela.document.write('td {border: 1px solid black; padding: 8px; text-align: left; }</style> ');
        janela.document.write('</head><body>');
        janela.document.write('<h2>Relatório de Visualização</h2>');
        janela.document.write(tabela.outerHTML);
        janela.document.write('</body></html>');
        janela.document.close();
        janela.print();
    }
}

function imprimirRelatorioTV() {
    const dadosTabela = document.getElementById('tabelaRelatorio').getElementsByTagName('tbody')[0].rows;
    if (dadosTabela.length === 0) {
        Swal.fire('Erro', 'Nenhum dado disponível para exibição', 'error');
        return;
    }

    let conteudoHeader = document.querySelector('header');
    let containerRelatorio = document.getElementById('relatorioTV');
    let containerPrincipal = document.getElementById('conteudoPrincipal');

    conteudoHeader.style.display = "none";
    containerPrincipal.style.display = "none";
    containerRelatorio.style.display = "flex";

    let estilos = `
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html {width:100%; height: 100%; overflow: hidden; background-color: #1C1C1C; font-family: Arial, sans-serif; }

        #relatorioTV { position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-color: #1C1C1C; color: white; display: flex;
        flex-direction: column; align-items: center; justify-content: flex-start; padding: 20px; }
        .card-container { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;
        max-width: 90vw; overflow-y: auto; margin-top: 100px; }
        .card { width: 200px; height: 200px; padding: 10px; display: flex;
        flex-direction: column; justify-content: center; align-items: center;
        border-radius: 10px;
        color: white; font-size: 14px; font-weight: bold; text-align: center; }
        .floor-0 { background-color: rgb(128, 128, 128); }
        .floor-1 { background-color: rgb(52, 122, 69); }
        .floor-2 { background-color: rgb(66, 149, 226); }
        .floor-3 { background-color: rgb(178, 41, 196); }
        .floor-4 { background-color: #483D8B; }
        .floor-5 { background-color: #f7941d; }
        .floor-6 { background-color: rgb(200, 50, 50); }
        .btn-voltar, .btn-periodo { font-size: 16px; padding: 10px 20px; cursor: pointer;
        border-radius: 5px; color: white; }
        .btn-voltar { position: absolute; top: 20px; left: 20px; background-color: red; }
        .btn-voltar:hover { background-color: darkred; }
        .btn-periodo { background-color: #333; margin: 0 10px; padding: 10px 20px; }
        .btn-periodo:hover { background-color: #555; }

        /* Agrupando os botões em linha */
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 10px; /* Espaço entre os botões */
            position: absolute;
            top: 60px;
            width: 100%;
        }
    
    </style>`;

    let conteudo = `
        <button class="btn-voltar" onclick="voltarParaPrincipal()">Voltar</button>
        <div class="btn-container">
            <button class="btn-periodo" onclick="filtrarPeriodo('manha')">Manhã</button>
            <button class="btn-periodo" onclick="filtrarPeriodo('tarde')">Tarde</button>
            <button class="btn-periodo" onclick="filtrarPeriodo('noite')">Noite</button>
        </div>
        <div class="card-container" id="cardsContainer"></div>
    `;

    let dadosOrdenados = [];

    for (let i = 0; i < dadosTabela.length; i++) {
        let sala = dadosTabela[i].cells[1].innerText;
        let turma = dadosTabela[i].cells[2].innerText;
        let professor = dadosTabela[i].cells[3].innerText;
        let horario = dadosTabela[i].cells[4].innerText;


        let andar = parseInt(sala.match(/\d+/)[0].charAt(0)) || 0;
        andar = andar > 6 ? 6 : andar;

        let periodo = 'manha';
        if (horario.includes('Tarde') || horario.includes('13:30')) {
            periodo = "tarde";
        } else if (horario.includes('Noite') || horario.includes('19:00')) {
            periodo = "Noite";
        }

        dadosOrdenados.push({ sala, turma, professor, horario, andar, periodo });
    };

    dadosOrdenados.sort((a, b) => a.andar - b.andar);


    dadosOrdenados.forEach(dado => {
        conteudo += `
        <div class="card floor-${dado.andar}" data-periodo="${dado.periodo}">
            <div>Sala: ${dado.sala}</div>
            <div>Turma: ${dado.turma}</div>
            <div>Professor: ${dado.professor}</div>
            <div>Horario: ${dado.horario}</div>
        </div>
    `;
    });

    conteudo += `</div>`;


    containerRelatorio.innerHTML = estilos + conteudo;

    filtrarPeriodo('manha');
}

function voltarParaPrincipal(){
    document.getElementById('relatorioTV').style.display = "none";
    document.getElementById('conteudoPrincipal').style.display = "block";
    document.querySelector("header").style.display = "flex";
}

function filtrarPeriodo(periodo){
    let cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        if (card.getAttribute('data-periodo') === periodo){
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}