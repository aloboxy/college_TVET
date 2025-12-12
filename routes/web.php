<?php


use App\Http\Controllers\FullCalenderController;
use App\Http\Controllers\MarkSelectorController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\StaffCourseAddController;
use App\Http\Controllers\StudentPlanningController;
use App\Http\Controllers\SupportTeam\AccessController;
use App\Http\Controllers\SupportTeam\CoursePlanningController;
use App\Http\Controllers\SupportTeam\MarkController;
use App\Http\Controllers\SupportTeam\TeacherAddAjaxController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Livewire\Teacheradd;
use App\Livewire\Enrolledcourse;
use App\Livewire\MarkSelector;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BulkTranferStudent;





Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    Route::post('Resits', 'ResitController@selector')->name('resits.selector');
    Route::get('resit/{exam}/{department}/{subject}/{year}', 'ResitController@manage')->name('resits.manage');
    Route::put('update_resit/{exam}/{department}/{subject}/{year}', 'ResitController@update')->name('resits.update');
});
// Route::get('/course', 'CourseController@index')->name('course');
Route::get('/privacy-policy', 'HomeController@privacy_policy')->name('privacy_policy');
Route::get('/terms-of-use', 'HomeController@terms_of_use')->name('terms_of_use');


Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@dashboard')->name('home');
    Route::get('/home', 'HomeController@dashboard')->name('home');
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');

    Route::group(['prefix' => 'my_account'], function () {
        Route::get('/', 'MyAccountController@edit_profile')->name('my_account');
        Route::put('/', 'MyAccountController@update_profile')->name('my_account.update');
        Route::put('/change_password', 'MyAccountController@change_pass')->name('my_account.change_pass');
    });





    /*************** Support Team *****************/
    Route::group(['namespace' => 'SupportTeam',], function () {

        /*************** Students *****************/
        Route::any('teacheradd', 'CourseController@course')->name('addstudent');
        Route::any('enrolledstudent', 'CourseController@my')->name('showEnrolled');

        Route::post('courseadding/{exam}/{class}/{section}/{subject}', 'courseController@enrolledplay')->name('studentget');


        Route::group(['prefix' => 'students'], function () {
            Route::get('reset_pass/{st_id}', 'StudentRecordController@reset_pass')->name('st.reset_pass');
            Route::get('graduated', 'StudentRecordController@graduated')->name('students.graduated');
            Route::put('not_graduated/{id}', 'StudentRecordController@not_graduated')->name('st.not_graduated');
            Route::get('list', 'StudentRecordController@listByClass')->name('students.list');
            Route::get('ledger/{id}', 'GradeSheetLedger@ledger')->name('students.ledger');
            Route::get('group/{class_id}', 'StudentRecordController@groupBy')->name('students.group');
            Route::get('change_college/{st_id}', 'StudentRecordController@student_change_depart')->name('students.change_college');
            Route::put('change_college/save/{st_id}', 'StudentRecordController@student_change_depart_store')->name('students.change_college.store');

            /* Promotions */
            Route::post('promote_selector', 'PromotionController@selector')->name('students.promote_selector');
            Route::get('promotion/manage', 'PromotionController@manage')->name('students.promotion_manage');
            Route::delete('promotion/reset/{pid}', 'PromotionController@reset')->name('students.promotion_reset');
            Route::delete('promotion/reset_all', 'PromotionController@reset_all')->name('students.promotion_reset_all');
            Route::get('promotion/{fc?}/{fs?}/{tc?}/{ts?}/{year?}/{exam_id?}/{pass?}', 'PromotionController@promotion')->name('students.promotion');
            Route::post('promote/{fc}/{fs}/{tc}/{ts}/{year}/{exam_id}/{pass}', 'PromotionController@promote')->name('students.promote');
        });


        // /****************Status *************/
        // Route::get('users', 'UserStatusController@index');
        // Route::get('userChangeStatus', 'UserStatusController@userChangeStatus');

        /*************** Users *****************/
        Route::group(['prefix' => 'users'], function () {
            Route::get('reset_pass/{id}', 'UserController@reset_pass')->name('users.reset_pass');
        });

        /*************** TimeTables *****************/
        Route::group(['prefix' => 'timetables'], function () {
            Route::get('/', 'TimeTableController@index')->name('tt.index');

            Route::group(['middleware' => 'teamSA'], function () {
                Route::post('/', 'TimeTableController@store')->name('tt.store');
                Route::put('/{tt}', 'TimeTableController@update')->name('tt.update');
                Route::delete('/{tt}', 'TimeTableController@delete')->name('tt.delete');
            });

            /*************** TimeTable Records *****************/
            Route::group(['prefix' => 'records'], function () {

                Route::group(['middleware' => 'teamSA'], function () {
                    Route::get('manage/{ttr}', 'TimeTableController@manage')->name('ttr.manage');
                    Route::post('/', 'TimeTableController@store_record')->name('ttr.store');
                    Route::get('edit/{ttr}', 'TimeTableController@edit_record')->name('ttr.edit');
                    Route::put('/{ttr}', 'TimeTableController@update_record')->name('ttr.update');
                });

                Route::get('show/{ttr}', 'TimeTableController@show_record')->name('ttr.show');
                Route::get('print/{ttr}', 'TimeTableController@print_record')->name('ttr.print');
                Route::delete('/{ttr}', 'TimeTableController@delete_record')->name('ttr.destroy');
            });

            /*************** Time Slots *****************/
            Route::group(['prefix' => 'time_slots', 'middleware' => 'teamSA'], function () {
                Route::post('/', 'TimeTableController@store_time_slot')->name('ts.store');
                Route::post('/use/{ttr}', 'TimeTableController@use_time_slot')->name('ts.use');
                Route::get('edit/{ts}', 'TimeTableController@edit_time_slot')->name('ts.edit');
                Route::delete('/{ts}', 'TimeTableController@delete_time_slot')->name('ts.destroy');
                Route::put('/{ts}', 'TimeTableController@update_time_slot')->name('ts.update');
            });
        });

        /*************** Payments *****************/
        Route::group(['prefix' => 'payments'], function () {

            Route::get('manage/{class_id?}', 'PaymentController@manage')->name('payments.manage');
            Route::get('manage/{user_id?}', 'PaymentController@manage')->name('payments.manage');
            Route::get('invoice/{id}/{year?}', 'PaymentController@invoice')->name('payments.invoice');
            Route::get('studentfees/{id}/{year?}', 'PaymentController@student_fees')->name('payments.studentfess');
            Route::get('receipts/{id}', 'PaymentController@receipts')->name('payments.receipts');
            Route::get('pdf_receipts/{id}', 'PaymentController@pdf_receipts')->name('payments.pdf_receipts');
            Route::post('select_year', 'PaymentController@select_year')->name('payments.select_year');
            Route::post('select_year', 'PaymentController@select_year')->name('payments.select_year');
            Route::post('show', 'PaymentController@show')->name('payments.showpay');
            Route::post('select_class', 'PaymentController@select_class')->name('payments.select_class');

            Route::delete('reset_record/{id}', 'PaymentController@reset_record')->name('payments.reset_record');
            Route::post('pay_now/{id}', 'PaymentController@pay_now')->name('payments.pay_now');
            Route::get('payment_selector/{id}', 'PaymentController@payment_year_selector')->name('payments.year_selector');
            Route::post('invoice_year', 'PaymentController@invoice_year')->name('payments.invoice_year');
            Route::get('bill', 'PaymentController@bill')->name('payments.bill');
            Route::post('generalbill', 'PaymentController@generalbill')->name('payments.generalbill');
        });

        /*************** Pins *****************/
        Route::group(['prefix' => 'pins'], function () {
            Route::get('create', 'PinController@create')->name('pins.create');
            Route::get('/', 'PinController@index')->name('pins.index');
            Route::post('/', 'PinController@store')->name('pins.store');
            Route::get('enter/{id}', 'PinController@enter_pin')->name('pins.enter');
            Route::post('verify/{id}', 'PinController@verify')->name('pins.verify');
            Route::delete('/', 'PinController@destroy')->name('pins.destroy');
        });

        /*************** Marks *****************/
        Route::group(['prefix' => 'marks'], function () {

            // FOR teamSA
            Route::group(['middleware' => 'teamSA'], function () {
                Route::get('batch_fix', 'MarkController@batch_fix')->name('marks.batch_fix');
                Route::put('batch_update', 'MarkController@batch_update')->name('marks.batch_update');
                Route::get('tabulation/{exam?}/{class?}/{sec_id?}/{type?}', 'MarkController@tabulation')->name('marks.tabulation');
                Route::post('tabulation', 'MarkController@tabulation_select')->name('marks.tabulation_select');
                Route::get('Pass', 'MarkController@pass')->name('pass.failed');
                Route::get('tabulation/print/{exam_id}/{class_id}/{section_id}/{type}', 'MarkController@print_tabulation')->name('marks.print_tabulation');
                Route::get('/grades/ranges', 'MarkController@getGradeRanges')->name('grades.ranges');
            });

            // FOR teamSAT
            Route::group(['middleware' => 'teamSAT'], function () {
                Route::get('/', 'MarkController@index')->name('marks.index');
                Route::get('download/', 'MarkController@indexdownload')->name('marks.downloadindex');


                Route::get('clinical', 'MarkController@clinical_index')->name('clinical.index');



                Route::get('manage/{exam}/{department}/{subject}/{year}', 'MarkController@manage')->name('marks.manage');
                Route::get('managedownload/{exam}/{department}/{subject}/{year}', 'MarkController@download')->name('marks.download');
                Route::put('update/{exam}/{department}/{subject}/{year}', 'MarkController@update')->name('marks.update');
                Route::put('comment_update/{exr_id}', 'MarkController@comment_update')->name('marks.comment_update');
                Route::put('skills_update/{skill}/{exr_id}', 'MarkController@skills_update')->name('marks.skills_update');



                Route::put('clinic/update/{exam}/{class}/{section}/{subject}/{year}', 'MarkController@update_clinical')->name('clinical.update');


                Route::get('clinic/get/{exam}/{section}/{class}/{subject}/{year}', 'MarkController@clinical_get')->name('clinical.get');

                Route::post('selector', 'MarkController@selector')->name('marks.selector');
                Route::post('selector/download', 'MarkController@selectordownload')->name('marks.selectordownload');
                Route::post('clinical/selector', 'MarkController@clinical')->name('clinical.selector');

                Route::get('bulk/{class?}/{section?}', 'MarkController@bulk')->name('marks.bulk');
                Route::post('bulk', 'MarkController@bulk_select')->name('marks.bulk_select');
            });

            Route::get('select_year/{id}', 'MarkController@year_selector')->name('marks.year_selector');
            Route::post('select_year/{id}', 'MarkController@year_selected')->name('marks.year_select');
            Route::get('show/{id}/{year}', 'MarkController@show')->name('marks.show');
            Route::get('print/{id}/{exam_id}/{year}', 'MarkController@print_view')->name('marks.print');

            Route::delete('delete/{mark}', 'MarkController@destroy')->name('marks.destroy');
        });

        /*************** Grade Change Requests *****************/
        Route::group(['prefix' => 'grade_requests'], function () {
            Route::get('/', 'GradeRequestController@index')->name('grade_requests.index');
            Route::post('/', 'GradeRequestController@store')->name('grade_requests.store');
            Route::put('approve_dept/{id}', 'GradeRequestController@approveDept')->name('grade_requests.approve_dept');
            Route::put('approve_college/{id}', 'GradeRequestController@approveCollege')->name('grade_requests.approve_college');
            Route::put('reject/{id}', 'GradeRequestController@reject')->name('grade_requests.reject');
            Route::put('revert/{id}', 'GradeRequestController@revert')->name('grade_requests.revert');
        });


        Route::group(['prefix' => 'list'], function () {

            Route::get('manage/{exam}/{class}/{section}/{subject}/{year}', 'CourselistController@manage')->name('courselist.manage');
            Route::post('listselector', 'CourselistController@selector')->name('courselist.selector');
            Route::get('/', 'CourselistController@index')->name('courselist.index');
        });



        Route::group(['prefix' => 'access'], function () {
            Route::get('/', 'AccessController@index')->name('access.index');
            Route::post('selector', 'AccessController@selector')->name('access.selector');
            Route::post('delete', 'AccessController@delete')->name('access.delete');
            Route::get('create', 'AccessController@create')->name('access.create');
            Route::get('manage/{exam}/{class}/{section}/{year}', 'AccessController@manage')->name('access.manage');
            Route::put('update/{exam}/{class}/{section}/{year}', 'AccessController@update')->name('access.update');
        });

        Route::group(['prefix' => 'repeat'], function () {
            Route::get('/', 'RepeatController@index')->name('repeat.index');
            Route::post('selector', 'RepeatController@selector')->name('repeat.selector');
        });

        Route::group(['prefix' => 'duplicate'], function () {
            Route::get('/', 'CleanDupliController@index')->name('duplicate.index');
            Route::post('clean', 'CleanDupliController@clean')->name('duplicate.clean');
        });



        Route::resource('students', 'StudentRecordController');
        Route::resource('users', 'UserController');
        Route::resource('classes', 'MyClassController');
        Route::resource('departments', 'ClassTypeController');
        Route::resource('sections', 'SectionController');
        Route::resource('subjects', 'SubjectController');
        Route::resource('grades', 'GradeController');
        Route::resource('exams', 'ExamController');
        Route::resource('dorms', 'DormController');
        Route::resource('payments', 'PaymentController');
        Route::resource('roles', 'RoleController');
        Route::resource('courses', 'CourseController');
        Route::resource('college','CollegeController');
        Route::resource('major', 'MajorController');
        Route::resource('minor', 'MinorController');
        Route::resource('library', 'BookController');
        // Route::resource('access','AccessController');



        Route::get('teacheradd', 'TeacherAddAjaxController@index')->name('teacheradd.index');
        Route::get('teacheradd/getEnrolledStudents', 'TeacherAddAjaxController@getEnrolledStudents')->name('teacheradd.getEnrolledStudents');
        Route::get('teacheradd/getUnenrolledStudents', 'TeacherAddAjaxController@getUnenrolledStudents')->name('teacheradd.getUnenrolledStudents');
        Route::get('department/subject', [TeacherAddAjaxController::class, 'getSubjectByDepartment'])->name('department.subject');
        Route::get('department/course', [TeacherAddAjaxController::class, 'getCourseByDepartment'])->name('department.course');
        /// course student lookup
        Route::get('course/students', [TeacherAddAjaxController::class, 'getStudentsByCourse'])->name('course.students');


        Route::post('teacher/enroll', [TeacherAddAjaxController::class, 'enrollStudent'])->name('teacheradd.enroll');
        Route::delete('teacher/drop', [TeacherAddAjaxController::class, 'dropStudent'])->name('teacheradd.drop');
        Route::get('teacheradd/enrolled', [TeacherAddAjaxController::class, 'getEnrolledStudents'])->name('teacheradd.enrolled');
        Route::get('teacheradd/unenrolled', [TeacherAddAjaxController::class, 'getUnenrolledStudents'])->name('teacheradd.unenrolled');
        Route::get('permissions/assign/{user}', [UserPermissionController::class, 'show'])->name('permissions.assign');
        // Route::put('permissions/assigns/{user}', [UserPermissionController::class, , 'assign'])->name('permissions.assign');
        Route::post('permissions/store', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::delete('permissions/destroy/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
        Route::get('permissions/index', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('permissions/edit/{permission}', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('permissions/update/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::get('department/class', [TeacherAddAjaxController::class, 'getClassByDepartment'])->name('department.class');
        Route::get('department/class/section', [TeacherAddAjaxController::class, 'getClassSection'])->name('class.section');
        Route::get('section/department', [TeacherAddAjaxController::class, 'getSectionByDepartment'])->name('section.department');


        /****status***/
        Route::get('/status-update/{id}', 'StudentRecordController@status_update');

    });

    /************************ AJAX ****************************/
    Route::group(['prefix' => 'ajax'], function () {
        Route::get('get_lga/{state_id}', 'AjaxController@get_lga')->name('get_lga');
        Route::get('get_class_sections/{class_id}', 'AjaxController@get_class_sections')->name('get_class_sections');
        Route::get('get_class_subjects/{class_id}', 'AjaxController@get_class_subjects')->name('get_class_subjects');
        Route::get('get_class_subjects_term/{term_id}', 'AjaxController@get_class_subjects_term')->name('get_class_subjects_term');
        Route::get('get_exam_term/{term_id}', 'AjaxController@get_exam_term')->name('get_class_subjects_term');
        Route::get('department/{college_id}', 'AjaxController@get_major')->name('get_major');

    });



    Route::get('/bulk/transfer/students', [BulkTranferStudent::class, 'list'])->name('bulk.transfer.students');
    Route::post('/process/transfer/students', [BulkTranferStudent::class, 'store'])->name('bulk.process.students');
    Route::post('/middleman/transfer/students', [BulkTranferStudent::class, 'middleman'])->name('bulk.middleman.students');
    Route::get('/bulk/transfer/select/list', [BulkTranferStudent::class, 'selectedCollege'])->name('bulk.transfer.select.list');

    /************************ SUPER ADMIN ****************************/
    Route::group(['namespace' => 'SuperAdmin', 'middleware' => 'super_admin', 'prefix' => 'super_admin'], function () {

        Route::get('/settings', 'SettingController@index')->name('settings');
        Route::put('/settings', 'SettingController@update')->name('settings.update');
        Route::get('/accounting', 'AccountingController@index')->name('accounting');
        Route::get('/accounting/student', 'AccountingController@index_student_records')->name('accounting.index_student_records');
        Route::post('/accounting/student/result', 'AccountingController@student_fees')->name('accounting.student_fees');
        Route::get('/backup-database', 'BackUpController@backup')->name('backup.database');
    });

    Route::get('/courses/list', 'SupportTeam\CourseController@list')->name('courses.list');
    Route::get('/planning', [StudentPlanningController::class, 'index'])->name('planning.view');
    Route::get('/planning/courses', [StudentPlanningController::class, 'getCourses']);
    Route::post('/planning/enroll', [StudentPlanningController::class, 'enroll']);
    Route::post('/planning/drop-course', [StudentPlanningController::class, 'dropCourse'])->name('drop.course');

    Route::get('/planning/planned-courses', [StudentPlanningController::class, 'plannedCourses'])->name('planned.courses');
    Route::get('/planning/course/list', [StudentPlanningController::class, 'courselist'])->name('course.enrolled.list');
    /************************ PARENT ****************************/
    Route::group(['namespace' => 'MyParent', 'middleware' => 'my_parent'], function () {

        Route::get('/my_children', 'MyController@children')->name('my_children');
    });

    Route::group(['prefix' => 'course'], function () {
        // Route::get('course/list', 'CourseController@StudentCourseList')->name('studentcourse');
        Route::get('/list', 'SupportTeam\CourseController@StudentCourseList')->name('studentcourse');
    });


    Route::get('fullcalender', [FullCalenderController::class, 'index'])->name('calender');
    Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax']);




    ///Livewire route for testing
    Route::get('planned/course/list', 'CoursePlanningController@index')->name('courselist.planned_course_list');
    Route::post('planned/enrolled', 'CoursePlanningController@enrolled')->name('courselist.get');



    Route::get('year/semester', [MarkSelectorController::class, 'exam']);
    Route::get('exam/department', [MarkSelectorController::class, 'exam_department']);
    Route::get('year/class', [MarkSelectorController::class, 'class']);
    Route::get('class/section', [MarkSelectorController::class, 'section']);
    Route::get('colleges/department', [MarkSelectorController::class, 'department']);
    Route::get('colleges/majors', [MarkSelectorController::class, 'get_major']);
    Route::get('colleges/minors', [MarkSelectorController::class, 'get_minor']);
    Route::get('class/subject', [MarkSelectorController::class, 'subject']);
    Route::get('clinical/subject', [MarkSelectorController::class, 'clinical_subject']);
    Route::get('resit/selector', [MarkSelectorController::class, 'resit'])->name('resit.selector');
    Route::post('resit/show', [MarkSelectorController::class, 'resitshow'])->name('resit.show');
    Route::get('resit/index', [MarkSelectorController::class, 'resitgrade'])->name('resits');

    Route::get('/get-subjects-by-level', [MarkSelectorController::class, 'getSubjectsByLevel'])->name('get.subjects.by.level');


    Route::get('class/enrolled', [StaffCourseAddController::class, 'subject']);
        Route::post('update-student-status', 'AjaxController@student_status')->name('update-student-status');

});
