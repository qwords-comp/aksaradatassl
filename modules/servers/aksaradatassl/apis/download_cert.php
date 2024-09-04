<?php

$certificate = $_GET['certificate'];

// Set headers to force download
header('Content-Description: File Transfer');
header('Content-Type: application/x-x509-ca-cert');
header('Content-Disposition: attachment; filename="certificate.cert"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($certificate));

// Output the certificate content
echo $certificate;