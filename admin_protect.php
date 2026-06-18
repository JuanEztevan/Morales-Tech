<?php
session_name('morales_admin');
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['idAdmin'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'No autorizado.']);
        exit;
    }
    header("Location: login_staff.php");
    exit;
}