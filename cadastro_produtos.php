<?php
require 'database.php'; // Inclui a conexão com o banco de dados

// Consulta para pegar todos os insumos
$sql = "SELECT * FROM insumos";
$stmt = $db->query($sql);
$insumos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $margem_lucro = $_POST['margem_lucro'];
    
    // Calculando o custo com base nos insumos selecionados
    $custo = 0;
    if (isset($_POST['insumos'])) {
        foreach ($_POST['insumos'] as $key => $id_insumo) {
            $quantidade = $_POST['quantidade'][$key]; // Captura a quantidade para cada insumo
            
            // Obtendo o preço do insumo
            $sql_insumo = "SELECT preco FROM insumos WHERE id = :id";
            $stmt_insumo = $db->prepare($sql_insumo);
            $stmt_insumo->bindParam(':id', $id_insumo);
            $stmt_insumo->execute();
            $insumo = $stmt_insumo->fetch(PDO::FETCH_ASSOC);
            
            $custo += ($insumo['preco'] * $quantidade); // Adiciona o custo proporcional ao total
        }
    }

    $preco_venda = $custo + ($custo * ($margem_lucro / 100));

    $sql = "INSERT INTO produtos (nome, descricao, custo, preco_venda, margem_lucro) VALUES (:nome, :descricao, :custo, :preco_venda, :margem_lucro)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':descricao', $descricao);
    $stmt->bindParam(':custo', $custo);
    $stmt->bindParam(':preco_venda', $preco_venda);
    $stmt->bindParam(':margem_lucro', $margem_lucro);

    if ($stmt->execute()) {
        $sucesso = "Produto cadastrado com sucesso!";
    } else {
        $erro = "Erro ao cadastrar o produto!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produtos</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Estilo para o formulário */
        .container form {
            display: flex;
            flex-direction: column;
            gap: 15px; /* Espaçamento uniforme entre os campos */
        }

        input[type="text"], input[type="number"], textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 100%; /* Preenche todo o espaço disponível */
            box-sizing: border-box; /* Para incluir o padding e border no total da largura */
        }

        textarea {
            height: 100px; /* Altura fixa para descrição */
        }

        button {
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .insumo-group {
            display: flex;
            align-items: center;
        }

        .insumo-group input[type="checkbox"] {
            margin-right: 10px; /* Espaçamento entre checkbox e label */
        }

        .insumo-label {
            flex-grow: 1; /* Faz a label ocupar o espaço disponível */
            margin: 0; /* Remove margens para uniformidade */
            text-align: left; /* Alinhamento à esquerda */
            font-weight: normal; /* Remove negrito */
        }

        input[type="number"] {
            width: 100px; /* Largura fixa para a quantidade */
            margin-left: 10px; /* Espaçamento entre a label e o campo de quantidade */
        }

        /* Alinhamento centralizado para a margem de lucro */
        #margem_lucro {
            text-align: center; /* Alinhamento central */
            width: auto; /* Ajusta a largura */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cadastrar Produtos</h1>
        <form method="POST" action="">
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="descricao">Descrição do Produto:</label>
            <textarea id="descricao" name="descricao" required></textarea>

            <label for="insumos">Selecionar Insumos:</label>
            <div id="insumos">
                <?php foreach ($insumos as $insumo): ?>
                    <div class="insumo-group">
                        <input type="checkbox" id="insumo_<?php echo $insumo['id']; ?>" name="insumos[]" value="<?php echo $insumo['id']; ?>">
                        <label class="insumo-label" for="insumo_<?php echo $insumo['id']; ?>">
                            <?php echo htmlspecialchars($insumo['nome'] . " - R$ " . number_format($insumo['preco'], 2, ',', '.')); ?>
                        </label>
                        <input type="number" step="any" name="quantidade[]" placeholder="Quantidade" min="0" value="0" required>
                    </div>
                <?php endforeach; ?>
            </div>

            <label for="margem_lucro">Margem de Lucro (%):</label>
            <input type="number" step="any" id="margem_lucro" name="margem_lucro" required>

            <button type="submit">Cadastrar Produto</button>
        </form>

        <?php if (isset($erro)) echo "<p style='color: red;'>$erro</p>"; ?>
        <?php if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>"; ?>
        <a href="index.php" class="button voltar">Voltar à Página Inicial</a>
    </div>
</body>
</html>
