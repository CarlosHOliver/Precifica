<?php
require 'database.php'; // Inclui a conexão com o banco de dados

// Verifica se o ID do produto foi passado
if (isset($_GET['id'])) {
    $produto_id = $_GET['id'];

    // Busca o produto para exibir os dados atuais
    $sql_produto = "SELECT * FROM produtos WHERE id = :produto_id";
    $stmt_produto = $db->prepare($sql_produto);
    $stmt_produto->bindParam(':produto_id', $produto_id);
    $stmt_produto->execute();
    $produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);

    // Busca todos os insumos disponíveis
    $sql_todos_insumos = "SELECT * FROM insumos";
    $stmt_todos_insumos = $db->query($sql_todos_insumos);
    $todos_insumos = $stmt_todos_insumos->fetchAll(PDO::FETCH_ASSOC);

    // Busca os insumos associados ao produto
    $sql_insumos = "SELECT * FROM produto_insumos WHERE produto_id = :produto_id";
    $stmt_insumos = $db->prepare($sql_insumos);
    $stmt_insumos->bindParam(':produto_id', $produto_id);
    $stmt_insumos->execute();
    $insumos_produto = $stmt_insumos->fetchAll(PDO::FETCH_ASSOC);
    
    // Cria um array para verificar os insumos que estão associados ao produto
    $insumos_selecionados = [];
    foreach ($insumos_produto as $insumo_produto) {
        $insumos_selecionados[$insumo_produto['insumo_id']] = $insumo_produto['quantidade'];
    }
}

// Atualiza os dados do produto
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $margem_lucro = $_POST['margem_lucro'];

    // Atualiza os insumos do produto
    if (isset($_POST['insumos'])) {
        $insumos_selecionados = $_POST['insumos'];
        $quantidades = $_POST['quantidades'];

        // Deleta os insumos atuais para atualizar
        $sql_delete_insumos = "DELETE FROM produto_insumos WHERE produto_id = :produto_id";
        $stmt_delete = $db->prepare($sql_delete_insumos);
        $stmt_delete->bindParam(':produto_id', $produto_id);
        $stmt_delete->execute();

        // Insere os novos insumos
        foreach ($insumos_selecionados as $index => $insumo_id) {
            $quantidade = $quantidades[$index];
            $sql_insert_insumo = "INSERT INTO produto_insumos (produto_id, insumo_id, quantidade) 
                                  VALUES (:produto_id, :insumo_id, :quantidade)";
            $stmt_insert = $db->prepare($sql_insert_insumo);
            $stmt_insert->bindParam(':produto_id', $produto_id);
            $stmt_insert->bindParam(':insumo_id', $insumo_id);
            $stmt_insert->bindParam(':quantidade', $quantidade);
            $stmt_insert->execute();
        }
    }

    // Calcula o custo com base nos insumos selecionados e atualiza o produto
    $custo_total = 0;
    foreach ($insumos_selecionados as $index => $insumo_id) {
        $quantidade = $quantidades[$index];

        // Pega o preço unitário do insumo
        $sql_preco_insumo = "SELECT preco FROM insumos WHERE id = :insumo_id";
        $stmt_preco = $db->prepare($sql_preco_insumo);
        $stmt_preco->bindParam(':insumo_id', $insumo_id);
        $stmt_preco->execute();
        $insumo = $stmt_preco->fetch(PDO::FETCH_ASSOC);

        // Calcula o custo com base na quantidade
        $custo_total += $insumo['preco'] * $quantidade;
    }

    $preco_venda = $custo_total + ($custo_total * ($margem_lucro / 100));

    // Atualiza o produto no banco de dados
    $sql_update_produto = "UPDATE produtos SET nome = :nome, descricao = :descricao, custo = :custo, preco_venda = :preco_venda, margem_lucro = :margem_lucro WHERE id = :produto_id";
    $stmt_update = $db->prepare($sql_update_produto);
    $stmt_update->bindParam(':nome', $nome);
    $stmt_update->bindParam(':descricao', $descricao);
    $stmt_update->bindParam(':custo', $custo_total);
    $stmt_update->bindParam(':preco_venda', $preco_venda);
    $stmt_update->bindParam(':margem_lucro', $margem_lucro);
    $stmt_update->bindParam(':produto_id', $produto_id);
    
    if ($stmt_update->execute()) {
        $sucesso = "Produto atualizado com sucesso!";
    } else {
        $erro = "Erro ao atualizar o produto!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Editar Produto</h1>
        <form method="POST" action="editar_produto.php?id=<?= $produto_id ?>">
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" value="<?= $produto['nome'] ?>" required>

            <label for="descricao">Descrição do Produto:</label>
            <textarea id="descricao" name="descricao" required><?= $produto['descricao'] ?></textarea>

            <label for="margem_lucro">Margem de Lucro (%):</label>
            <input type="number" step="any" id="margem_lucro" name="margem_lucro" value="<?= $produto['margem_lucro'] ?>" required>

            <h3>Selecionar Insumos:</h3>
            <?php foreach ($todos_insumos as $insumo): ?>
                <div>
                    <input type="checkbox" name="insumos[]" value="<?= $insumo['id'] ?>" <?= isset($insumos_selecionados[$insumo['id']]) ? 'checked' : '' ?>>
                    <?= $insumo['nome'] ?> - R$ <?= $insumo['preco'] ?>
                    <label for="quantidade">Quantidade:</label>
                    <input type="number" step="any" name="quantidades[]" value="<?= $insumos_selecionados[$insumo['id']] ?? 1 ?>" required>
                </div>
            <?php endforeach; ?>

            <button type="submit">Atualizar Produto</button>
        </form>

        <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
        <?php if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>"; ?>
        <a href="index.php" class="button voltar">Voltar à Página Inicial</a>
    </div>
</body>
</html>
