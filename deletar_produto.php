<?php
require 'database.php'; // Inclui a conexão com o banco de dados

// Verifica se o ID do produto foi passado
if (isset($_GET['id'])) {
    $produto_id = $_GET['id'];

    // Busca o produto para exibir detalhes
    $sql_produto = "SELECT * FROM produtos WHERE id = :produto_id";
    $stmt_produto = $db->prepare($sql_produto);
    $stmt_produto->bindParam(':produto_id', $produto_id);
    $stmt_produto->execute();
    $produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);

    // Verifica se o produto existe
    if (!$produto) {
        echo "Produto não encontrado.";
        exit;
    }

    // Deletar o produto e insumos associados se a confirmação for recebida
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Primeiro, remove os insumos associados
        $sql_delete_insumos = "DELETE FROM produto_insumos WHERE produto_id = :produto_id";
        $stmt_delete_insumos = $db->prepare($sql_delete_insumos);
        $stmt_delete_insumos->bindParam(':produto_id', $produto_id);
        $stmt_delete_insumos->execute();

        // Depois, remove o produto
        $sql_delete_produto = "DELETE FROM produtos WHERE id = :produto_id";
        $stmt_delete_produto = $db->prepare($sql_delete_produto);
        $stmt_delete_produto->bindParam(':produto_id', $produto_id);

        if ($stmt_delete_produto->execute()) {
            header("Location: index.php?sucesso=Produto deletado com sucesso!");
            exit;
        } else {
            $erro = "Erro ao deletar o produto!";
        }
    }
} else {
    header("Location: index.php?erro=ID do produto não especificado!");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Deletar Produto</h1>
        <p>Você tem certeza que deseja deletar o produto <strong><?= $produto['nome'] ?></strong>?</p>
        <form method="POST">
            <button type="submit">Sim, deletar produto</button>
            <a href="index.php" class="button cancelar">Cancelar</a>
        </form>

        <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
    </div>
</body>
</html>
