<?php
require_once "connexion.php";

$table = isset($_GET["table"]) ? $_GET["table"] : "";
$id    = isset($_GET["id"])    ? (int)$_GET["id"] : 0;
$msg   = "";

if ($table === "client") {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Commande WHERE numcli = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        $msg = "Impossible : ce client a des commandes en base.";
    } else {
        $conn->prepare("DELETE FROM Client WHERE numcli = ?")->execute([$id]);
        $msg = "Client n°$id supprime.";
    }
} elseif ($table === "commande") {
    $conn->prepare("DELETE FROM Commande WHERE numcom = ?")->execute([$id]);
    $msg = "Commande n°$id supprimee.";
} elseif ($table === "produit") {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Contenir WHERE numcrocs = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() > 0) {
        $msg = "Impossible : ce produit est present dans une commande.";
    } else {
        $conn->prepare("DELETE FROM Crocs WHERE numcrocs = ?")->execute([$id]);
        $msg = "Produit n°$id supprime.";
    }
}

$retour = ["client" => "index.php?section=clients", "commande" => "index.php?section=commandes", "produit" => "index.php?section=produits"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Suppression</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Suppression</h1>
<div class="msg"><?= htmlspecialchars($msg) ?></div>
<a href="<?= $retour[$table] ?? 'index.php' ?>">Retour</a>

</body>
</html>
