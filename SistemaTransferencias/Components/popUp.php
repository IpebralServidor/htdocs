<?php

    echo '   
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Alterar a quantidade máxima do local</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label for="inputQtdMaxLocal" class="form-label">Quantidade:</label>
                        <input type="number" id="inputQtdMaxLocal" class="form-control" name="inputQtdMaxLocal">
                        
                        <label for="inputLocalMaxLocal" class="form-label">Local:</label>
                        <input type="number" id="inputLocalMaxLocal" class="form-control" name="inputLocalMaxLocal">
                        
                        <label for="inputProdMaxLocal" class="form-label">Produto:</label>
                        <input type="number" id="inputProdMaxLocal" class="form-control" name="inputProdMaxLocal">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnAlterarMaxLocal">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    ';

?>


