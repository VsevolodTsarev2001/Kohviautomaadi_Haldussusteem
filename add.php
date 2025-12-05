<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lisa jook</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="flex-center">
    <div class="card-container">
        <h3>Lisa jook</h3>
        <form action="api_add.php" method="POST">
            <label>Nimi</label>
            <input name="jooginimi" required>
            <label>Kogus (ml)</label>
            <input name="kogus" type="number" required>
            <label>Kategooria</label>
            <select name="grupp">
                <option value="hot-drinks">Kuum</option>
                <option value="cold-drinks">Külm</option>
                <option value="juices">Mahl</option>
                <option value="tee">Tee</option>
                <option value="limonaad">Limonaad</option>
                <option value="alcohol">Alkohoolne</option>
                <option value="specials">Spetsiaalsed</option>
            </select>
            <label>Tops</label>
            <input name="topsitüüp">
            <label>Maksmine</label>
            <input name="maksmisviis">
            <button type="submit" class="btn">Lisa</button>
        </form>
    </div>
</div>
</body>
</html>
