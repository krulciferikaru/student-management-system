<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTracker</title>

    <!-- Bootstrap 5.3.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Datatables CSS -->
    <link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.2.2/af-2.7.0/b-3.2.2/b-colvis-3.2.2/b-html5-3.2.2/b-print-3.2.2/cr-2.0.4/date-1.5.5/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.4/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.2/sp-2.3.3/sl-3.0.0/sr-1.4.1/datatables.min.css" rel="stylesheet" integrity="sha384-6gM1RUmcWWtU9mNI98EhVNlLX1LDErxSDu2o/YRIeXq34o77tQYTXLzJ/JLBNkNV" crossorigin="anonymous">
    <!-- Sweet Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/student-rms/student-rms/css/header.css">
    <!-- DataTables Query -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/5hb7x2j4z5e5f5e5f5e5f5e5f5e5f5e5f5e5f" crossorigin="anonymous"></script>
</head>

<body id="body-pd">
    <?php
    $curr_file = basename($_SERVER['PHP_SELF']); //basename - returns the FILE NAME //server php self returns PATH of current file
    if ($curr_file != 'login.php' && $curr_file != '404.php') {
        $currentPage = $_SERVER['REQUEST_URI'];

        function isCollapsed($folder)
        {
            $result = strpos($_SERVER['PHP_SELF'], $folder) !== false ? '' : 'collapsed';
        }
        function isCollapsedin($page, $folder)
        {
            $result = strpos($_SERVER['PHP_SELF'], $page) !== false || strpos($_SERVER['PHP_SELF'], $folder) !== false ? 'show' : '';
            return $result;
        }
        function isCollapsedTrue($folder)
        {
            $result = strpos($_SERVER['PHP_SELF'], $folder) !== false ? 'true' : 'false';
            return $result;
        }
        function isCollapsedShow($folder)
        {
            $result = strpos($_SERVER['PHP_SELF'], $folder) !== false ? 'show' : '';
            return $result;
        }
        function isActive($page, $folder)
        {
            $result = basename($_SERVER['PHP_SELF']) == $page && strpos($_SERVER['PHP_SELF'], $folder) !== false ? 'active' : '';
            return $result;
        }

        function isInFolder($folder)
        {
            $result = strpos($_SERVER['PHP_SELF'], $folder) !== false ? 'active' : '';
            return $result;
        }
    ?>
        <header class="header" id="header">
            <div class="header_toggle"> <i class='bx bx-menu' id="header-toggle"></i> </div>
        </header>

        <div class="l-navbar" id="nav-bar">
            <nav class="nav">
                <div>
                    <a href="/student-rms/student-rms/index.php" class="nav_logo"> <i class='bx bxs-school nav_logo-icon'></i> <span class="nav_logo-name">EduTracker</span> </a>
                    <div class="nav_list">
                        <a href="/student-rms/student-rms/index.php"
                            class="nav_link <?= $currentPage == '/student-rms/student-rms/index.php' ? 'active' : '' ?>">
                            <i class='bx bx-grid-alt nav_icon'></i>
                            <span class="nav_name">Dashboard</span>
                        </a>

                        <!-- GRADES -->
                        <a href="/student-rms/student-rms/grades/index.php"
                            class="nav_link <?= isActive('index.php', 'grades') ?>">
                            <i class='bx bxs-bar-chart-alt-2 nav-icon'></i>
                            <span class="nav_name">Grades</span>
                        </a>
                        <!-- END OF GRADES -->

                        <!-- SUPER ADMIN & ADMIN-->
                        <?php if($_SESSION['role'] === 'Super-admin' || $_SESSION['role'] === 'Admin') {?>
                            <!-- STUDENTS -->
                            <a class="nav_link <?=isCollapsed('students')?> <?=isInFolder('students')?>"
                                data-bs-toggle="collapse"
                                href="#studentsCollapse"
                                role="button"
                                aria-expanded="<?= isCollapsedTrue('students') ?>"
                                aria-controls="studentsCollapse">
                                <i class="fa-solid fa-users nav-icon"></i>
                                Students
                            </a>
                            <div class="collapse <?= isCollapsedShow('students') ?>" id="studentsCollapse">
                                <div>
                                    <a href="/student-rms/student-rms/students/index.php"
                                        class="nav_link <?= isCollapsedin('index.php', 'students') ?>">
                                        <i class="fa-solid fa-eye nav-icon"></i>
                                        View Students
                                    </a>
                                    <a href="/student-rms/student-rms/students/create.php"
                                        class="nav_link <?= isCollapsedIn('create.php', 'students') ?>">
                                        <i class="fa-solid fa-plus nav-icon"></i>
                                        Add Students
                                    </a>
                                </div>
                            </div>
                            <!--END OF STUDENTS-->

                            <!--COURSES-->
                            <a class="nav_link <?= isCollapsed('courses') ?> <?= isInFolder('courses') ?>"
                                data-bs-toggle="collapse"
                                href="#coursesCollapse"
                                role="button"
                                aria-expanded="<?= isCollapsedTrue('courses') ?>"
                                aria-controls="studentsCollapse">
                                <i class="fa-solid fa-graduation-cap nav-icon"></i>
                                Courses
                            </a>
                            <div class="collapse <?= isCollapsedShow('courses') ?>" id="coursesCollapse">
                                <div>
                                    <a href="/student-rms/student-rms/courses/index.php"
                                        class="nav_link <?= isCollapsedin('index.php', 'courses') ?>">
                                        <i class="fa-solid fa-eye nav-icon"></i>
                                        View Courses
                                    </a>
                                    <a href="/student-rms/student-rms/courses/create.php"
                                        class="nav_link <?= isCollapsedIn('create.php', 'courses') ?>">
                                        <i class="fa-solid fa-plus nav-icon"></i>
                                        Add Courses
                                    </a>
                                </div>
                            </div>
                            <!--END OF COURSES-->

                            <!--SUBJECTS-->
                            <a class="nav_link <?= isCollapsed('subjects') ?> <?= isInFolder('subjects') ?>"
                                data-bs-toggle="collapse"
                                href="#subjectsCollapse"
                                role="button"
                                aria-expanded="<?= isCollapsedTrue('subjects') ?>"
                                aria-controls="studentsCollapse">
                                <i class='bx bx-book-open nav-icon'></i>
                                Subjects
                            </a>
                            <div class="collapse <?= isCollapsedShow('subjects') ?>" id="subjectsCollapse">
                                <div>
                                    <a href="/student-rms/student-rms/subjects/index.php"
                                        class="nav_link <?= isCollapsedin('index.php', 'subjects') ?>">
                                        <i class="fa-solid fa-eye nav-icon"></i>
                                        View Subjects
                                    </a>
                                    <a href="/student-rms/student-rms/subjects/create.php"
                                        class="nav_link <?= isCollapsedIn('create.php', 'subjects') ?>">
                                        <i class="fa-solid fa-plus nav-icon"></i>
                                        Add Subjects
                                    </a>
                                </div>
                            </div>
                            <!--END OF SUBJECTS-->

                            <!--USERS-->
                            <a class="nav_link <?= isCollapsed('users') ?> <?= isInFolder('users') ?>"
                                data-bs-toggle="collapse"
                                href="#usersCollapse"
                                role="button"
                                aria-expanded="<?= isCollapsedTrue('users') ?>"
                                aria-controls="studentsCollapse">
                                <i class='bx bxs-user-account nav-icon'></i>
                                Users
                            </a>
                            <div class="collapse <?= isCollapsedShow('users') ?>" id="usersCollapse">
                                <div>
                                    <a href="/student-rms/student-rms/users/index.php"
                                        class="nav_link <?= isCollapsedin('index.php', 'users') ?>">
                                        <i class="fa-solid fa-eye nav-icon"></i>
                                        View Users
                                    </a>
                                    <a href="/student-rms/student-rms/users/create.php"
                                        class="nav_link <?= isCollapsedIn('create.php', 'users') ?>">
                                        <i class="fa-solid fa-plus"></i>
                                        Add Users
                                    </a>
                                </div>
                            </div>
                            </a>
                            <!--END OF USERS-->
                    </div>

                <?php } ?>
                </div> <a href="#" class="nav_link" onclick="confirmLogout()"> 
                                <i class='bx bx-log-out nav_icon'></i> 
                                <span class="nav_name">Log Out</span> 
                        </a>
            </nav>
        </div>
        <!--Container Main start-->
        <div>

        <?php } ?>


