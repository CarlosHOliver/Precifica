<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];

    // Simulando um processo de cadastro (podemos adicionar um banco de dados depois)
    echo "<h1>Insumo Cadastrado com Sucesso!</h1>";
    echo "<p>Nome: " . htmlspecialchars($nome) . "</p>";
    echo "<p>Quantidade: " . htmlspecialchars($quantidade) . "</p>";
    echo "<p>Preço: R$" . htmlspecialchars($preco) . "</p>";
}
?>
