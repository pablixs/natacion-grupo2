</main>
<footer class="text-center mt-5 py-3 border-top">
    <!-- <p>&copy; <?= date('Y') ?> - Aplicaciones WEB - Profesor lic. Juan Pablo Cesarini</p> -->
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="js/services/datatables.js"></script> -->
<script src="<?= Env::get('ASSET_URL') ?>/js/services/datatables.min.js"></script>

<script type="module" src="<?= rtrim(Env::get('ASSET_URL'), '/') ?>/js/modules/MainAdmin.js"></script>
</body>

</html>