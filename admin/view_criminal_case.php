<?php
require_once '../config.php';
checkAdminAuth();

/* Validate person_id */
if (!isset($_GET['person_id']) || empty($_GET['person_id'])) {
    redirect('criminal_cases.php');
}

$person_id = intval($_GET['person_id']);

/* Fetch PERSON info */
$personStmt = $conn->prepare("
    SELECT 
        person_id,
        full_name,
        gender_sex,
        age,
        address,
        contact_number
    FROM persons
    WHERE person_id = ?
");
$personStmt->bind_param("i", $person_id);
$personStmt->execute();
$person = $personStmt->get_result()->fetch_assoc();
$personStmt->close();

if (!$person) {
    redirect('criminal_cases.php');
}

/* Fetch ALL criminal cases of this person */
$caseStmt = $conn->prepare("
    SELECT *
    FROM criminal_cases
    WHERE person_id = ?
    ORDER BY created_at DESC
");
$caseStmt->bind_param("i", $person_id);
$caseStmt->execute();
$cases = $caseStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Criminal Case Record | PAO Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
:root {
    --bg:#f1f1f1;
    --card:#ffffff;
    --primary:#2f80ed;
    --danger:#dc3545;
    --border:#e5e7eb;
    --text-dark:#1f2937;
    --text-muted:#6b7280;
}
body { background:var(--bg); font-family:'Segoe UI',sans-serif; }
.card-box {
    background:var(--card);
    border:1px solid var(--border);
    border-radius:14px;
    padding:20px;
    margin-bottom:20px;
}
.section-title { font-weight:700; color:var(--text-dark); }
.case-card {
    border-left:6px solid var(--primary);
    border-radius:10px;
}
.case-card.terminated { border-left-color:var(--danger); }
.badge-pending { background:var(--primary); }
.badge-terminated { background:var(--danger); }
.label { font-weight:600; color:var(--text-dark); }
.value { color:var(--text-muted); }
</style>
</head>

<body>
<div class="container my-4">

<!-- Header -->
<div class="mb-3">
    <h3 class="fw-bold">Criminal Case Record</h3>
    <div class="text-muted">Dashboard / Criminal Cases / <?= htmlspecialchars($person['full_name']) ?></div>
</div>

<!-- PERSON INFORMATION -->
<div class="card-box">
    <h5 class="section-title mb-3">Person Information</h5>
    <div class="row g-3">
        <div class="col-md-4"><span class="label">Full Name:</span> <span class="value"><?= htmlspecialchars($person['full_name']) ?></span></div>
        <div class="col-md-2"><span class="label">Gender:</span> <span class="value"><?= htmlspecialchars($person['gender_sex']) ?></span></div>
        <div class="col-md-2"><span class="label">Age:</span> <span class="value"><?= htmlspecialchars($person['age']) ?></span></div>
        <div class="col-md-4"><span class="label">Contact:</span> <span class="value"><?= htmlspecialchars($person['contact_number']) ?></span></div>
        <div class="col-md-12"><span class="label">Address:</span> <span class="value"><?= htmlspecialchars($person['address']) ?></span></div>
    </div>
</div>

<!-- CRIMINAL CASES -->
<div class="card-box">
<h5 class="section-title mb-3">Criminal Cases</h5>

<?php if ($cases->num_rows > 0): ?>
<div class="accordion" id="caseAccordion">

<?php $i=0; while($case=$cases->fetch_assoc()):
$isTerminated = ($case['status_of_case'] === 'Terminated');
?>

<div class="accordion-item mb-3 case-card <?= $isTerminated?'terminated':'' ?>">
<h2 class="accordion-header">
<button class="accordion-button collapsed" type="button"
        data-bs-toggle="collapse" data-bs-target="#case<?= $i ?>">
    <strong class="me-2">Control No:</strong> <?= htmlspecialchars($case['control_number']) ?>
    <span class="badge ms-3 <?= $isTerminated?'badge-terminated':'badge-pending' ?>">
        <?= htmlspecialchars($case['status_of_case']) ?>
    </span>
</button>
</h2>

<div id="case<?= $i ?>" class="accordion-collapse collapse">
<div class="accordion-body">

<!-- Case Identification -->
<h6 class="fw-bold">Case Identification</h6>
<div class="row mb-2">
    <div class="col-md-3"><span class="label">Case No:</span> <?= $case['case_no'] ?: '—' ?></div>
    <div class="col-md-3"><span class="label">Court:</span> <?= $case['court_body'] ?: '—' ?></div>
    <div class="col-md-6"><span class="label">Title:</span> <?= $case['title_of_the_case'] ?></div>
</div>
<div class="mb-2"><span class="label">Cause of Action:</span> <?= $case['cause_of_action'] ?: '—' ?></div>

<hr>

<!-- Detention -->
<h6 class="fw-bold">Detention & Location</h6>
<div class="row mb-2">
    <div class="col-md-3">
        <span class="label">Location Type:</span>
        <?= isset($case['location_type']) ? ($case['location_type'] == 1 ? 'Urban' : 'Rural') : '—' ?>
    </div>
    <div class="col-md-3"><span class="label">Date Confined:</span> <?= $case['date_of_confinement'] ?: '—' ?></div>
    <div class="col-md-6"><span class="label">Place of Detention:</span> <?= $case['place_of_detention'] ?: '—' ?></div>
</div>
<div class="mb-2"><span class="label">Case Received:</span> <?= $case['case_received'] ?: '—' ?></div>


<hr>

<!-- Status -->
<h6 class="fw-bold">Case Status</h6>
<div class="mb-2"><span class="label">Last Action Taken:</span> <?= $case['last_action_taken'] ?: '—' ?></div>


<?php if ($isTerminated): ?>
<div class="row">
    <div class="col-md-6"><span class="label">Cause of Termination:</span> <?= $case['cause_of_termination'] ?: '—' ?></div>
    <div class="col-md-6"><span class="label">Date of Termination:</span> <?= $case['date_of_termination'] ?: '—' ?></div>
</div>
<?php endif; ?>

</div>
</div>
</div>

<?php $i++; endwhile; ?>

</div>
<?php else: ?>
<p class="text-muted">No criminal cases recorded for this person.</p>
<?php endif; ?>
</div>

<div class="mt-4">
    <a href="criminal_cases.php" class="btn btn-outline-secondary">
        <i class="fa fa-arrow-left"></i> Back to Criminal Cases
    </a>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
