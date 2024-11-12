<?php
require 'database.php'; // Inclui a conexão com o banco de dados

// Verifica se o ID do insumo foi passado na URL
if (!isset($_GET['id'])) {
    header('Location: listar_insumos.php'); // Redireciona se o ID não for fornecido
    exit;
}

// Captura o ID do insumo
$id = $_GET['id'];

// Consulta para buscar o insumo pelo ID
$sql = "SELECT * FROM insumos WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id);
$stmt->execute();
$insumo = $stmt->fetch(PDO::FETCH_ASSOC);

// Verifica se o insumo existe
if (!$insumo) {
    header('Location: listar_insumos.php'); // Redireciona se o insumo não for encontrado
    exit;
}

// Atualiza o insumo se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];

    // Validações básicas (opcional)
    if (empty($nome) || empty($quantidade) || empty($preco)) {
        $erro = "Todos os campos são obrigatórios!";
    } else {
        // Atualiza os dados no banco
        $sql = "UPDATE insumos SET nome = :nome, quantidade = :quantidade, preco = :preco WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':preco', $preco);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            $sucesso = "Insumo atualizado com sucesso!";
            // Opcional: Redireciona para a lista após a atualização
            header('Location: listar_insumos.php');
            exit;
        } else {
            $erro = "Erro ao atualizar o insumo!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Insumo</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclui o CSS existente -->
</head>
<body>
    <div class="container">
        <h1>Editar Insumo</h1>
        <form method="POST" action="">
            <label for="nome">Nome do Insumo:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($insumo['nome']); ?>" required>

            <label for="quantidade">Quantidade:</label>
            <input type="number" step="any" id="quantidade" name="quantidade" value="<?php echo htmlspecialchars($insumo['quantidade']); ?>" required>

            <label for="preco">Preço:</label>
            <input type="number" step="any" id="preco" name="preco" value="<?php echo htmlspecialchars($insumo['preco']); ?>" required>

            <button type="submit">Atualizar Insumo</button>
        </form>

        <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
        <?php if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>"; ?>

        <a href="listar_insumos.php" class="button voltar">Voltar à Lista de Insumos</a>
    </div>

    <footer>
        <p><strong>(c) Dobradura Artes e Personalizados - 2024 - Desenvolvido por Carlos Henrique C. de Oliveira | Projeto de Extensão I - Descomplica Faculdade Digital</strong></p>
    </footer>
</body>
</html>
