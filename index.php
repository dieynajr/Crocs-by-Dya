<?php
require_once "connexion.php";

$section = isset($_GET["section"]) ? $_GET["section"] : "clients";
$numcom  = isset($_GET["numcom"])  ? (int)$_GET["numcom"] : null;
?>
<!DOCTYPE html>
<html lang="fr">
    
<head>
    <meta charset="UTF-8">
    <title>Crocs by DYA</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Crocs by DYA - Administration</h1>

<nav>
    <a href="index.php?section=clients">Clients</a>
    <a href="index.php?section=commandes">Commandes</a>
    <a href="index.php?section=produits">Produits</a>
</nav>

<?php if ($section === "clients") : ?>

    <h2>Liste des clients</h2>
    <table>
        <tr><th>N°</th><th>Nom</th><th>Adresse</th><th>Actions</th></tr>
        <?php
        $clients = $conn->query("SELECT * FROM Client ORDER BY numcli")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($clients as $c) :
        ?>
        <tr>
            <td><?= $c["numcli"] ?></td>
            <td><?= htmlspecialchars($c["nomcli"]) ?></td>
            <td><?= htmlspecialchars($c["adrcli"]) ?></td>
            <td>
                <a class="btn" href="modifier.php?table=client&id=<?= $c["numcli"] ?>">Modifier</a>
                <a class="btn" href="supprimer.php?table=client&id=<?= $c["numcli"] ?>"
                   onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

<?php elseif ($section === "commandes") : ?>

    <?php if ($numcom) : ?>

        <h2>Detail de la commande n°<?= $numcom ?></h2>
        <a href="index.php?section=commandes">Retour aux commandes</a>
        <table>
            <tr><th>Modele</th><th>Couleur</th><th>Prix unit.</th><th>Quantite</th><th>Sous-total</th></tr>
            <?php
            $stmt = $conn->prepare(
                "SELECT cr.modele, cr.couleur, cr.prix, co.quantite, (cr.prix * co.quantite) AS stotal
                 FROM Contenir co
                 JOIN Crocs cr ON co.numcrocs = cr.numcrocs
                 WHERE co.numcom = ?"
            );
            $stmt->execute([$numcom]);
            $lignes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $total = 0;
            foreach ($lignes as $l) :
                $total += $l["stotal"];
            ?>
            <tr>
                <td><?= htmlspecialchars($l["modele"]) ?></td>
                <td><?= htmlspecialchars($l["couleur"]) ?></td>
                <td><?= $l["prix"] ?> EUR</td>
                <td><?= $l["quantite"] ?></td>
                <td><?= number_format($l["stotal"], 2) ?> EUR</td>
            </tr>
            <?php endforeach; ?>
            <tr><td colspan="4"><strong>Total</strong></td><td><strong><?= number_format($total, 2) ?> EUR</strong></td></tr>
        </table>

    <?php else : ?>

        <h2>Liste des commandes</h2>
        <table>
            <tr><th>N°</th><th>Date</th><th>Client</th><th>Actions</th></tr>
            <?php
            $commandes = $conn->query(
                "SELECT com.numcom, com.datecom, cli.nomcli
                 FROM Commande com
                 JOIN Client cli ON com.numcli = cli.numcli
                 ORDER BY com.numcom"
            )->fetchAll(PDO::FETCH_ASSOC);
            foreach ($commandes as $c) :
            ?>
            <tr>
                <td><?= $c["numcom"] ?></td>
                <td><?= $c["datecom"] ?></td>
                <td><?= htmlspecialchars($c["nomcli"]) ?></td>
                <td>
                    <a class="btn" href="index.php?section=commandes&numcom=<?= $c["numcom"] ?>">Voir detail</a>
                    <a class="btn" href="modifier.php?table=commande&id=<?= $c["numcom"] ?>">Modifier</a>
                    <a class="btn" href="supprimer.php?table=commande&id=<?= $c["numcom"] ?>"
                       onclick="return confirm('Supprimer cette commande ?')">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>

<?php elseif ($section === "produits") : ?>

    <h2>Liste des produits</h2>
    <table>
        <tr><th>N°</th><th>Modele</th><th>Couleur</th><th>Prix</th><th>Fournisseur</th><th>Actions</th></tr>
        <?php
        $produits = $conn->query(
            "SELECT cr.numcrocs, cr.modele, cr.couleur, cr.prix, f.nomfour
             FROM Crocs cr
             JOIN Fournisseur f ON cr.numfour = f.numfour
             ORDER BY cr.numcrocs"
        )->fetchAll(PDO::FETCH_ASSOC);
        foreach ($produits as $p) :
        ?>
        <tr>
            <td><?= $p["numcrocs"] ?></td>
            <td><?= htmlspecialchars($p["modele"]) ?></td>
            <td><?= htmlspecialchars($p["couleur"]) ?></td>
            <td><?= $p["prix"] ?> EUR</td>
            <td><?= htmlspecialchars($p["nomfour"]) ?></td>
            <td>
                <a class="btn" href="modifier.php?table=produit&id=<?= $p["numcrocs"] ?>">Modifier</a>
                <a class="btn" href="supprimer.php?table=produit&id=<?= $p["numcrocs"] ?>"
                   onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

<?php endif; ?>

</body>
</html>
