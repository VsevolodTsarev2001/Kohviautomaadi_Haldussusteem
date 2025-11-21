<?php
require 'functions.php';
$xml = loadXml();
$drink = findDrinkById($xml, $_GET['id']);
?>

<!DOCTYPE html>
<html>
<body>
<h2>Muuda jooki</h2>

<form action="api_edit.php" method="POST">

    <input type="hidden" name="id" value="<?= $drink['id'] ?>">

    Nimi: <input name="jooginimi" value="<?= htmlspecialchars($drink->jooginimi) ?>"><br><br>

    Kogus (ml): <input name="kogus" value="<?= htmlspecialchars($drink->kogus) ?>"><br><br>

    Kategooria:
    <select name="grupp">
        <option value="hot-drinks" <?= $drink->xpath('ancestor::grupp')[0]['id']=='hot-drinks'?'selected':''?>>Kuum</option>
        <option value="cold-drinks" <?= $drink->xpath('ancestor::grupp')[0]['id']=='cold-drinks'?'selected':''?>>Külm</option>
        <option value="juices" <?= $drink->xpath('ancestor::grupp')[0]['id']=='juices'?'selected':''?>>Mahl</option>
        <option value="tee" <?= $drink->xpath('ancestor::grupp')[0]['id']=='tee'?'selected':''?>>Tee</option>
        <option value="limonaad" <?= $drink->xpath('ancestor::grupp')[0]['id']=='limonaad'?'selected':''?>>Limonaad</option>
        <option value="alcohol" <?= $drink->xpath('ancestor::grupp')[0]['id']=='alcohol'?'selected':''?>>Alkohoolne</option>
        <option value="specials" <?= $drink->xpath('ancestor::grupp')[0]['id']=='specials'?'selected':''?>>Spetsiaalsed</option>
    </select><br><br>

    Tops: <input name="topsitüüp" value="<?= htmlspecialchars($drink->topsitüüp) ?>"><br><br>

    Maksmine: <input name="maksmisviis" value="<?= htmlspecialchars($drink->maksmisviis) ?>"><br><br>

    <button type="submit">Salvesta</button>

</form>

</body>
</html>
