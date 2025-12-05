<?php
require 'functions.php';
session_start();

$xml = loadXml();
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// ----- COLLECT DRINKS -----
$drinks = [];
foreach ($xml->grupp as $g) {
    if ($category !== '' && (string)$g['id'] !== $category) continue;
    if (!$g->joogid) continue;
    foreach ($g->joogid->jook as $j) {
        $j->_grupp = (string)$g['id'];
        $drinks[] = $j;
    }
}

// ----- APPLY SEARCH FILTER -----
if ($search !== '') {
    $drinks = array_filter($drinks, function($d) use ($search) {
        return strpos(strtolower((string)$d->jooginimi), $search) !== false;
    });
}

// ----- SORT BY NIMI (ALPHABETICALLY) -----
usort($drinks, function($a, $b) {
    return strcasecmp((string)$a->jooginimi, (string)$b->jooginimi);
});
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Kohviautomaat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h1>Kohviautomaat</h1>
    <?php if(isset($_SESSION['user'])): ?>
        <div>
            Tere, <?= htmlspecialchars($_SESSION['user']['username']) ?> |
            <a href="logout.php" class="btn">Logi välja</a>
        </div>
    <?php endif; ?>
</header>

<div class="card-container">
    <form id="filterForm" method="GET">
        <label>Otsi:</label>
        <input type="text" name="search" id="searchInput" value="<?= htmlspecialchars($search) ?>">
        <label>Kategooria:</label>
        <select name="category" id="categorySelect">
            <option value="">Kõik</option>
            <?php foreach ($xml->grupp as $g): ?>
                <option value="<?= $g['id'] ?>" <?= $category==$g['id']?'selected':'' ?>>
                    <?= ucfirst((string)$g['id']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Filtreeri</button>
    </form>
</div>

<table id="drinksTable">
    <thead>
    <tr>
        <th onclick="sortTable(0)">ID</th>
        <th onclick="sortTableByName()">Nimi</th>
        <th onclick="sortTable(2)">Kogus (ml)</th>
        <th onclick="sortTable(3)">Tops</th>
        <th onclick="sortTable(4)">Maksmine</th>
        <th onclick="sortTable(5)">Kategooria</th>
        <th>Toimingud</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($drinks as $d): ?>
        <tr>
            <td><?= $d['id'] ?></td>
            <td><?= htmlspecialchars($d->jooginimi) ?></td>
            <td><?= htmlspecialchars($d->kogus) ?></td>
            <td><?= htmlspecialchars($d->topsitüüp) ?></td>
            <td><?= htmlspecialchars($d->maksmisviis) ?></td>
            <td><?= htmlspecialchars($d->_grupp) ?></td>
            <td>
                <a class="btn" href="edit.php?id=<?= $d['id'] ?>">Muuda</a>
                <a class="btn" href="delete.php?id=<?= $d['id'] ?>" onclick="return confirm('Kustutada?');">Kustuta</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<script>
    function sortTableByName() {
        const table = document.getElementById("drinksTable");
        let rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        switching = true;
        dir = "asc"; // начальное направление сортировки

        while (switching) {
            switching = false;
            rows = table.rows;

            for (i = 1; i < rows.length - 1; i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[1]; // столбец "Nimi"
                y = rows[i + 1].getElementsByTagName("TD")[1];

                if (dir === "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir === "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }

            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount === 0 && dir === "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }

    function sortTable(n) {
        const table = document.getElementById("drinksTable");
        let switching = true;
        let dir = "asc";

        while(switching){
            switching = false;
            let rows = table.rows;
            for(let i=1;i<rows.length-1;i++){
                let shouldSwitch = false;
                let x = rows[i].getElementsByTagName("TD")[n];
                let y = rows[i+1].getElementsByTagName("TD")[n];
                let cmpX = isNaN(x.innerHTML) ? x.innerHTML.toLowerCase() : parseFloat(x.innerHTML);
                let cmpY = isNaN(y.innerHTML) ? y.innerHTML.toLowerCase() : parseFloat(y.innerHTML);
                if((dir==="asc" && cmpX>cmpY)||(dir==="desc" && cmpX<cmpY)){
                    shouldSwitch=true; break;
                }
            }
            if(shouldSwitch){
                rows[i].parentNode.insertBefore(rows[i+1], rows[i]);
                switching=true;
            } else if(dir==="asc"){
                dir="desc";
                switching=true;
            }
        }
    }
</script>

</body>
</html>
