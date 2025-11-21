<?php
require 'functions.php';

$xml = loadXml();

// ----- FILTERS -----
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// ----- COLLECT DRINKS -----
$drinks = [];
foreach ($xml->grupp as $g) {
    if ($category !== '' && (string)$g['id'] !== $category) continue;

    if (!$g->joogid) continue;
    foreach ($g->joogid->jook as $j) {
        $j->_grupp = (string)$g['id']; // временное поле для фильтра и вывода
        $drinks[] = $j;
    }
}

// ----- APPLY SEARCH FILTER -----
if ($search !== '') {
    $drinks = array_filter($drinks, function($d) use ($search) {
        return strpos(strtolower((string)$d->jooginimi), $search) !== false;
    });
}

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Joogid</title>
    <style>
        body { font-family: Arial; background:#f0f0f0; padding:20px; }
        table { border-collapse: collapse; width:100%; background:white; }
        th, td { padding:8px 12px; border:1px solid #ccc; }
        th { background:#333; color:white; }
        input, select { padding:5px; margin:4px; }
        .button { padding:6px 12px; background:#444; color:white; text-decoration:none; }
    </style>
</head>
<body>

<h1>Joogid</h1>

<form id="filterForm" method="GET">
    Otsi: <input type="text" name="search" id="searchInput" value="<?= htmlspecialchars($search) ?>">
    Kategooria:
    <select name="category" id="categorySelect">
        <option value="">Kõik</option>
        <?php foreach ($xml->grupp as $g): ?>
            <option value="<?= $g['id'] ?>" <?= $category==$g['id']?'selected':'' ?>>
                <?= ucfirst((string)$g['id']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<br>

<table>
    <tr>
        <th>ID</th>
        <th>Nimi</th>
        <th>Kogus (ml)</th>
        <th>Tops</th>
        <th>Maksmine</th>
        <th>Kategooria</th>
        <th>Toimingud</th>
    </tr>

    <?php foreach ($drinks as $d): ?>
        <tr>
            <td><?= $d['id'] ?></td>
            <td><?= htmlspecialchars($d->jooginimi) ?></td>
            <td><?= htmlspecialchars($d->kogus) ?></td>
            <td><?= htmlspecialchars($d->topsitüüp) ?></td>
            <td><?= htmlspecialchars($d->maksmisviis) ?></td>
            <td><?= htmlspecialchars($d->_grupp) ?></td>
            <td>
                <a class="button" href="edit.php?id=<?= $d['id'] ?>">Muuda</a>
                <a class="button" href="delete.php?id=<?= $d['id'] ?>" onclick="return confirm('Kustutada?');">Kustuta</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<script>
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const form = document.getElementById('filterForm');

    // Фильтрация при вводе текста
    searchInput.addEventListener('input', () => {
        form.submit();
    });

    // Фильтрация при выборе категории
    categorySelect.addEventListener('change', () => {
        form.submit();
    });
</script>

</body>
</html>
