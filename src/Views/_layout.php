<?= view('CI4\Auth\Views\_header') ?>

<!-- Toast Container -->
<div aria-live="polite" aria-atomic="true" class="position-relative" style="z-index:9999;">
    <!-- Position it: -->
    <!-- - `.toast-container` for spacing between toasts -->
    <!-- - `.position-absolute`, `top-0` & `end-0` to position the toasts in the upper right corner -->
    <!-- - `.p-3` to prevent the toasts from sticking to the edge of the container  -->
    <div class="toast-container position-absolute top-0 end-0 p-3">

        <?php
        //
        // Add your toast here
        //
        // echo bs5_toast([
        //     'title' => 'Boom',
        //     'time' => date('Y-m-d H:m'),
        //     'body' => 'This is a Toast message',
        //     'style' => 'info',
        //     'delay' => 5000,
        // ]);
        ?>

    </div>
</div>
<!-- /Toast Container -->

<main role="main" class="container pt-5">

    <?= $this->renderSection('main') ?>

</main>
<!-- /.container -->

<?= view('CI4\Auth\Views\_footer') ?>