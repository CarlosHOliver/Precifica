<?php
require 'database.php'; // Inclui a conexão com o banco de dados

// Verifica se o ID do produto foi passado
if (isset($_GET['id'])) {
    $produto_id = $_GET['id'];

    // Consulta para pegar os insumos do produto
    $sql_insumos = "
        SELECT i.nome AS insumo_nome, i.preco AS insumo_preco, pi.quantidade AS quantidade_utilizada
        FROM insumos AS i
        JOIN produto_insumos AS pi ON i.id = pi.insumo_id
        WHERE pi.produto_id = :produto_id
    ";
    $stmt_insumos = $db->prepare($sql_insumos);
    $stmt_insumos->bindParam(':produto_id', $produto_id);
    $stmt_insumos->execute();
    $insumos = $stmt_insumos->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para pegar o nome do produto
    $sql_produto = "SELECT nome FROM produtos WHERE id = :produto_id";
    $stmt_produto = $db->prepare($sql_produto);
    $stmt_produto->bindParam(':produto_id', $produto_id);
    $stmt_produto->execute();
    $produto = $stmt_produto->fetch(PDO::FETCH_ASSOC);
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
    <title>Listar Insumos do Produto</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclui o CSS existente -->
</head>
<body>
    <div class="container">
        <h1>Insumos do Produto: <?= htmlspecialchars($produto['nome']) ?></h1>

        <?php if (empty($insumos)): ?>
            <p>Nenhum insumo cadastrado para este produto.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Valor de Custo</th>
                        <th>Quantidade Utilizada</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $custo_total = 0; // Para calcular o custo total
                    foreach ($insumos as $insumo): 
                        $valor_total = $insumo['insumo_preco'] * $insumo['quantidade_utilizada'];
                        $custo_total += $valor_total;
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($insumo['insumo_nome']); ?></td>
                            <td>R$ <?php echo number_format($insumo['insumo_preco'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($insumo['quantidade_utilizada']); ?></td>
                            <td>R$ <?php echo number_format($valor_total, 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Custo Total</th>
                        <th>R$ <?php echo number_format($custo_total, 2, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        <?php endif; ?>

        <a href="listar_produtos.php" class="button voltar">Voltar</a>
        <a href="index.php" class="button voltar">Voltar à Página Inicial</a>
    </div>

    <footer>
        <p><strong>(c) Dobradura Artes e Personalizados - 2024 - Desenvolvido por Carlos Henrique C. de Oliveira | Projeto de Extensão I - Descomplica Faculdade Digital</strong></p>
    </footer>
</body>
</html>
