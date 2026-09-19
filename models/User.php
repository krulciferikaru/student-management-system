<?php


    require_once 'Model.php';

    class User extends Model{
        protected static $table = "users"; //edit based sa pangalan ng table sa database

        //mga gusto maretrieve sa users na table, kung ano pangalan ng column sa users na table, dapat EXACTLY pareho sa column name sa table
        public $id;
        public $name;
        public $email;
        public $password;
        public $role;
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
            if (isset($data['password'])){
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
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
                "name" => $this->name,
                "email" => $this->email,
                "password" => password_hash($this->password, PASSWORD_DEFAULT),
                "role" => $this->role,
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

        public function subjects(){
            $subject = Subject::where('instructor_id', '=', $this->id);
            return $subject ?? null;
        }


        public static function allInstructors(){
            $result = self::where('role', '=', 'Instructor');
            return $result ?? null;
        }

         
        public static function allActiveInstructors() {
            $instructors = self::where('role', '=', 'Instructor');
            $result = [];
            
            if ($instructors) {
                foreach ($instructors as $instructor) {
                    if ($instructor->status === 'Active') {
                        $result[] = $instructor; // Use array_push syntax instead of +=
                    }
                }
            }
            
            return !empty($result) ? $result : null;
        }

        public static function requireRole(array $role) {
            if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $role)) {
                header("Location: /student-rms/student-rms/index.php");
                exit;
            }
        }
        public function subjectsGraded(){
            return $this->belongsToMany(Subject::class, 'grades', 'instructor_id', 'subject_id');
        }  

        public function grades()
        {
            $result = Grade::where('instructor_id', '=', $this->id);

            return $result ?? null;
        }
        
        public static function findEmail($email){
            $result = self::where('email', '=', $email);
            return $result ? $result[0]: null; 
        }
        public function changeStatus(){
            $result = parent::updateByID($this->id, [
                'status' => $this->status=='Active' ? 'Inactive' : 'Active'
            ]);
            return $result;
        }

        public static function passwordRandomizer($length) {
            $string = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    
            return substr(str_shuffle($string), 0, $length);
        }
        
       
        public function StudentsEnrolled(){

            $studenttotal = 0;
            $subjects = $this->subjects(); // Assuming this method returns the subjects taught by the instructor

            if (empty($subjects)) {
                return 0; // If no enrollment found for the student
            }

            foreach ($subjects as $subject) {
                $studenttotal += count($subject->students());
            }
            return $studenttotal;
        
        }

        public function pendingGradingTasks(){
            $subjects = $this->subjects(); 
            $pendingTasks = 0; 
            if (empty($subjects)) {
                return 0; // If no enrollment found for the student
            }
            foreach ($subjects as $subject) {
                $students = $subject->students(); // Assuming this method returns the students enrolled in the subject
                if (empty($students)) {
                    return 0; // If no enrollment found for the student
                }
                foreach ($students as $student) {
                    $grade = $student->studentGrade($subject->id); // Assuming this method returns the grade for the student in the subject
                    if (empty($grade)) {
                        $pendingTasks++;
                    }
                }
            }
            return $pendingTasks;
        }
        
    }   



?>