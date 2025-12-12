<?php

namespace App\Repositories;

use App\Models\ClassType;
use App\Models\Course;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use Carbon\Carbon;

class MyClassRepo
{

    public function all()
    {
        return MyClass::where('active',1)->orderBy('name', 'asc')->with('class_type')->get();
    }

    public function allclasstype()
    {
        return ClassType::orderBy('name', 'asc')->get();
    }

    public function getMC($data)
    {
        return MyClass::where($data)->with('section');
    }

    public function find($id)
    {
        return MyClass::find($id);
    }

    public function create($data)
    {
        return MyClass::create($data);
    }

    public function createclasstype($data)
    {
        return ClassType::create($data);
    }

    public function update($id, $data)
    {
        return MyClass::find($id)->update($data);
    }

    public function delete($id)
    {
        return MyClass::destroy($id);
    }

    public function getTypes()
    {
        return ClassType::orderBy('name', 'asc')->get();
    }

    public function findType($class_type_id)
    {
        return ClassType::find($class_type_id);
    }

    public function findclassType($id)
    {
        return ClassType::find($id);
    }

    public function findTypeByClass($class_id)
    {
        return ClassType::find($this->find($class_id)->class_type_id);
    }

    /************* Section *******************/

    public function createSection($data)
    {
        return Section::create($data);
    }

    public function findSection($id)
    {
        return Section::find($id);
    }

    public function updateclasstype($id, $data)
    {
        return ClassType::find($id)->update($data);
    }

    public function updateSection($id, $data)
    {
        return Section::find($id)->update($data);
    }

    public function deleteSection($id)
    {
        return Section::destroy($id);
    }

    public function deleteclasstype($id)
    {
        return ClassType::destroy($id);
    }

    public function isActiveSection($section_id)
    {
        return Section::where(['id' => $section_id, 'active' => 1])->exists();
    }

    public function getAllSections()
    {
        return Section::orderBy('name', 'asc')->with(['my_class', 'teacher'])->get();
    }

    public function getClassSections($class_id)
    {
        return Section::where(['my_class_id' => $class_id])->orderBy('name', 'asc')->get();
    }

    /************* Subject *******************/

    public function createSubject($data)
    {
        return Subject::create($data);
    }

    public function createCourse($data)
    {
        return Course::create($data);
    }

    public function findSubject($id)
    {
        return Subject::find($id);
    }

    public function findCourse($id)
    {
        return Course::find($id);
    }

    public function findSubjectByClass($class_id, $order_by = 'name')
    {
        return $this->getSubject(['my_class_id'=> $class_id])->orderBy($order_by)->get();
    }

    public function findSubjectByClassCourse($class_id)
    {
        return $this->getCourse(['my_class_id'=> $class_id])->get();
    }

    public function findSubjectByTeacher($teacher_id, $order_by = 'name')
    {
        return $this->getSubject(['teacher_id'=> $teacher_id])->orderBy($order_by)->get();
    }

//     public function getClassSubjectTerm($class_id, $term_id)
//   {
//     ;
//   }


    public function getCourse($data)
    {
        return Course::where($data);
    }
    public function getSubject($data)
    {
        return Subject::where($data);
    }

    public function getSubjectsByIDs($ids)
    {
        return Subject::whereIn('id', $ids)->orderBy('name')->get();
    }

    public function getCoursesBySubjectID($ids)
    {
        return Course::whereIn('subject_id', $ids)->with('subject')->get();
    }

    public function updateSubject($id, $data)
    {
        return Subject::find($id)->update($data);
    }

    public function updateCourse($id, $data)
    {
        return Course::find($id)->update($data);
    }

    public function deleteSubject($id)
    {
        return Subject::destroy($id);
    }

    public function deleteCourse($id)
    {
        return Course::destroy($id);
    }

    public function getAllSubjects()
    {
        return Subject::orderBy('name', 'asc')->with(['my_class', 'teacher'])->get();
    }

    public function getAllCourses()
    {
        $nowtdate = Carbon::now();
        $meat= $nowtdate->addMonths(0);

        return Course::where('created_at','<',$meat)->orderBy('id', 'asc')->with(['my_class', 'teacher'])->get();
    }

}
