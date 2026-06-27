<?php 
/** @var string $module */

?>
</main>
<footer class="primary-bg text-white mt-auto pt-5 pb-4 mt-auto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="mb-0 fw-bold">Alpine Natación</h5>
                    </div>
                    <p class="small text-white-50 mb-3">Escuela de natación especializada en Buenos Aires, Argentina.</p>

                </div>


        

            </div>
            <hr class="my-4 border-secondary">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="small text-white-50 mb-0">&copy; <?= date('Y') ?>  Alpine Natación</p>
                </div>
            </div>
        </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="<?= rtrim(Env::get('ASSET_URL'), '/') ?>/js/modules/MainSwimmers.js"></script>
</body>

</html>