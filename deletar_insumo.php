<?php
require 'database.php'; // Inclui a conexão com o banco de dados

// Verifica se o ID do insumo foi passado na URL
if (!isset($_GET['id'])) {
    header('Location: listar_insumos.php'); // Redireciona se o ID não for fornecido
    exit;
}

// Captura o ID do insumo
$id = $_GET['id'];

// Verifica se o formulário de confirmação foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Deleta o insumo do banco de dados
    $sql = "DELETE FROM insumos WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        $sucesso = "Insumo deletado com sucesso!";
        header('Location: listar_insumos.php'); // Redireciona para a lista após a exclusão
        exit;
    } else {
        $erro = "Erro ao deletar o insumo!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Insumo</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclui o CSS existente -->
</head>
<body>
    <div class="container">
        <h1>Deletar Insumo</h1>
        <p>Tem certeza que deseja deletar este insumo?</p>
        <form method="POST" action="">
            <button type="submit">Deletar Insumo</button>
            <a href="listar_insumos.php" class="button voltar">Cancelar</a>
        </form>

        <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
        <?php if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>"; ?>
    </div>

    <footer>
        <p><strong>(c) Dobradura Artes e Personalizados - 2024 - Desenvolvido por Carlos Henrique C. de Oliveira | Projeto de Extensão I - Descomplica Faculdade Digital</strong></p>
    </footer>
</body>
</html>
