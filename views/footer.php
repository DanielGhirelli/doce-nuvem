</main>
<script src="js/cart.js"></script>
<script src="js/validation.js"></script>
<?php foreach ($scripts as $s): ?>
<script src="js/<?= e($s) ?>.js"></script>
<?php endforeach; ?>
</body>
</html>
