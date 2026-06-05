</main>
<footer class="primary-bg text-white mt-auto pt-5 pb-4 mt-auto">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa-solid fa-droplet text-info fs-4 me-2"></i>
                        <h5 class="mb-0 fw-bold">Alpine Natación</h5>
                    </div>
                    <p class="small text-white-50 mb-3">Escuela de natación especializada en Buenos Aires, Argentina.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50 fs-5"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#" class="text-white-50 fs-5"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="text-white-50 fs-5"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="text-white-50 fs-5"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold mb-3">Navegación</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Inicio</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Clases</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Alumnos</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Profesores</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Página principal</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h6 class="fw-bold mb-3">Contacto</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li class="mb-2"><i class="fa-solid fa-location-dot me-2 text-info"></i>Av. Libertador 1234, Ciudad</li>
                        <li class="mb-2"><i class="fa-solid fa-phone me-2 text-info"></i>(011) 1234-5678</li>
                        <li class="mb-2"><i class="fa-solid fa-envelope me-2 text-info"></i>info@alpinenatacion.com</li>
                        <li class="mb-2"><i class="fa-solid fa-clock me-2 text-info"></i>Lun-Sáb: 7:00 - 21:00</li>
                    </ul>
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