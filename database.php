<?php
try {
    $db = new PDO('sqlite:precificacao.db'); // O banco de dados será criado como um arquivo 'precificacao.db'
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
    exit();
}
try {
    $db->exec("CREATE TABLE IF NOT EXISTS insumos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        quantidade FLOAT NOT NULL,
        preco FLOAT NOT NULL
    )");
} catch (PDOException $e) {
        echo "Erro ao criar a tabela: " . $e->getMessage();
    }
try {
    $db->exec("CREATE TABLE IF NOT EXISTS produtos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nome TEXT NOT NULL,
        descricao TEXT NOT NULL,
        custo FLOAT NOT NULL,
        preco_venda FLOAT NOT NULL,
        margem_lucro FLOAT NOT NULL
    )");
} catch (PDOException $e) {
        echo "Erro ao criar a tabela: " . $e->getMessage();
    }
try {
    // Criação da tabela produto_insumos para associar produtos e insumos com suas quantidades
    $db->exec("CREATE TABLE IF NOT EXISTS produto_insumos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        produto_id INTEGER NOT NULL,
        insumo_id INTEGER NOT NULL,
        quantidade FLOAT NOT NULL,
        FOREIGN KEY (produto_id) REFERENCES produtos(id),
        FOREIGN KEY (insumo_id) REFERENCES insumos(id)
    )");
} catch (PDOException $e) {
    echo "Erro ao criar a tabela: " . $e->getMessage();
    }
?>
