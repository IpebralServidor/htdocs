<?php

include 'WebClientPrint.php';

use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\InstalledPrinter;
use Neodynamic\SDK\Web\PrintFilePDF;
use Neodynamic\SDK\Web\PrintOrientation;
use Neodynamic\SDK\Web\ClientPrintJob;
use Neodynamic\SDK\Web\Sizing;

// Process request
// Generate ClientPrintJob? only if clientPrint param is in the query string
$urlParts = parse_url($_SERVER['REQUEST_URI']);

if (isset($urlParts['query'])) {
    $rawQuery = $urlParts['query'];
    parse_str($rawQuery, $qs);
    if (isset($qs[WebClientPrint::CLIENT_PRINT_JOB])) {
        $printerName = urldecode($qs['printerName']);
        $fileName = uniqid() . '.pdf';
        $filePath = $qs['filePath'];
        //Create a ClientPrintJob obj that will be processed at the client side by the WCPP
        $cpj = new ClientPrintJob();

        $myfile = new PrintFilePDF($filePath, $fileName, null);
        $myfile->printOrientation = PrintOrientation::Landscape;
        $cpj->printFile = $myfile;

        $cpj->clientPrinter = new InstalledPrinter($printerName);
        //Send ClientPrintJob back to the client
        echo $cpj->sendToClient();
    }
}
