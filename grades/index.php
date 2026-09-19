<?php
require_once '../database/Database.php';
require_once '../models/Grade.php';
require_once '../models/grade.php';
require_once '../models/User.php';
require_once '../models/Course.php';

session_start();
if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
Grade::setConnection($conn);
grade::setConnection($conn);
User::setConnection($conn);
Course::setConnection($conn);

$user = User::findEmail($_SESSION['email']); // $_SESSION['id]
$grades = $user->subjects();

if (!$user) {
    die("User not found.");
}

include '../layout/header.php';

?>

<link rel="stylesheet" href="../css/style.css">

<?php if ($_SESSION['role'] == 'Instructor'):?>

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 55%">
        <h1 class="text-center mb-4" id="black-text">Grade</h1>

        <div class="row gy-2 align-items-center mb-3">
            <div class="col-12 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start gap-2">
                <div class="export-as-dropdown-btn"></div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="gradesTable" class="table table-borderless text-center mx-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Catalogue Number</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php if (empty($grades)): ?>
                        <tr>
                            <td colspan="12" class="text-center" id="text">No grades found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($grades as $grade): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $grade->catalog_no ?></td>
                                <td><?= $grade->name ?></td>
                                <td><?= Course::find($grade->course_id)->name ?></td>
                                <td class="d-flex justify-content-center">
                                    <div class="actions-dropdown">
                                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a href="view.php?id=<?= $grade->id ?>" class="view-btn"><i class="bi bi-eye"></i>View</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
<?php if ($_SESSION['role'] == 'Super-admin' || 'Admin'):?>
    
<div class="container vh-100 d-flex align-items-center justify-content-center position-relative mt-3">
    <div class="glass-container d-flex flex-column mx-auto position-relative w-100" style="max-width: 55%">
        <h1 class="text-center mb-4" id="black-text">Grade</h1>

        <div class="row gy-2 align-items-center mb-3">
            <div class="col-12 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-start gap-2">
                <div class="export-as-dropdown-btn"></div>
            </div>
        </div>
        <div class="col-12">
                    <form action="" method="GET">
                        <select class="form-select" id="instructor" name="instructor" onchange="this.form.submit()">
                            <option value="" <?php if (isset($_GET['instructor']) && $_GET['instructor'] === '') echo 'selected'; ?> disabled selected hidden>Pick Instructor</option>
                                <?php 
                                    $instructors = User::where("role", "=", "Instructor");
                                    foreach ($instructors as $instructor):?>
                                    <option value=<?=$instructor->id?>> <?=$instructor->name?></option>
                                <?php endforeach; ?>
                        </select>
                    </form>
                </div>

                <?php 
                    $id = isset($_GET['instructor']) ? $_GET['instructor'] : null;
                    $user = $id ? User::find($id) : null;
                    $grades = $user ? $user->subjects() : [];
                ?>
                <h3 class="text-center mb-4" id="black-text"><?php isset($user->name) ? $user->name : null?></h3>
        <div class="table-responsive w-100">
            <table id="gradesTable" class="table table-borderless text-center mx-auto">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Catalogue Number</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php if (empty($grades)): ?>
                        <tr>
                            <td colspan="12" class="text-center" id="text">No grades found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($grades as $grade): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= $grade->catalog_no ?></td>
                                <td><?= $grade->name ?></td>
                                <td><?= Course::find($grade->course_id)->name ?></td>
                                <td class="d-flex justify-content-center">
                                    <div class="actions-dropdown">
                                        <button class="dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">Actions</button>
                                        <ul class="dropdown-menu">
                                            <li><a href="view.php?id=<?= $grade->id ?>" class="view-btn"><i class="bi bi-eye"></i>View</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>




<?php include '../layout/footer.php'; ?>