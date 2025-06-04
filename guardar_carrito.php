<?php
session_start();
$input = file_get_contents('php://input');
$_SESSION['carrito'] = json_decode($input, true);
echo json_encode(['success' => true]);