<?php
require_once "connexion.php";

$table = isset($_GET["table"]) ? $_GET["table"] : "";
$id    = isset($_GET["id"])    ? (int)$_GET["id"] : 0;
$msg   = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($table === "client") {
        $stmt = $conn->prepare("UPDATE Client SET nomcli = ?, adrcli = ? WHERE numcli = ?");
        $stmt->execute([trim($_POST["nomcli"]), trim($_POST["adrcli"]), $id]);
        $msg = "Client mis a jour.";
    } elseif ($table === "commande") {
        $stmt = $conn->prepare("UPDATE Commande SET datecom = ?, numcli = ? WHERE numcom = ?");
        $stmt->execute([$_POST["datecom"], (int)$_POST["numcli"], $id]);
        $msg = "Commande mise a jour.";
    } elseif ($table === "produit") {
        $stmt = $conn->prepare("UPDATE Crocs SET modele = ?, couleur = ?, prix = ?, numfour = ? WHERE numcrocs = ?");
        $stmt->execute([trim($_POST["modele"]), trim($_POST["couleur"]), floatval($_POST["prix"]), (int)$_POST["numfour"], $id]);
        $msg = "Produit mis a jour.";
    }
}

$data = [];
if ($table === "client") {
    $stmt = $conn->prepare("SELECT * FROM Client WHERE numcli = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif ($table === "commande") {
    $stmt = $conn->prepare("SELECT * FROM Commande WHERE numcom = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $clients = $conn->query("SELECT * FROM Client ORDER BY nomcli")->fetchAll(PDO::FETCH_ASSOC);
} elseif ($table === "produit") {
    $stmt = $conn->prepare("SELECT * FROM Crocs WHERE numcrocs = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    $fournisseurs = $conn->query("SELECT * FROM Fournisseur ORDER BY nomfour")->fetchAll(PDO::FETCH_ASSOC);
}

$retour = ["client" => "index.php?section=clients", "commande" => "index.php?section=commandes", "produit" => "index.php?section=produits"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Modifier - <?= htmlspecialchars($table) ?> n°<?= $id ?></h1>

<?php if ($msg) : ?><div class="msg"><?= $msg ?></div><?php endif; ?>

<?php if (!$data) : ?>
    <p>Enregistrement introuvable.</p>
<?php else : ?>
    <!-- Formulaire pour modifier un client -->

    <?php if ($table === "client") : ?>
    <form method="POST">
        <label>Nom</label>
        <input type="text" name="nomcli" value="<?= htmlspecialchars($data["nomcli"]) ?>" required>
        <label>Adresse</label>
        <input type="text" name="adrcli" value="<?= htmlspecialchars($data["adrcli"]) ?>" required>
        <button type="submit">Enregistrer</button>
    </form>
    <!-- Formulaire pour modifier une commande -->
    <?php elseif ($table === "commande") : ?>
    <form method="POST">
        <label>Date</label>
        <input type="date" name="datecom" value="<?= $data["datecom"] ?>" required>
        <label>Client</label>
        <select name="numcli">
            <?php foreach ($clients as $c) : ?>
            <option value="<?= $c["numcli"] ?>" <?= $c["numcli"] == $data["numcli"] ? "selected" : "" ?>>
                <?= htmlspecialchars($c["nomcli"]) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Enregistrer</button>
    </form>

    <!-- Formulaire pour modifier un produit -->
    <?php elseif ($table === "produit") : ?>
    <form method="POST">
        <label>Modele</label>
        <input type="text" name="modele" value="<?= htmlspecialchars($data["modele"]) ?>" required>
        <label>Couleur</label>
        <input type="text" name="couleur" value="<?= htmlspecialchars($data["couleur"]) ?>" required>
        <label>Prix</label>
        <input type="number" name="prix" step="0.01" value="<?= $data["prix"] ?>" required>
        <label>Fournisseur</label>
        <select name="numfour">
            <?php foreach ($fournisseurs as $f) : ?>
            <option value="<?= $f["numfour"] ?>" <?= $f["numfour"] == $data["numfour"] ? "selected" : "" ?>>
                <?= htmlspecialchars($f["nomfour"]) ?>
            </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Enregistrer</button>
    </form>
    <?php endif; ?>

<?php endif; ?>

<br><a href="<?= $retour[$table] ?? 'index.php' ?>">Retour</a>

</body>
</html>
