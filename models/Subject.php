<?php


    require_once 'Model.php';

    class Subject extends Model{
        protected static $table = "subjects"; //edit based sa pangalan ng table sa database

        //mga gusto maretrieve sa  table, kung ano pangalan ng column sa users na table, dapat EXACTLY pareho sa column name sa table
        public $id;
        public $code;
        public $catalog_no;
        public $name;
        public $day;
        public $time;
        public $room;
        public $course_id;
        public $semester;
        public $year_level;
        public $instructor_id;

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
                "id" => $this->id,
                "code" => $this->code,
                "catalog_no" => $this->catalog_no,
                "name" => $this->name,
                "day" => $this->day,
                "time" => $this->time,
                "room" => $this->room,
                "course_id" => $this->course_id,
                "semester" => $this->semester,
                "year_level" => $this->year_level,
                "instructor_id" => $this->instructor_id
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
            return Course::find($this->course_id);
        }

        public function instructor(){
            return User::find($this->instructor_id);
        }

        public function students(){
            return $this->belongsToMany(Student::class, 'subject_enrollments', 'subject_id', 'student_id');
        }

        public function gradedStudents(){
            $students = $this->belongsToMany(Student::class, 'grades', 'subject_id', 'student_id');
            return $students ?? null;
        }
        
    }   



?>