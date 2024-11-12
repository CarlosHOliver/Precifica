<?php
require 'database.php'; // Inclui a conexão com o banco de dados

// Consulta para pegar todos os produtos
$sql = "SELECT * FROM produtos";
$stmt = $db->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Produtos</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclui o CSS existente -->
</head>
<body>
    <div class="container">
        <h1>Listar Produtos</h1>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Custo</th>
                    <th>Preço de Venda</th>
                    <th>Margem de Lucro</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($produtos)): ?>
                    <tr>
                        <td colspan="6">Nenhum produto cadastrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
                            <td>R$<?php echo number_format($produto['custo'], 2, ',', '.'); ?></td>
                            <td>R$<?php echo number_format($produto['preco_venda'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($produto['margem_lucro']) . '%'; ?></td>
                            <td>
                                <a href="editar_produto.php?id=<?php echo $produto['id']; ?>">Editar</a> |
                                <a href="deletar_produto.php?id=<?php echo $produto['id']; ?>" onclick="return confirm('Tem certeza que deseja deletar este produto?');">Deletar</a> |
                                <a href="listar_produto_cadastrado.php?id=<?php echo $produto['id']; ?>">Listar Insumos</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="cadastro_produtos.php" class="button voltar">Cadastrar Novo Produto</a>
        <a href="index.php" class="button voltar">Voltar à Página Inicial</a>
    </div>

    <footer>
        <p><strong>(c) Dobradura Artes e Personalizados - 2024 - Desenvolvido por Carlos Henrique C. de Oliveira | Projeto de Extensão I - Descomplica Faculdade Digital</strong></p>
    </footer>
</body>
</html>
