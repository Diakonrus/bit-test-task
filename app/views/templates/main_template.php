<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <title><?= $this->title ?></title>
        <link href="<?= $this->path ?>css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= $this->path ?>css/custom.css" rel="stylesheet">
        
        <script src="<?= $this->path ?>js/jquery-1.11.1.js"></script>
        <script src="<?= $this->path ?>js/bootstrap.min.js"></script>
        <script src="<?= $this->path ?>js/main.js"></script>
    </head>
    <body>
        <div class="content">
            <?php include 'app/views/' . $content_view; ?>
        </div>
    </body>
</html>