<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\SubjectStream;

class Classes extends Model
{

    protected $table = 'classes';

//    protected $guarded = [];
    protected $fillable = [
        'grade_id',
        'teacher_id',
        'subject_stream_id',
        'name',
        'year',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function subjectStream()
    {
        return $this->belongsTo(SubjectStream::class);
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
