</div>

<script>
     const savedState = localStorage.getItem('navbarState');
        if (savedState === 'true') {
            const nav = document.getElementById('nav-bar');
            const toggle = document.getElementById('header-toggle');
            const bodypd = document.getElementById('body-pd');
            const headerpd = document.getElementById('header');

            nav.classList.add('show');
            toggle.classList.add('bx-x');
            bodypd.classList.add('body-pd');
            headerpd.classList.add('body-pd');
        }
    document.addEventListener("DOMContentLoaded", function() {
        const showNavbar = (toggleId, navId, bodyId, headerId) => {
            const toggle = document.getElementById(toggleId),
                nav = document.getElementById(navId),
                bodypd = document.getElementById(bodyId),
                headerpd = document.getElementById(headerId);

            // Validate that all variables exist
            if (toggle && nav && bodypd && headerpd) {
                toggle.addEventListener('click', () => {
                    // show navbar
                    nav.classList.toggle('show');
                    // change icon
                    toggle.classList.toggle('bx-x');
                    // add padding to body
                    bodypd.classList.toggle('body-pd');
                    // add padding to header
                    headerpd.classList.toggle('body-pd');

                    // Save state to localStorage
                    localStorage.setItem('navbarState', nav.classList.contains('show'));
                });
            }
        }

        showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');

        document.body.classList.add('loaded');
    });

    function confirmLogout() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out of the system",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, logout'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/student-rms/student-rms/auth/logout.php';
        }
    });
}
</script>

<!-- Bootstrap 5.3.3 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<!-- Datatables JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/jszip-3.10.1/dt-2.2.2/af-2.7.0/b-3.2.2/b-colvis-3.2.2/b-html5-3.2.2/b-print-3.2.2/cr-2.0.4/date-1.5.5/fc-5.0.4/fh-4.0.1/kt-2.12.1/r-3.0.4/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.2/sp-2.3.3/sl-3.0.0/sr-1.4.1/datatables.min.js" integrity="sha384-10kTwhFyUU637a6/7q0kLBdo8jQWjxteg63DT/K8Sdq/nCDaDAkH+Nq/MIrsp8wc" crossorigin="anonymous"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/js/all.min.js" integrity="sha512-b+nQTCdtTBIRIbraqNEwsjB6UvL3UEMkXnhzd8awtCYh0Kcsjl9uEgwVFVbhoj3uu1DO1ZMacNvLoyJJiNfcvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<?php if (!empty($subjects)): ?>
    <script>
        $(document).ready(function() {
            $('#subjectsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'collection',
                    text: 'Export As',
                    buttons: ['copy', 'csv', 'excel'],
                    className: 'btn btn-primary'
                }],
            }).buttons().container().appendTo('.export-as-dropdown-btn');
        });
    </script>
<?php endif; ?>

<?php if (!empty($users)): ?>
    <script>
        $(document).ready(function() {
            $('#usersTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'collection',
                    text: 'Export As',
                    buttons: ['copy', 'csv', 'excel'],
                    className: 'btn btn-primary'
                }],
            }).buttons().container().appendTo('.export-as-dropdown-btn');
        });
    </script>
<?php endif; ?>

<?php if (!empty($grades)): ?>
    <script>
        $(document).ready(function() {
            $('#gradesTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'collection',
                    text: 'Export As',
                    buttons: ['copy', 'csv', 'excel'],
                    className: 'btn btn-primary'
                }],
            }).buttons().container().appendTo('.export-as-dropdown-btn');
        });
    </script>
<?php endif; ?>

<?php if (!empty($students)): ?>
    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'collection',
                    text: 'Export As',
                    buttons: ['copy', 'csv', 'excel'],
                    className: 'btn btn-primary'
                }],
            }).buttons().container().appendTo('.export-as-dropdown-btn');
        });
    </script>
<?php endif; ?>

<?php if (!empty($courses)): ?>
    <script>
        $(document).ready(function() {
            $('#coursesTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'collection',
                    text: 'Export As',
                    buttons: ['copy', 'csv', 'excel'],
                    className: 'btn btn-primary'
                }],
            }).buttons().container().appendTo('.export-as-dropdown-btn');
        });
    </script>
<?php endif; ?>

<body>

    </html>