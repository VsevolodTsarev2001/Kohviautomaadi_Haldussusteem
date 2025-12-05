<form action="api_delete.php" method="POST" class="flex-center">
    <div class="card-container">
        <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
        <p>Oled kindel, et soovid kustutada?</p>
        <button type="submit">Jah, kustuta</button>
        <a class="button" href="index.php" style="display:block; text-align:center; margin-top:10px;">Tagasi</a>
    </div>
</form>
