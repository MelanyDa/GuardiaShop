<?php
$conn = new mysqli("localhost", "root", "", "guardiashop");
if ($conn->connect_error) {
    http_response_code(500);
    exit;
}

?>