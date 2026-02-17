<?php
require_once '../config.php';
checkAdminAuth();


// ================= FILTER LOGIC =================
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$where = "";

if ($filter == 'urban') {
    $where = "WHERE cc.location_type = 1";
}
elseif ($filter == 'rural') {
    $where = "WHERE cc.location_type = 0";
}
elseif ($filter == 'male') {
    $where = "WHERE p.gender_sex = 'Male'";
}
elseif ($filter == 'female') {
    $where = "WHERE p.gender_sex = 'Female'";
}

// Fetch all criminal cases with linked person info
$sql = "
SELECT 
    cc.id,
    cc.person_id,
    cc.control_number,
    cc.party_represented,
    cc.case_no,
    cc.court_body,
    cc.title_of_the_case,
    cc.cause_of_action,
    cc.status_of_case,
    cc.last_action_taken,
    cc.cause_of_termination,
    cc.date_of_termination,
    cc.location_type,
    cc.date_of_confinement,
    cc.place_of_detention,
    cc.case_received,
    p.full_name AS person_name,
    p.address AS person_address,
    p.gender_sex AS person_gender,
    p.contact_number AS person_contact,
    p.age AS person_age
FROM criminal_cases cc
LEFT JOIN persons p ON cc.person_id = p.person_id
$where
ORDER BY cc.id DESC


";
$result = $conn->query($sql);

// ================= COUNT QUERIES =================
$totalUrban = $conn->query("
    SELECT COUNT(*) as total 
    FROM criminal_cases 
    WHERE location_type = 1
")->fetch_assoc()['total'];

$totalRural = $conn->query("
    SELECT COUNT(*) as total 
    FROM criminal_cases 
    WHERE location_type = 0
")->fetch_assoc()['total'];

$totalMale = $conn->query("
    SELECT COUNT(*) as total 
    FROM criminal_cases cc
    LEFT JOIN persons p ON cc.person_id = p.person_id
    WHERE p.gender_sex = 'Male'
")->fetch_assoc()['total'];

$totalFemale = $conn->query("
    SELECT COUNT(*) as total 
    FROM criminal_cases cc
    LEFT JOIN persons p ON cc.person_id = p.person_id
    WHERE p.gender_sex = 'Female'
")->fetch_assoc()['total'];

// ================= ACTIVE FILTER COUNT =================
$activeCount = $result->num_rows;

$filterLabel = "All Cases";

if ($filter == 'urban') {
    $filterLabel = "Urban";
}
elseif ($filter == 'rural') {
    $filterLabel = "Rural";
}
elseif ($filter == 'male') {
    $filterLabel = "Male";
}
elseif ($filter == 'female') {
    $filterLabel = "Female";
}



// Fetch persons for dropdown
$persons = $conn->query("SELECT person_id, full_name FROM persons ORDER BY full_name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Criminal Cases | PAO Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<style>
body { 
    background:#f1f1f1; 
    font-family: 'Segoe UI', sans-serif; 
    }   
.sidebar {
     min-height:100vh; 
     background:#f5f5f5; 
     border-right:1px solid #e5e7eb; 
     padding:20px; 
    }
.sidebar h4 {
     font-weight:700; 
     color:#1f2937; 
    }
.sidebar .nav-link {
     color:#6b7280; 
     border-radius:10px; 
     padding:10px 14px; 
     margin-bottom:6px; 
     transition:0.2s; 
    }
.sidebar .nav-link.active, .sidebar .nav-link:hover {
     background:#2f80ed;
      color:#fff; 
    }
.content-area { 
    padding:30px; 
    }
.card-box { 
    background:#fff; 
    border-radius:14px; 
    border:1px solid #e5e7eb; 
    padding:20px; 
    }
.table thead th {
     font-size:0.85rem; 
     color:#6b7280; 
     border-bottom:1px solid #e5e7eb; 
    }
.table tbody td {
     vertical-align: middle; 
    }
.badge-status { 
    padding:5px 10px; 
    border-radius:20px; 
    font-size:0.75rem; 
    }
.modal-body { 
    max-height:65vh; 
    overflow-y:auto; 
    }
</style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <aside class="col-md-3 col-lg-2 sidebar">
            <h4 class="mb-4">PAO Admin</h4>

            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fa-solid fa-grid-2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="criminal_cases.php">
                        <i class="fa-solid fa-gavel me-2"></i> Criminal Cases
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <a class="nav-link text-danger" href="../logout.php">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </aside>

<!-- Main -->
<main class="col-md-9 col-lg-10 content-area">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Criminal Cases</h2>
            <nav class="breadcrumb">
                <span class="breadcrumb-item">Dashboard</span>
                <span class="breadcrumb-item active">Criminal Cases</span>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCaseModal">
                <i class="fas fa-plus me-1"></i> Add Case
            </button>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPersonModal">
                <i class="fas fa-user-plus me-1"></i> Add Person
            </button>
        </div>
    </div>

    <div class="card-box">
       <div class="d-flex justify-content-between align-items-center mb-3">

    <!-- LEFT SIDE -->
    <div class="d-flex align-items-center gap-3">

        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle"
                    type="button"
                    data-bs-toggle="dropdown">
                <i class="fas fa-sliders-h me-1"></i> <?= $filterLabel ?>
            </button>

            <ul class="dropdown-menu shadow">
                <li><a class="dropdown-item" href="criminal_cases.php">All Cases</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="?filter=urban">Urban</a></li>
                <li><a class="dropdown-item" href="?filter=rural">Rural</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="?filter=male">Male</a></li>
                <li><a class="dropdown-item" href="?filter=female">Female</a></li>
            </ul>
        </div>

        <!-- COUNT DISPLAY (RIGHT SIDE OF FILTER BUTTON) -->
        <div>
            <span class="fw-semibold text-muted">
                Total: 
            </span>
            <span class="badge bg-primary fs-6">
                <?= $activeCount ?>
            </span>
        </div>

    </div>

    <!-- RIGHT SIDE SEARCH -->
    <input type="text" id="searchInput" 
           class="form-control w-25" 
           placeholder="Search case...">

</div>


        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Control No.</th>
                        <th>Party Represented</th>
                        <th>Gender / Sex</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Person</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="caseTable">
                <?php if($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['control_number']) ?></td>
                            <td><?= htmlspecialchars($row['party_represented']) ?></td>
                            <td><?= htmlspecialchars($row['person_gender']) ?></td>
                            <td><?= htmlspecialchars($row['title_of_the_case']) ?></td>
                            <td>
                                <?php $status=$row['status_of_case']; $cls=($status=='Terminated')?'bg-danger text-white':'bg-primary text-white'; ?>
                                <span class="badge rounded-pill <?= $cls ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                            <td><?= htmlspecialchars($row['person_name']) ?></td>
                            <td class="text-end"><a href="view_criminal_case.php?person_id=<?= $row['person_id'] ?>" class="btn btn-sm btn-outline-primary">
    <i class="fas fa-eye me-1"></i> View
</a>
</td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center text-muted">No criminal cases found</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</div>
</div>

<!-- Add Case Modal -->
<div class="modal fade" id="addCaseModal" tabindex="-1">
<div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
<div class="modal-content">
<form action="save_criminal_case.php" method="POST">
<div class="modal-header">
    <h5>Add Criminal Case</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <label>Person</label>
            <select name="person_id" class="form-select" required>
                <option value="">-- Select Person --</option>
                <?php
                $persons2 = $conn->query("SELECT person_id, full_name FROM persons ORDER BY full_name ASC");
                while($p=$persons2->fetch_assoc()): ?>
                    <option value="<?= $p['person_id'] ?>"><?= htmlspecialchars($p['full_name']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <hr>
    <h6>Case Identification</h6>
    <div class="row g-3 mb-3">
        <div class="col-md-3"><label>Control No.</label><input type="text" name="control_number" class="form-control" required></div>
         <div class="col-md-3"><label>Party Represented</label><input type="text" name="party_represented" class="form-control"></div>
         <div class="col-md-3"><label>Title of The  Case
</label><input type="text" name="title_of_the_case" class="form-control" required></div>
         <div class="col-md-3"><label>Court / Body</label><input type="text" name="court_body" class="form-control"></div>

        
    </div>
    <div class="row g-3 mb-3">
<div class="col-md-3"><label>Case No.</label><input type="text" name="case_no" class="form-control"></div>
    <div class="col-md-3"><label>Cause of Action</label><input name="cause_of_action" class="form-control" rows="2"></input></div>

    </div><div class="col-md-4">
        <label class="d-block">Location Type</label>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio"
                   name="location_type" value="1" required>
            <label class="form-check-label">Urban</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio"
                   name="location_type" value="0">
            <label class="form-check-label">Rural</label>
        </div>
    </div>
    <hr>
    <h6>Case Status</h6>
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label>Status</label>
            <select name="status_of_case" id="statusSelect" class="form-select" required>
                <option value="">Select Status</option>
                <option value="Pending">Pending</option>
                <option value="Terminated">Terminated</option>
            </select>
        </div>
        <div class="col-md-8">
            <label>Last Action Taken (optional)</label>
            <textarea name="last_action_taken" class="form-control"></textarea>
        </div>
    </div>

    <div id="terminatedFields" style="display:none;">
        <div class="row g-3 mb-3">
            <div class="col-md-6"><label>Cause of Termination</label><textarea name="cause_of_termination" class="form-control"></textarea></div>
            <div class="col-md-6"><label>Date of Termination</label><input type="date" name="date_of_termination" class="form-control"></div>
        </div>
    </div>
<div class="row g-3 mb-3"> <div class="col-md-4"><label>Date of Confinement</label><input type="date" name="date_of_confinement" class="form-control">
</div>
 <div class="col-md-4"><label>Place of Detention</label><input type="text" name="place_of_detention" class="form-control">
</div>
  <div class="col-md-4"><label>Case Received</label>
<input type="date" name="case_received" class="form-control"></div> </div>
</div>

<div class="modal-footer bg-light sticky-bottom">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-primary">Save Case</button>
</div>
</form>
</div>
</div>
</div>

<!-- Add Person Modal -->
<div class="modal fade" id="addPersonModal" tabindex="-1">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content">
<form action="save_person.php" method="POST">
<div class="modal-header">
    <h5>Add Person</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div class="mb-3"><label>Full Name</label><input type="text" name="full_name" class="form-control" required></div>
    <div class="mb-3"><label>Gender</label>
        <select name="gender_sex" class="form-select" required><option value="">Select</option><option>Male</option><option>Female</option></select>
    </div>
    <div class="mb-3"><label>Age</label><input type="number" name="age" class="form-control" required></div>
    <div class="mb-3"><label>Address</label><input type="text" name="address" class="form-control" required></div>
    <div class="mb-3"><label>Contact Number</label><input type="text" name="contact_number" class="form-control" required></div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
    <button type="submit" class="btn btn-success">Save Person</button>
</div>
</form>
</div>
</div>
</div>

<script>
document.getElementById("statusSelect").addEventListener("change", function(){
    document.getElementById("terminatedFields").style.display = this.value==="Terminated"?"block":"none";
});
document.getElementById("searchInput").addEventListener("keyup", function(){
    let v=this.value.toLowerCase();
    document.querySelectorAll("#caseTable tr").forEach(r=>r.style.display=r.innerText.toLowerCase().includes(v)?"":"none");
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
