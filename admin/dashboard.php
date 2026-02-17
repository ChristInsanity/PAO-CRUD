<?php
require_once '../config.php';
checkAdminAuth();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - PAO</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
:root {
    --bg: #f1f1f1;
    --card: #ffffff;
    --primary: #2f80ed;
    --text-dark: #1f2937;
    --text-muted: #6b7280;
    --sidebar: #f5f5f5;
    --border: #e5e7eb;
}

body {
    background-color: var(--bg);
    font-family: 'Segoe UI', sans-serif;
}

/* Sidebar */
.sidebar {
    min-height: 100vh;
    background: var(--sidebar);
    border-right: 1px solid var(--border);
    padding: 20px;
}

.sidebar h4 {
    font-weight: 700;
    color: var(--text-dark);
}

.sidebar .nav-link {
    color: var(--text-muted);
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 6px;
    transition: 0.2s;
}

.sidebar .nav-link.active,
.sidebar .nav-link:hover {
    background: var(--primary);
    color: #fff;
}

/* Content */
.content {
    padding: 30px;
}

.header-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--text-dark);
}

/* Cards */
.stat-card {
    background: var(--card);
    border-radius: 18px;
    padding: 22px;
    border: 1px solid var(--border);
}

.stat-card h6 {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.stat-card h2 {
    font-weight: 700;
    margin: 10px 0;
}

.stat-icon {
    font-size: 28px;
    color: var(--primary);
}

/* Table */
.table-card {
    background: var(--card);
    border-radius: 18px;
    border: 1px solid var(--border);
    padding: 20px;
}

.table th {
    color: var(--text-muted);
    font-weight: 600;
    border-bottom: 1px solid var(--border);
}

.table td {
    vertical-align: middle;
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
                    <a class="nav-link active" href="dashboard.php">
                        <i class="fa-solid fa-grid-2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="criminal_cases.php">
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

        <!-- Main Content -->
        <main class="col-md-9 col-lg-10 content">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="header-title">Overview</h2>
            </div>

            <!-- Stats -->
            <div class="row g-4 mb-4">

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Total Criminal Cases</h6>
                                <h2>0</h2>
                            </div>
                            <i class="fa-solid fa-folder-open stat-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Ongoing Cases</h6>
                                <h2>0</h2>
                            </div>
                            <i class="fa-solid fa-scale-balanced stat-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6>Terminated Cases</h6>
                                <h2>0</h2>
                            </div>
                            <i class="fa-solid fa-circle-check stat-icon"></i>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Recent Criminal Cases -->
            <div class="table-card">
                <h5 class="mb-3">Recent Criminal Cases</h5>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Control No.</th>
                            <th>Title of Case</th>
                            <th>Status</th>
                            <th>Date Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                No criminal cases recorded yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

</body>
</html>
