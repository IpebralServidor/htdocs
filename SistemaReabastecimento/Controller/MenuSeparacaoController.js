$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const nunota = urlParams.get('nunota');
    $('#aplicar').click(function() {
        abrirNota(nunota, 'S')
    });
    $('#aplicar-sem-fila').click(function() {
        abrirNota(nunota , 'N')
    });
});

function abrirNota(nunota, fila) {
    window.location.href = 'reabastecimento.php?nunota=' + nunota + '&fila=' + fila;
}