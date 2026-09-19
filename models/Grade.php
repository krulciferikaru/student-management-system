<?php


    require_once 'Model.php';

    class Grade extends Model{
        protected static $table = "grades"; //edit based sa pangalan ng table sa database

        //mga gusto maretrieve sa users na table, kung ano pangalan ng column sa users na table, dapat EXACTLY pareho sa column name sa table
        public $id;
        public $student_id;
        public $subject_id;
        public $instructor_id;
        public $grade;
        public $remarks;
    
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
                "subject_id" => $this->subject_id,
                "instructor_id" => $this->instructor_id,
                "grade" => $this->grade,
                "remarks" => $this->remarks
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

        public function student(){
            return Student::find($this->student_id);
        }
        public function subject(){
            return Subject::find($this->subject_id);
        }
        public function instructor(){
            return User::find($this->instructor_id);
        }

         public static function remarks($subject_id, $remarks)
        {
            $gradeInSubj = 0;
            
            $gradeList = parent::where('remarks', '=', $remarks);
            $subject = $subject_id;

            if (empty($gradeList)) {
                return 0; 
            }

            foreach ($gradeList as $grade) {
                if ($grade['subject_id'] == $subject) {
                    $gradeInSubj++;
                }
            }
            return $gradeInSubj ?? 0; // Return the found grade or null if not found
            
        }

        // public static function failed($subject_id)
        // {
        //     $gradeInSubj = 0;

        //     $gradeList = parent::where('remarks', '=', 'Failed');
        //     $subject = $subject_id;

        //     if (empty($gradeList)) {
        //         return 0; 
        //     }

        //     foreach ($gradeList as $grade) {
        //         if ($grade['subject_id'] == $subject) {
        //             $gradeInSubj++;
        //         }
        //     }
        //     return $gradeInSubj ?? 0; // Return the found grade or null if not found
        // }

        // public static function pending ($subject_id)
        // {
        //     $gradeInSubj = 0;
        //     $gradeList = parent::where('remarks', '=', 'Pending');
        //     $subject = $subject_id;

        //     if (empty($gradeList)) {
        //         return 0; 
        //     }

        //     foreach ($gradeList as $grade) {
        //         if ($grade['subject_id'] == $subject) {
        //             $gradeInSubj++;
        //         }
               
        //     } 
        //     return $gradeInSubj ?? 0; // Return the found grade or null if not found
        // }

        // public static function incomplete ($subject_id)
        // {
        //     $gradeInSubj = 0;
        //     $gradeList = parent::where('remarks', '=', 'INC');
        //     $subject = $subject_id;

        //     if (empty($gradeList)) {
        //         return 0; 
        //     }

        //     foreach ($gradeList as $grade) {
        //         if ($grade['subject_id'] == $subject) {
        //             $gradeInSubj++;
        //         }
        //     } 
        //     return $gradeInSubj ?? 0; // Return the found grade or null if not found
        // }
    }
    ?>