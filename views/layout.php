<?php
defined('BASE_PATH') || define('BASE_PATH', realpath(__DIR__ . '/..'));

// Carrega o header (que deve conter apenas <head> e abrir <body>)
require BASE_PATH . '/includes/header.php';
?>

<!-- Wrapper principal -->
<div id="wrapper">

    <?php require BASE_PATH . '/includes/sidebar.php'; ?>

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <?php require BASE_PATH . '/includes/navbar.php'; ?>

            <!-- Begin Page Content -->
            <div class="container-fluid">
                <?php
                if (isset($view) && file_exists($view)) {
                    require $view;
                } else {
                    echo '<p class="alert alert-danger">View não encontrada</p>';
                }
                ?>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <?php require BASE_PATH . '/includes/footer.php'; ?>

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Wrapper -->

</body>

</html>
<?php
