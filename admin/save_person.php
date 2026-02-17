<?php
require_once '../config.php';
checkAdminAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize inputs
    $full_name      = sanitize($_POST['full_name']);
    $gender_sex     = sanitize($_POST['gender_sex']);
    $age            = sanitize($_POST['age']);
    $address        = sanitize($_POST['address']);
    $contact_number = sanitize($_POST['contact_number']);

    // Basic validation
    if (empty($full_name) || empty($gender_sex) || empty($age) || empty($address)) {
        redirect('criminal_cases.php');
    }

    // Insert into persons table
    $stmt = $conn->prepare("
        INSERT INTO persons (full_name, gender_sex, age, address, contact_number, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param("ssiss", $full_name, $gender_sex, $age, $address, $contact_number);
    $stmt->execute();
    $stmt->close();

    redirect('criminal_cases.php');
}
