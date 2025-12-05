<?php
require 'functions.php';
$xml = loadXml();
$drink = findDrinkById($xml, $_GET['id']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Muuda jooki</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="flex-center">
    <div class="card-container">
        <h3>Muuda jooki</h3>
        <form action="api_edit.php" method="POST">
            <input type="hidden" name="id" value="<?= $drink['id'] ?>">

            <div class="input-group">
                <span class="icon">Nimi</span>
                <input name="jooginimi" type="text" placeholder="Jooogi nimi" value="<?= htmlspecialchars($drink->jooginimi) ?>" required>
            </div>

            <div class="input-group">
                <span class="icon">Kogus (ml)</span>
                <input name="kogus" type="number" placeholder="Kogus (ml)" value="<?= htmlspecialchars($drink->kogus) ?>" required>
            </div>

            <div class="input-group">
                <span class="icon">Kategooria</span>
                <select name="grupp" required>
                    <?php foreach ($xml->grupp as $g): ?>
                        <option value="<?= $g['id'] ?>" <?= $drink->xpath('ancestor::grupp')[0]['id']==$g['id']?'selected':'' ?>>
                            <?= ucfirst((string)$g['id']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="input-group">
                <span class="icon">Tops</span>
                <input name="topsitüüp" type="text" placeholder="Tops" value="<?= htmlspecialchars($drink->topsitüüp) ?>">
            </div>

            <div class="input-group">
                <span class="icon">Maksmine</span>
                <input name="maksmisviis" type="text" placeholder="Maksmisviis" value="<?= htmlspecialchars($drink->maksmisviis) ?>">
            </div>

            <button type="submit" class="btn">Salvesta</button>
        </form>
    </div>
</div>
</body>
</html>
