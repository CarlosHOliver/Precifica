<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastrar Insumos</title>
        <link rel="stylesheet" href="style.css">
        <!-- Link para Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </head>

<body>
    <?php
    require 'database.php'; // Inclui a conexão com o banco de dados

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Captura os dados do formulário
        $nome = $_POST['nome'];
        $quantidade = $_POST['quantidade'];
        $preco = $_POST['preco'];

        // Validações básicas (opcional)
        if (empty($nome) || empty($quantidade) || empty($preco)) {
            $erro = "Todos os campos são obrigatórios!";
        } else {
            // Inserir os dados no banco
            $sql = "INSERT INTO insumos (nome, quantidade, preco) VALUES (:nome, :quantidade, :preco)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':quantidade', $quantidade);
            $stmt->bindParam(':preco', $preco);

            if ($stmt->execute()) {
                $sucesso = "Insumo cadastrado com sucesso!";
            } else {
                $erro = "Erro ao cadastrar o insumo!";
            }
        }
    }
    ?>
    <div class="container">
        <h1>Cadastrar Insumos</h1>
        <form method="POST" action="">
            <label for="nome">Nome do Insumo:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="quantidade">Quantidade:</label>
            <input type="number" step="any" id="quantidade" name="quantidade" required>

            <label for="preco">Preço:</label>
            <input type="number" step="any" id="preco" name="preco" required>

            <!-- Botão com a classe btn para seguir o estilo -->
            <button type="submit" class="btn">Cadastrar Insumo</button>
        </form>

        <!-- Mensagens de sucesso ou erro -->
        <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
        <?php if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>"; ?>

        <!-- Botão de Voltar -->
        <p><div class="voltar">
            <a href="index.php" class="btn voltar-btn">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
        </p></div>

    <footer>
        <p> <strong>(c) Dobradura Artes e Personalizados - 2024 - Desenvolvido por Carlos Henrique C. de Oliveira | Projeto de Extensão I - Descomplica Faculdade Digital</strong></p>
    </footer>
</body>
</html>
