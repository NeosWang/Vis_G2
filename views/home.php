<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$title = 'Home';
include $rootPath . '/views/tpl/header.php';
?>


<body>
    <?php
    include $rootPath . '/views/tpl/naviBar.php'
    ?>

    <div class="container">
        <div class="row">
            <div>todo list</div>
            <ul>

            </ul>
        </div>
    </div>
</body>

<?php include $rootPath . '/views/tpl/footer.php'; ?>