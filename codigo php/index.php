<?php
// CONFIG-CONEXAO
$conexao = mysqli_connect("localhost", "root", "", "formulario");
//localhoast é o endereco do serivdor no banco de dados
//root do usuario
//formulario é o nome do banco de dados
//""senha que no caso o nosso codigo nao tem 

// PROCESSO PARA EXCLUIR UM USUARIO/NOME
if (isset($_GET['delete'])) {
    $nomeParaDeletar = mysqli_real_escape_string($conexao, $_GET['delete']);
    $sqlDelete = "DELETE FROM usuarios WHERE nome='$nomeParaDeletar'";
    mysqli_query($conexao, $sqlDelete);
}

// PROCESSO PARA INSERIR OS DADOS 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Protege o valor do campo "nome" contra SQL Injection
    $nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    // Recebe o valor do campo "idade"
    $idade = $_POST['idade'];
    // Recebe o valor do campo "email"
    $email = $_POST['email'];

    // Verifica se o nome já existe no banco de dados
    $sql = "SELECT nome FROM usuarios WHERE nome='$nome'";
    $retorno = mysqli_query($conexao, $sql);

    // Se o nome não existir, insere os dados no banco
    if (mysqli_num_rows($retorno) == 0) {
        // Cria a consulta SQL para inserir o novo usuário na tabela
        $sql = "INSERT INTO usuarios (nome, idade, email) VALUES ('$nome', '$idade', '$email')";
        // Executa a consulta de inserção no banco de dados
        mysqli_query($conexao, $sql);
}
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
<div style="display: flex; flex-direction: column; width: 500px; margin: 0 auto;">
    <form method="POST">
        <h1>CRUD</h1>
        <label for="user">Nome:</label>
        <input type="text" id="user" name="nome"> 
        <br>
        <label for="idade">Idade:</label>
        <input type="number" id="idade" name="idade">
        <br>
        <label for="email">E-mail:</label>
        <input type="text" id="email" name="email">
        <br>
        <input type="submit" style="cursor: pointer; display: flex; flex-direction: column; width: 76px; margin: 0 auto; height: 35px; background-color: red ; color: white;" value="Enviar">
        <br>
    </form>
    <table id="minhaTabela">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Idade</th>
                <th>E-mail</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT nome, idade, email FROM usuarios";
            $result = mysqli_query($conexao, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['nome'] . "</td>";
                echo "<td>" . $row['idade'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>
                        <button class='edit-btn' onclick='editarLinha(this)'>Editar</button>
                        <a href='?delete=" . urlencode($row['nome']) . "' onclick='return confirm(\"Tem certeza que deseja apagar?\")'>
                            <button class='delete-btn'>Apagar</button>
                        </a>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
</div>
<script>
function editarLinha(btn) {
    var linha = btn.parentNode.parentNode;
    document.getElementById("user").value = linha.cells[0].textContent;
    document.getElementById("idade").value = linha.cells[1].textContent;
    document.getElementById("email").value = linha.cells[2].textContent;
    linha.parentNode.removeChild(linha);
}
</script>
</body>
</html>