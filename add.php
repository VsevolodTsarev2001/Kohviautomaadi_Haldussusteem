<!DOCTYPE html>
<html>
<body>
<h2>Lisa jook</h2>

<form action="api_add.php" method="POST">

    Nimi: <input name="jooginimi" required><br><br>

    Kogus (ml): <input name="kogus" type="number" required><br><br>

    Kategooria:
    <select name="grupp">
        <option value="hot-drinks">Kuum</option>
        <option value="cold-drinks">Külm</option>
        <option value="juices">Mahl</option>
        <option value="tee">Tee</option>
        <option value="limonaad">Limonaad</option>
        <option value="alcohol">Alkohoolne</option>
        <option value="specials">Spetsiaalsed</option>
    </select><br><br>

    Tops: <input name="topsitüüp"><br><br>

    Maksmine: <input name="maksmisviis"><br><br>

    <button type="submit">Lisa</button>

</form>

</body>
</html>
