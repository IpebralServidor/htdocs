<?php
echo '   
        <div class="modal fade" id="modalConfirmaReferencia" tabindex="-1" role="dialog" aria-labelledby="modalConfirmaReferencia" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="p-3">
                        <div class="modal-body fw-bold">
                            Digite a referência novamente: <span style="color: red">*</span>
                        </div>
                        <div class="mb-1">
                            <input type="text" class="form-control" id="novaReferencia">
                        </div>
                        <div class="mt-3">
                            <button id="btnConfirmarReferencia" class="btn btn-primary fw-bold w-100" style="background-color: var(--color-pad) !important; border-color: var(--color-pad) !important" data-dismiss="modal">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="button" class="btn btn-primary d-none" id="btnConfirmaReferencia" data-toggle="modal" data-target="#modalConfirmaReferencia">
            Launch demo modal
        </button>
    ';
