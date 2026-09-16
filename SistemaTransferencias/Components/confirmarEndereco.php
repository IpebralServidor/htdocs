<?php
    echo '   
        <div class="modal fade" id="modalConfirmaEndereco" tabindex="-1" role="dialog" aria-labelledby="modalConfirmaEndereco" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="p-3">
                        <div class="modal-body fw-bold">
                            Digite o endereço novamente: <span style="color: red">*</span><span id="prodDelete"></span>
                        </div>
                        <div class="mb-1">
                            <input type="number" class="form-control" id="novoEndereco">
                        </div>
                        <div class="mt-3">
                            <button id="btnConfirmarEndereco" class="btn btn-primary fw-bold w-100" style="background-color: var(--color-pad) !important; border-color: var(--color-pad) !important" data-bs-dismiss="modal">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="button" class="btn btn-primary d-none" id="btnConfirmaEndereco" data-bs-toggle="modal" data-bs-target="#modalConfirmaEndereco">
            Launch demo modal
        </button>
    ';
?>


