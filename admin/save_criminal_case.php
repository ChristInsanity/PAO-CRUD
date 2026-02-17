<?php
require_once '../config.php';
checkAdminAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize inputs
    $person_id           = sanitize($_POST['person_id']);
    $control_number      = sanitize($_POST['control_number']);
    $party_represented   = sanitize($_POST['party_represented']);
    $case_no             = sanitize($_POST['case_no']);
    $court_body          = sanitize($_POST['court_body']);
    $title_of_the_case   = sanitize($_POST['title_of_the_case']);
    $cause_of_action     = sanitize($_POST['cause_of_action']);
    $status_of_case      = sanitize($_POST['status_of_case']);
    $last_action_taken   = sanitize($_POST['last_action_taken']);
    $cause_of_termination= sanitize($_POST['cause_of_termination']);
    $date_of_termination = sanitize($_POST['date_of_termination']);
    $location_type = isset($_POST['location_type']) 
    ? intval($_POST['location_type']) 
    : null;
    $date_of_confinement = sanitize($_POST['date_of_confinement']);
    $place_of_detention  = sanitize($_POST['place_of_detention']);
    $case_received       = sanitize($_POST['case_received']);

    // Basic validation
    if (empty($person_id) || empty($control_number) || empty($title_of_the_case) || empty($status_of_case)) {
        redirect('criminal_cases.php');
    }

    // Insert into criminal_cases table
    $stmt = $conn->prepare("
        INSERT INTO criminal_cases (
            person_id,
            control_number,
            party_represented,
            case_no,
            court_body,
            title_of_the_case,
            cause_of_action,
            status_of_case,
            last_action_taken,
            cause_of_termination,
            date_of_termination,
            location_type,
            date_of_confinement,
            place_of_detention,
            case_received,
            created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->bind_param(
    "issssssssssisss",
    $person_id,
    $control_number,
    $party_represented,
    $case_no,
    $court_body,
    $title_of_the_case,
    $cause_of_action,
    $status_of_case,
    $last_action_taken,
    $cause_of_termination,
    $date_of_termination,
    $location_type,
    $date_of_confinement,
    $place_of_detention,
    $case_received
);


    $stmt->execute();
    $stmt->close();

    redirect('criminal_cases.php');
}
