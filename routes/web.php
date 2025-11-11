<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    DashboardController,
    CoursePublicController,
    ProfileController
};
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Teacher\CourseController as TeacherCourse;
use App\Http\Controllers\Student\EnrollController as StudentEnroll;
use App\Http\Middleware\RoleMiddleware; 
use App\Http\Controllers\Teacher\CourseController;
use App\Http\Controllers\Teacher\MaterialController;
use App\Http\Controllers\Teacher\AssignmentController as TeacherAssignment;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//
// Halaman utama (Publik)
//
Route::get('/', [HomeController::class, 'index'])->name('home');

//
// Dashboard (butuh login)
//
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

//
// Admin-only
//
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/users', AdminUser::class)->name('admin.users');
});



//
// Teacher-only
//
Route::middleware(['auth', RoleMiddleware::class . ':teacher'])->group(function () {

    Route::get('/teacher/courses', [TeacherCourse::class, 'index'])->name('teacher.courses');
    Route::get('/teacher/courses/create', [TeacherCourse::class, 'create'])->name('teacher.courses.create');
    Route::post('/teacher/courses', [TeacherCourse::class, 'store'])->name('teacher.courses.store');
    Route::get('/teacher/courses/{course}', [TeacherCourse::class, 'show'])->name('teacher.courses.show');
    Route::get('/teacher/courses/{course}/edit', [TeacherCourse::class, 'edit'])->name('teacher.courses.edit');
    Route::put('/teacher/courses/{course}', [TeacherCourse::class, 'update'])->name('teacher.courses.update');
    Route::delete('/teacher/courses/{course}', [TeacherCourse::class, 'destroy'])->name('teacher.courses.destroy');

    Route::get('/teacher/courses/{course}/materials/create', [MaterialController::class, 'create'])
        ->name('teacher.materials.create');
    Route::post('/teacher/courses/{course}/materials', [MaterialController::class, 'store'])
        ->name('teacher.materials.store');
    Route::get('/teacher/courses/{course}/materials/{material}', [MaterialController::class, 'show'])
        ->name('teacher.materials.show');
    Route::get('/teacher/courses/{course}/materials/{material}/edit', [MaterialController::class, 'edit'])
        ->name('teacher.materials.edit');
    Route::put('/teacher/courses/{course}/materials/{material}', [MaterialController::class, 'update'])
        ->name('teacher.materials.update');
    Route::delete('/teacher/courses/{course}/materials/{material}', [MaterialController::class, 'destroy'])
        ->name('teacher.materials.destroy');

    Route::get('/teacher/courses/{course}/assignments/create', function (\App\Models\Course $course) {
        return view('teacher.assignments-create', compact('course'));
    })->name('teacher.assignments.create');

    Route::get('/teacher/courses/{course}/assignments/create', [TeacherAssignment::class, 'create'])
        ->name('teacher.assignments.create');
    Route::post('/teacher/courses/{course}/assignments', [TeacherAssignment::class, 'store'])
        ->name('teacher.assignments.store');
    Route::get('/teacher/courses/{course}/assignments/{assignment}', [TeacherAssignment::class, 'show'])
        ->name('teacher.assignments.show');
    Route::get('/teacher/courses/{course}/assignments/{assignment}/edit', [TeacherAssignment::class, 'edit'])
        ->name('teacher.assignments.edit');
    Route::put('/teacher/courses/{course}/assignments/{assignment}', [TeacherAssignment::class, 'update'])
        ->name('teacher.assignments.update');
    Route::delete('/teacher/courses/{course}/assignments/{assignment}', [TeacherAssignment::class, 'destroy'])
        ->name('teacher.assignments.destroy');

});


//
// Student-only
//
Route::middleware(['auth', RoleMiddleware::class . ':student'])->group(function () {
    Route::get('/student/my-courses', [StudentEnroll::class, 'myCourses'])->name('student.mycourses');
    Route::post('/student/enroll/{course}', [StudentEnroll::class, 'enroll'])->name('student.enroll');
});

//
// Publik (katalog & detail kursus)
//
Route::get('/courses', [CoursePublicController::class, 'index'])->name('courses.catalog');
Route::get('/courses/{course}', [CoursePublicController::class, 'show'])->name('courses.show');

//
// Profile (Breeze default)
//
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//
// Auth scaffolding (login/register/logout)
//
require __DIR__ . '/auth.php';
