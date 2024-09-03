<?php

$ca_bundle = $_GET['ca_bundle'];

// Set headers to force download
header('Content-Description: File Transfer');
header('Content-Type: application/x-x509-ca-cert');
header('Content-Disposition: attachment; filename="ca_bundle.cert"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($ca_bundle));

// Output the certificate content
echo $ca_bundle;