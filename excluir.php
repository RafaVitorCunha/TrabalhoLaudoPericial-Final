<?php
require_once("util/Conexao.php");

if (!isset($_GET["id"])) {
    echo "<h3>ID não informado!</h3>";
    echo "<a href='index.php'>Voltar</a>";
    exit();
}

$con = Conexao::getConexao();
$sql = "DELETE FROM laudos WHERE id = ?";
$stm = $con->prepare($sql);
$stm->execute([$_GET["id"]]);

header("Location: index.php");
exit();
?>
