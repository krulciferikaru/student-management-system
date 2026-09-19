<?php
require_once '../database/Database.php';
require_once '../models/User.php';
require_once '../models/Course.php';
require_once '../models/Subject.php';
session_start();

if (!isset($_SESSION['email'])) {
    header('Location: ../auth/login.php');
}

$db = new Database();
$conn = $db->getConnection();
User::setConnection($conn);
User::requireRole(['Admin', 'Super-admin']);
Course::setConnection($conn);

$id = $_GET['id'];
$subject = Subject::find($id); // Find the subject by ID
$courses = Course::all();
$instructors = User::allInstructors();

include '../layout/header.php';
?>

<link rel="stylesheet" href="../css/style.css">

<div class="container vh-100 d-flex align-items-center justify-content-center position-relative">
    <div class="glass-container d-flex flex-column mx-auto position-relative" style="max-width: 55%;">
        <!-- Cancel Button -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-start align-items-center">
                <a href="index.php" class="cancel-btn ms-auto">&times;</a>
            </div>
        </div>
        <h2 class="text-center" id="black-text">Edit Subject</h2>

        <form action="update.php?id=<?= $subject->id ?>" method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="code" id="black-text">Subject Code</label>
                    <input type="text" class="form-control" id="code" name="code" disabled value="<?= $subject->code ?>">
                </div>
                <div class="col-md-6">
                    <label for="catalog_no" id="black-text">Catalog No</label>
                    <input type="text" class="form-control" id="catalog_no" name="catalog_no" value="<?= $subject->catalog_no ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="name" id="black-text">Subject Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $subject->name ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="day" id="black-text">Day</label>
                    <select class="form-select" id="day" name="day" value="<?= $subject->day ?>" required>
                        <option value="$subject->day" selected></option>
                        <option value="Mon">Mon</option>
                        <option value="Tue">Tue</option>
                        <option value="Wed">Wed</option>
                        <option value="Thu">Thu</option>
                        <option value="Fri">Fri</option>
                        <option value="Sat">Sat</option>
                        <option value="Sun">Sun</option>

                        <option value="Mon/Tue">Mon/Tue</option>
                        <option value="Mon/Wed">Mon/Wed</option>
                        <option value="Mon/Thu">Mon/Thu</option>
                        <option value="Mon/Fri">Mon/Fri</option>
                        <option value="Mon/Sat">Mon/Sat</option>
                        <option value="Mon/Sun">Mon/Sun</option>

                        <option value="Tue/Wed">Tue/Wed</option>
                        <option value="Tue/Thu">Tue/Thu</option>
                        <option value="Tue/Fri">Tue/Fri</option>
                        <option value="Tue/Sat">Tue/Sat</option>
                        <option value="Tue/Sun">Tue/Sun</option>

                        <option value="Wed/Thu">Wed/Thu</option>
                        <option value="Wed/Fri">Wed/Fri</option>
                        <option value="Wed/Sat">Wed/Sat</option>
                        <option value="Wed/Sun">Wed/Sun</option>

                        <option value="Thu/Fri">Thu/Fri</option>
                        <option value="Thu/Sat">Thu/Sat</option>
                        <option value="Thu/Sun">Thu/Sun</option>

                        <option value="Fri/Sat">Fri/Sat</option>
                        <option value="Fri/Sun">Fri/Sun</option>

                        <option value="Sat/Sun">Sat/Sun</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="time" id="black-text">Time</label>
                    <select class="form-select" id="time" name="time" value="<?= $subject->time ?>" required>
                        <option value="$subject->time" selected></option>
                        
        
                        <option value="7:00 AM - 8:30 AM">7:00 AM - 8:30 AM</option>
                        <option value="7:30 AM - 9:00 AM">7:30 AM - 9:00 AM</option>
                        <option value="8:00 AM - 9:30 AM">8:00 AM - 9:30 AM</option>
                        <option value="8:30 AM - 10:00 AM">8:30 AM - 10:00 AM</option>
                        <option value="9:00 AM - 10:30 AM">9:00 AM - 10:30 AM</option>
                        <option value="9:30 AM - 11:00 AM">9:30 AM - 11:00 AM</option>
                        <option value="10:00 AM - 11:30 AM">10:00 AM - 11:30 AM</option>
                        <option value="10:30 AM - 12:00 PM">10:30 AM - 12:00 PM</option>
                        <option value="11:00 AM - 12:30 PM">11:00 AM - 12:30 PM</option>

                        <option value="1:00 PM - 2:30 PM">1:00 PM - 2:30 PM</option>
                        <option value="1:30 PM - 3:00 PM">1:30 PM - 3:00 PM</option>
                        <option value="2:00 PM - 3:30 PM">2:00 PM - 3:30 PM</option>
                        <option value="2:30 PM - 4:00 PM">2:30 PM - 4:00 PM</option>
                        <option value="3:00 PM - 4:30 PM">3:00 PM - 4:30 PM</option>
                        <option value="3:30 PM - 5:00 PM">3:30 PM - 5:00 PM</option>
                        <option value="4:00 PM - 5:30 PM">4:00 PM - 5:30 PM</option>
                        <option value="4:30 PM - 6:00 PM">4:30 PM - 6:00 PM</option>
                        <option value="5:00 PM - 6:30 PM">5:00 PM - 6:30 PM</option>
                        <option value="5:30 PM - 7:00 PM">5:30 PM - 7:00 PM</option>

                        <!-- 2-hour intervals -->
                        <option value="7:00 AM - 9:00 AM">7:00 AM - 9:00 AM</option>
                        <option value="9:00 AM - 11:00 AM">9:00 AM - 11:00 AM</option>
                        <option value="11:00 AM - 1:00 PM">11:00 AM - 1:00 PM</option>
                        <option value="1:00 PM - 3:00 PM">1:00 PM - 3:00 PM</option>
                        <option value="3:00 PM - 5:00 PM">3:00 PM - 5:00 PM</option>
                        <option value="5:00 PM - 7:00 PM">5:00 PM - 7:00 PM</option>

                        <!-- 3-hour intervals -->
                        <option value="7:00 AM - 10:00 AM">7:00 AM - 10:00 AM</option>
                        <option value="10:00 AM - 1:00 PM">10:00 AM - 1:00 PM</option>
                        <option value="1:00 PM - 4:00 PM">1:00 PM - 4:00 PM</option>
                        <option value="4:00 PM - 7:00 PM">4:00 PM - 7:00 PM</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="room" id="black-text">Room</label>
                    <input type="text" class="form-control" id="room" name="room" value="<?= $subject->room ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="course_id" id="black-text">Course</label>
                    <select class="form-select" id="course_id" name="course_id" value="<?= $subject->course_id ?>" required>
                        <?php foreach ($courses as $course) : ?>
                            <option value="<?= $course->id ?>" <?= ($subject->course_id == $course->id) ? 'selected' : '' ?>><?= $course->code ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="year_level" id="black-text">Year Level</label>
                    <select class="form-select" id="year_level" name="year_level" value="<?= $subject->year_level ?>" required>
                        <option value="1" <?= $subject->year_level == '1' ? 'selected' : '' ?>>1st Year</option>
                        <option value="2" <?= $subject->year_level == '2' ? 'selected' : '' ?>>2nd Year</option>
                        <option value="3" <?= $subject->year_level == '3' ? 'selected' : '' ?>>3rd Year</option>
                        <option value="4" <?= $subject->year_level == '4' ? 'selected' : '' ?>>4th Year</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label for="semester" id="black-text">Semester</label>
                    <select class="form-select" id="semester" name="semester" value="<?= $subject->semester ?>" required>
                        <option value="1" <?= $subject->semester == '1' ? 'selected' : '' ?>>1st Semester</option>
                        <option value="2" <?= $subject->semester == '2' ? 'selected' : '' ?>>2nd Semester</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="instructor_id" id="black-text">Assigned Instructor</label>
                    <select class="form-select" id="instructor_id" name="instructor_id" value="<?= $subject->instructor_id ?>" required>
                        <?php foreach ($instructors as $instructor) : ?>
                            <option value="<?= $instructor->id ?>" <?= ($subject->instructor_id == $instructor->id) ? 'selected' : '' ?>><?= $instructor->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn" style="width: 25%;">Save</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>

<?php


include '../layout/footer.php'; ?>