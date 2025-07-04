<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("util/Conexao.php");
$con = Conexao::getConexao();

$msgErro = '';
$erros = [];

$nome_perito = '';
$tipo = '';
$local = '';
$data = '';
$status = '';
$evidencias = '';
$observacao = '';

$sql = "SELECT * FROM laudos";
$stm = $con->prepare($sql);
$stm->execute();
$laudos = $stm->fetchAll();

if (isset($_POST["nome_perito"])) {
    $nome_perito = trim($_POST["nome_perito"]);
    $tipo = $_POST["tipo"];
    $local = trim($_POST["local"]);
    $data = trim($_POST["data"]);
    $status = $_POST["status"];
    $evidencias = trim($_POST["evidencias"]);
    $observacao = trim($_POST["observacao"]);

    if (!$nome_perito) array_push($erros, "• Informe o nome do perito.");
    if (!$tipo) array_push($erros, "• Selecione o tipo de perícia.");
    if (!$local) array_push($erros, "• Informe o local da perícia.");
    if (!$data) array_push($erros, "• Informe a data.");
    if (!$status) array_push($erros, "• Selecione o status.");
    if (!$evidencias) array_push($erros, "• Descreva as evidências.");
    if ($observacao == true && strlen($observacao) <= 2) array_push($erros, "• Observação muito curta (min/ 3 caracteres).");

    if (strlen($nome_perito) < 3)
        array_push($erros, "• Nome do perito muito curto (min/ 3 caracteres).");
    if ($data > 2026)
        array_push($erros, "• Data inválida.");


    if (count($erros) == 0) {
        $sql = "INSERT INTO laudos (nome_perito, tipo_pericia, local_pericia, data, status, evidencias, observacao)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stm = $con->prepare($sql);
        $stm->execute([$nome_perito, $tipo, $local, $data, $status, $evidencias, $observacao]);

        header("Location: index.php");
        exit();
    } else {
        $msgErro = implode("<br>", $erros);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Registro de Perícias Criminais</title>
    <style>
        /* Animaçãozinha só porque sim.  */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            animation: fadeIn 1s ease-in-out;
        }

        .header {
            background-color: rgb(34, 32, 91);
            color: white;
            display: flex;
            align-items: center;
            padding: 20px;
        }

        .header img {
            height: 125px;
            margin-right: 40px;
        }

        .header-text {
            text-align: center;
            flex-grow: 1;
            font-family: cursive;
            padding-right: 10%;
        }

        .container {
            display: flex;
            justify-content: space-between;
            padding: 30px;
            gap: 20px;
        }

        .formulario {
            width: 48%;
            background-color: #fffdfa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .formulario input[type="text"],
        .formulario input[type="date"],
        .formulario select,
        .formulario textarea {
            color: rgb(23, 48, 86);
            width: 80%;
            min-height: 38px;
            padding: 8px 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .formulario textarea {
            min-height: 80px;
            resize: vertical;
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .formulario input[type="date"] {
            height: 38px;
            line-height: 1.2;
        }

        .formulario button {
            background-color: rgb(23, 48, 86);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            margin-left: auto;
            margin-right: auto;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .formulario button:hover {
            background-color: #1f3566;
            transform: scale(1.05);
        }

        #divErro {
            background-color: rgb(243, 196, 200);
            border: 1px solid  rgb(146, 36, 47);
            color: rgb(117, 37, 45);
            font-weight: bold;
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            width: 90%;
            max-width: 500px; 
            margin-left: auto;
            margin-right: auto;
            display: block;
        }



        .tabela-laudos {
            width: 48%;
            background-color: #fffdfa;
            color: rgb(23, 48, 86);
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }

        .tabela-laudos h2 {
            text-align: center;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        tr {
            text-align: center;
            background-color: white;
        }

        th,
        td {
            border: 1px solid #bbb;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f4f4f4;
            text-align: center;
        }

        a {
            color: red;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        img {
            width: 5%;
            height: 20%;
            padding-left: 2%;
        }

    </style>
</head>

<body>

    <div class="header">
        <img src="img/LOGO-PC-PRreal.png" alt="Logo Polícia Científica do Paraná">
        <div class="header-text">
            <h1><strong>Polícia Científica do Paraná</strong></h1>
            <h3>Registro de Perícias Criminais</h3>
            <h4>Cadastro e listagem de laudos</h4>
        </div>
    </div>

    <div class="container">
        <div class="formulario">
            <?php if (!empty($msgErro)): ?>
                <div id="divErro"><strong>Corrija os seguintes erros:<br><br></strong><?= $msgErro ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <!-- Pesquisei se havia alguma forma de usar caracteres especiais pois estava dando erro, então achei esse 'htmlspecialchars'  -->
                <input type="text" name="nome_perito" placeholder="Nome do perito" value="<?= htmlspecialchars($nome_perito) ?>" />

                <select name="tipo">
                    <option value="">Tipo de perícia*</option>
                    <option value="AM" <?= $tipo == 'AM' ? 'selected' : '' ?>>Ambiental</option>
                    <option value="HM" <?= $tipo == 'HM' ? 'selected' : '' ?>>Homicídio</option>
                    <option value="CB" <?= $tipo == 'CB' ? 'selected' : '' ?>>Cibernética</option>
                    <option value="BT" <?= $tipo == 'BT' ? 'selected' : '' ?>>Balística</option>
                    <option value="OU" <?= $tipo == 'OU' ? 'selected' : '' ?>>Outros</option>
                </select>

                <input type="text" name="local" placeholder="Local da perícia" value="<?= htmlspecialchars($local) ?>" />
                <input type="date" name="data" value="<?= htmlspecialchars($data) ?>" />

                <select name="status">
                    <option value="">Status do laudo*</option>
                    <option value="EA" <?= $status == 'EA' ? 'selected' : '' ?>>Em andamento</option>
                    <option value="F" <?= $status == 'F' ? 'selected' : '' ?>>Finalizado</option>
                    <option value="P" <?= $status == 'P' ? 'selected' : '' ?>>Pendente</option>
                </select>

                <textarea name="evidencias" placeholder="Evidências encontradas"><?= htmlspecialchars($evidencias) ?></textarea>
                <textarea name="observacao" placeholder="Observações (opcional)"><?= htmlspecialchars($observacao) ?></textarea>

                <button type="submit">Gravar Laudo</button>
            </form>
        </div>

        <div class="tabela-laudos">
            <h2>Laudos Registrados</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Perito</th>
                    <th>Tipo</th>
                    <th>Local</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Evidências</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>

                <?php foreach ($laudos as $l): ?>
                    <tr>
                        <td><?= $l["id"] ?></td>
                        <td><?= htmlspecialchars($l["nome_perito"]) ?></td>
                        <td><?= $l["tipo_pericia"] ?></td>
                        <td><?= $l["local_pericia"] ?></td>
                        <td style="text-align: center;"><?= $l["data"] ?></td>
                        <td><?= $l["status"] ?></td>
                        <td><?= htmlspecialchars($l["evidencias"]) ?></td>
                        <td><?= trim($l["observacao"]) !== '' ? nl2br(htmlspecialchars($l["observacao"])) : '<strong>Nenhuma</strong>' ?></td>
                        <td><a href="excluir.php?id=<?= $l["id"] ?>" onclick="return confirm('Confirma a exclusão?');">Excluir</a></td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
    </div>
</body>
</html>
