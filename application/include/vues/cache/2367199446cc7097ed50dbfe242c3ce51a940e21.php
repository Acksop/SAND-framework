<?php $__env->startSection('sidebar'); ?>
    ##parent-placeholder-19bd1503d9bad449304cc6b4e977b74bac6cc771##

    <p>This is appended to the master sidebar.</p>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <h1>Accueil</h1>
    <br /><br /><br />
    <?php echo e(templating_a); ?>::<?php echo e(templating_b); ?>::<?php echo e(templating_c); ?>


<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Dépots Git\MVC---Objet\application\include\vues\templating/accueil.blade.php ENDPATH**/ ?>