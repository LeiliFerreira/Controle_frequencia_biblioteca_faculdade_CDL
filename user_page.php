<?php
@include 'config.php';

session_start();

if (!isset($_SESSION['user_name'])) {
    header('location:login_form.php');
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sistema</title>
    <link rel="stylesheet" href="css/style_user_page.css">
    <link rel="icon" href="img/icone_livro.png"> 
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
</head>

<body>
    <script>
        const modal = document.querySelector('.modal-container');
        const contagemOpcoes = {};
        const somatorioIndividual = {};
        let myChart; // Adicionado para armazenar a instância do gráfico
        const graficoContainer = document.getElementById('graficoContainer');
        const btnMostrarSomatorio = document.getElementById('btnMostrarSomatorio');

        function openModal() {
            modal.classList.add('active');
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        let contadorLinhas = 0;

        function registrarOpcoes() {
            const tipoUsuario = document.getElementById('categoria').value;
            const opcoesSelecionadas = document.querySelectorAll('input[type="checkbox"]:checked');
            const opcoesList = document.getElementById('opcoesList');

            opcoesSelecionadas.forEach(opcao => {
                opcao.checked = false;
            });

            const tr = document.createElement('tr');

            // Célula do tipo de usuário
            const tdTipoUsuario = document.createElement('td');
            tdTipoUsuario.textContent = tipoUsuario;
            tr.appendChild(tdTipoUsuario);

            // Células das opções selecionadas
            opcoesSelecionadas.forEach(opcao => {
                const td = document.createElement('td');
                td.textContent = opcao.value;
                tr.appendChild(td);

                // Atualizar a contagem de opções
                contagemOpcoes[opcao.value] = (contagemOpcoes[opcao.value] || 0) + 1;

                // Atualizar o somatório individual
                somatorioIndividual[opcao.value] = (somatorioIndividual[opcao.value] || 0) + 1;
            });

            // Célula da data
            const tdData = document.createElement('td');
            const dataLabel = document.createElement('div');
            dataLabel.classList.add('data-label');
            dataLabel.textContent = obterDataAtualFormatada();
            tdData.appendChild(dataLabel);
            tr.appendChild(tdData);

            // Célula do botão de apagar
            const tdApagar = document.createElement('td');
            const btnApagar = document.createElement('button');
            btnApagar.textContent = 'X';
            btnApagar.style.backgroundColor = 'red';
            btnApagar.style.color = 'white';
            btnApagar.style.width = '25px';
            btnApagar.style.border = 'none';
            btnApagar.style.borderRadius = '3px';
            btnApagar.onclick = function () {
                apagarLinha(tr);
            };
            tdApagar.appendChild(btnApagar);
            tr.appendChild(tdApagar);

            opcoesList.appendChild(tr);

            contadorLinhas++;
            btnMostrarSomatorio.style.display = 'block';
        }

        function apagarLinha(linha) {
            linha.remove();
            contadorLinhas--;

            if (contadorLinhas === 0) {
                btnMostrarSomatorio.style.display = 'none';
            }
        }

        function mostrarSomatorio() {
            let somatorioAlert = '';

            for (const opcao in somatorioIndividual) {
                somatorioAlert += `Somatório para ${opcao}: ${somatorioIndividual[opcao]}\n`;
            }

            let somatorioGeral = 0;
            for (const opcao in contagemOpcoes) {
                somatorioGeral += contagemOpcoes[opcao];
            }

            somatorioAlert += `\nSomatório Geral: ${somatorioGeral}`;

            alert(somatorioAlert);
        }

        function obterDataAtualFormatada() {
            const dataAtual = new Date();
            const dia = String(dataAtual.getDate()).padStart(2, '0');
            const mes = String(dataAtual.getMonth() + 1).padStart(2, '0');
            const ano = dataAtual.getFullYear();
            return `Data: ${dia}/${mes}/${ano}`;
        }

        function gerarGrafico() {
        // Destruir o gráfico existente
        if (myChart) {
            myChart.destroy();
        }

        const ctx = document.getElementById('meuGrafico').getContext('2d');
        const graficoContainer = document.getElementById('graficoContainer');

        if (graficoContainer) {
            const labels = Array.from(new Set([...Object.keys(contagemOpcoes), 'A.Pesq. Acervo', 'B.Estudo em Grupo', 'C.Estudo Leitura Individual', 'D.Acesso aos Micros', 'E.Visita', 'F.Normalização', 'G.Pesq. Internet', 'H.Orient. Pesquisa', 'I.Levant. Bibliog.']));
            const data = labels.map(opcao => contagemOpcoes[opcao] || 0);

            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Quantidade de Registros por Opção',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            graficoContainer.style.display = 'block';
        }
    }

      function gerarRelatorio() {
        const opcoesList = document.getElementById('opcoesList');
        const linhas = opcoesList.getElementsByTagName('tr');
        const xmlDoc = document.implementation.createDocument(null, 'Relatorio');
        const relatorioElement = xmlDoc.documentElement;

        for (let i = 0; i < linhas.length; i++) {
            const linha = linhas[i];
            const tipoUsuario = linha.cells[0].textContent;
            const opcoes = [];

            for (let j = 1; j < linha.cells.length - 2; j++) {
                opcoes.push(linha.cells[j].textContent);
            }

            const data = linha.cells[linha.cells.length - 2].textContent;

            const registroElement = xmlDoc.createElement('Registro');

            const tipoUsuarioElement = xmlDoc.createElement('TipoUsuario');
            tipoUsuarioElement.textContent = tipoUsuario;
            registroElement.appendChild(tipoUsuarioElement);

            const opcoesElement = xmlDoc.createElement('Opcoes');
            opcoes.forEach(opcao => {
                const opcaoElement = xmlDoc.createElement('Opcao');
                opcaoElement.textContent = opcao;
                opcoesElement.appendChild(opcaoElement);
            });
            registroElement.appendChild(opcoesElement);

            const dataElement = xmlDoc.createElement('Data');
            dataElement.textContent = data;
            registroElement.appendChild(dataElement);

            relatorioElement.appendChild(registroElement);
        }

        const serializer = new XMLSerializer();
        const xmlString = serializer.serializeToString(xmlDoc);

        const blob = new Blob([xmlString], {type: 'application/xml'});
        const url = URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.href = url;
        a.download = 'relatorio.xml';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        URL.revokeObjectURL(url);
    }

    function sair() {
            window.location.href = 'login.php';
        }


    </script>
    <nav>
        <img class="logo" src="img/logo_nav.png" width="100px">
        <h3> FACULDADE CDL </h3>
        <ul class="nav_list">
            <div class="img_perfil">
                <img class="icone_perfil" src="img/icone_perfil.png">
            </div>
            <div class="nome_perfil">
                <p class="nome_perfil"><span><?php echo $_SESSION['user_name'] ?></span></p>
            </div>
            <div class="img_sair">
                <input type="image" img class="icone_sair" src="img/icone_sair.png" onclick="sair()">
            </div>
        </ul>
    </nav>

    <div>
        <h2>Controle Biblioteca</h2>

        <div class="tipo_user">
            <label class="legenda_tipo">Tipo de usuário:</label>
            <select id="categoria" name="categoria">
                <option value="AG">AG - Aluno Graduação</option>
                <option value="AP">AP - Aluno Pós Graduação</option>
                <option value="AE">AE - Aluno Extensão</option>
                <option value="DO">DO - Corpo Docente</option>
                <option value="DI">Di - Diretor</option>
                <option value="FF">FF - Funcionário</option>
                <option value="GT">GT - Cestor</option>
                <option value="IN">IN - Institucional</option>
                <option value="VI">VI - Visitante</option>
            </select>
        </div>

        
        <table>
            <tr>
                <th><label for="option1">A.Acesso aos Micros</label></th>
                <th><label for="option2">B.Jogos</label></th>
                <th><label for="option3">C.Pesq. Acervo</label></th>
                <th><label for="option4">D.Estudo em Grupo</label></th>
                <th><label for="option5">E.Estudo Leitura Individual</label></th>
                <th><label for="option6">F.Visita</label></th>
                <th><label for="option7">G.Normalização</label></th>
                <th><label for="option8">H.Pesq. Internet</label></th>
                <th><label for="option9">I.Orient. Pesq</label></th>
                <th><label for="option10">J.Levant. Bibliog</label></th>
                <th><label for="option11">L.Aula DIF</label></th>
                <th><label for="option12">M.BV</label></th>
            </tr>

            <tr>
                <th><label for="option1"><input type="checkbox" id="option1" value="A.Acesso aos Micros"></th>
                <th><label for="option2"><input type="checkbox" id="option2" value="B.Jogos"></th>
                <th><label for="option3"><input type="checkbox" id="option3" value="C.Pesquisa Acervo"></th>
                <th><label for="option4"><input type="checkbox" id="option4" value="D.Estudo em Grupo"></th>
                <th><label for="option5"><input type="checkbox" id="option5" value="E.Estudo Leitura Individual"></th>
                <th><label for="option6"><input type="checkbox" id="option6" value="F.Visita"></th>
                <th><label for="option7"><input type="checkbox" id="option7" value="G.Normalização"></th>
                <th><label for="option8"><input type="checkbox" id="option8" value="H.Pesq. Internet"></th>
                <th><label for="option9"><input type="checkbox" id="option9" value="I.Orient. Pesq"></th>
                <th><label for="option10"><input type="checkbox" id="option10" value="J.Levant. Bibliog"></th>
                <th><label for="option11"><input type="checkbox" id="option11" value="L.Aula DIF"></th>
                <th><label for="option12"><input type="checkbox" id="option12" value="BV"></th>
            </tr>
        </table>

        <center>
            <br>
            <button class="botao_registrar"onclick="registrarOpcoes()">Registrar</button>
            <button id="btnMostrarSomatorio" onclick="mostrarSomatorio()">Mostrar Somatório</button>
            <button onclick="gerarGrafico()">Gerar Gráfico</button>
            <button onclick="gerarRelatorio()">Gerar Relatório</button>
        </center>

        <div id="graficoContainer" style="display: none;">
            <canvas id="meuGrafico"></canvas>
        </div>


        <div id="opcoesSelecionadas">
            <table id="opcoesList"></table>
        </div>
    </div>
</body>

</html>
