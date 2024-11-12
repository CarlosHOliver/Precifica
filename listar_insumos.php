<?php
require 'database.php'; // Inclui a conexão com o banco de dados

// Consulta para pegar todos os insumos
$sql = "SELECT * FROM insumos";
$stmt = $db->query($sql);
$insumos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Insumos</title>
    <link rel="stylesheet" href="style.css"> <!-- Inclui o CSS existente -->
</head>
<body>
    <div class="container">
        <h1>Listar Insumos</h1>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($insumos)): ?>
                    <tr>
                        <td colspan="4">Nenhum insumo cadastrado.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($insumos as $insumo): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($insumo['nome']); ?></td>
                            <td><?php echo htmlspecialchars($insumo['quantidade']); ?></td>
                            <td>R$ <?php echo number_format($insumo['preco'], 2, ',', '.'); ?></td>
                            <td>
                                <a href="editar_insumo.php?id=<?php echo $insumo['id']; ?>">Editar</a> |
                                <a href="deletar_insumo.php?id=<?php echo $insumo['id']; ?>" onclick="return confirm('Tem certeza que deseja deletar este insumo?');">Deletar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="cadastro_insumos.php" class="button voltar">Cadastrar Novo Insumo</a>
        <a href="index.php" class="button voltar">Voltar à Página Inicial</a>
    </div>

    <footer>
        <p><strong>(c) Dobradura Artes e Personalizados - 2024 - Desenvolvido por Carlos Henrique C. de Oliveira | Projeto de Extensão I - Descomplica Faculdade Digital</strong></p>
    </footer>
</body>
</html>
