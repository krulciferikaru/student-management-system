<?php


    require_once 'Model.php';
    require_once 'Course.php';
    require_once 'Subject.php';
    require_once 'Grade.php';
    require_once 'User.php';
    require_once 'Enrollment.php';

    class Student extends Model{
        protected static $table = "students"; //edit based sa pangalan ng table sa database

        //mga gusto maretrieve sa users na table, kung ano pangalan ng column sa users na table, dapat EXACTLY pareho sa column name sa table
        public $id;
        public $student_id;
        public $name;
        public $gender;
        public $birthdate;
        public $course_id;
        public $year_level;
        public $status;

        public function __construct(array $data = []){
            foreach ($data as $key => $value){
                if (property_exists($this, $key)){
                    $this->$key = $value;
                }
            }
        }

        public static function all(){
            $result = parent::all(); //naovverride na ni user table si table
            return $result
            ? array_map(fn ($data) => new self ($data), $result) //to change associative array into object
            : null;
        }

        public static function find($id){
            $result = parent::find($id);
            return $result ? new self($result) : null; //hindi naman kasi buong array yung laman ng result, isang row lang
        }

        public static function create(array $data){
            $result = parent::create($data);

            return $result ? new self($result) : null;
        }

        //now magccreate na ng nonstatic na function, need muna na may existing na object para matawaag

        public function update(array $data){
            $result = parent::updateByID($this->id, $data);

            if ($result){
                foreach ($data as $key => $value){
                    if (property_exists($this, $key)){
                        $this->$key = $value;
                    }
                }
                return true;
            } else {
                return false;
            }
        }
//yung object itself ay merong id 

        public function save(){
            $data = [
                "student_id" => $this->student_id,
                "name" => $this->name,
                "gender" => $this->gender,
                "birthdate" => $this->birthdate,
                "course_id" => $this->course_id,
                "year_level" => $this->year_level,
                "status" => $this->status
            ];

            $this->update($data);
        }
        public function delete(){
            $result = parent::deleteByID($this->id);
            
            if ($result){
                foreach ($this as $key => $value){
                    unset($this->$key);
                }
                return true;
            } else {
                return false;
            }
        }
        public static function where($column, $operation, $value){
            $result = parent::where($column, $operation, $value);
    
            return $result
                ? array_map(fn($data) => new self($data), $result)
                : null;
        }
    


        public function course(){
            return Course::find($this->course_id); // Assuming you have a method to get the course from the student
        }

        public function subjectsEnrolled(){
                return $this->belongsToMany(Subject::class, 'subject_enrollments', 'student_id', 'subject_id');
            
        }

        public function changeStatus(){
            $result = parent::updateByID($this->id, [
                'status' => $this->status=='Active' ? 'Inactive' : 'Active'
            ]);
            return $result;
        }
        
        public function studentGrade($subject_id){
            $subject = Subject::find($subject_id);
            $gradeList = Grade::where('student_id', '=', $this->id);


            if (empty($gradeList)) {
                return null; // If no enrollment found for the student
            }


            foreach ($gradeList as $grade) {
                if ($grade->subject_id == $subject_id) {
                    return $grade;
                }
            }
           
        }

        public function studentEnrollment($subject_id){
            $enrollmentList = Enrollment::where('student_id', '=', $this->id);

            if (empty($enrollmentList)) {
                return null; // If no enrollment found for the student
            }

            foreach ($enrollmentList as $enrollment) {
                if ($enrollment->subject_id == $subject_id) {
                    return $enrollment;
                }
            } 
                
            
        }

        

        
       
    }   



?>