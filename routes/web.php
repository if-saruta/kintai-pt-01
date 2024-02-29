<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\CsvIssueController;
use App\Http\Controllers\PdfEditController;
use App\Http\Controllers\PdfputController;
use App\Http\Controllers\InvoiceController;
use App\Models\Company;
use App\Models\Employee;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/home', function(){
        return view('home');
    })->name('home');

    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/store', [EmployeeController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [EmployeeController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [EmployeeController::class, 'delete'])->name('delete');
        Route::post('/csv', [EmployeeController::class, 'csvImport'])->name('csv');
    });

    Route::prefix('company')->name('company.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('');
        Route::post('/store', [CompanyController::class, 'store'])->name('store');
        Route::post('/update', [CompanyController::class, 'update'])->name('update');
        Route::get('/delete', [CompanyController::class, 'delete'])->name('delete');

        Route::post('/csv', [CompanyController::class, 'csvImport'])->name('csv');
    });

    Route::prefix('vehicle')->name('vehicle.')->group(function () {
        Route::get('/', [VehicleController::class, 'index'])->name('');
        Route::post('/store', [VehicleController::class, 'store'])->name('store');
        Route::post('/update', [VehicleController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [VehicleController::class, 'delete'])->name('delete');
        Route::post('/csv', [VehicleController::class, 'csvImport'])->name('csv');
    });

    Route::prefix('project')->name('project.')->group(function () {
        Route::get('/', [ProjectController::class, 'index'])->name('');
        Route::get('/create', [ProjectController::class, 'create'])->name('create');
        Route::post('/store', [ProjectController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [ProjectController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [ProjectController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [ProjectController::class, 'delete'])->name('delete');
        Route::get('/projectDelete/{id}', [ProjectController::class, 'projectDelete'])->name('projectDelete');
        Route::get('/employee_payment_show/{id}', [ProjectController::class, 'employeePaymentShow'])->name('employeePaymentShow');
        Route::post('/csv', [ProjectController::class, 'csvImport'])->name('csv');
    });

    Route::prefix('shift')->name('shift.')->group(function () {
        Route::get('/', [ShiftController::class, 'index'])->name('');
        Route::post('/', [ShiftController::class, 'selectWeek'])->name('');
        Route::get('/selectWeek', [ShiftController::class, 'selectWeek'])->name('selectWeek');
        Route::post('/selectWeek', [ShiftController::class, 'selectWeek'])->name('selectWeek');
        // Route::get('/create/{id}', [ShiftController::class, 'create'])->name('create');
        Route::post('/store', [ShiftController::class, 'store'])->name('store');

        Route::get('/edit', [ShiftController::class, 'selectWeek'])->name('edit');
        Route::post('/edit', [ShiftController::class, 'selectWeek'])->name('edit');
        Route::post('/edit/selectWeek', [ShiftController::class, 'selectWeek'])->name('editSelectWeek');

        Route::post('/update', [ShiftController::class, 'update'])->name('update');
        Route::post('/delete', [ShiftController::class, 'delete'])->name('delete');

        Route::post('/employeeShowShift', [ShiftController::class, 'selectWeek'])->name('employeeShowShift');
        Route::post('/employeeShowShift/selectWeek', [ShiftController::class, 'selectWeek'])->name('employeeShowShiftSelectWeek');

        Route::post('/employeePriceShift', [ShiftController::class, 'selectWeek'])->name('employeePriceShift');
        Route::post('/employeePriceShift/selectWeek', [ShiftController::class, 'selectWeek'])->name('employeePriceShiftSelectWeek');

        Route::post('/projectPriceShift', [ShiftController::class, 'selectWeek'])->name('projectPriceShift');
        Route::post('/projectPriceShift/selectWeek', [ShiftController::class, 'selectWeek'])->name('projectPriceShiftSelectWeek');

        Route::post('/projectCount', [ShiftController::class, 'selectWeek'])->name('projectCount');
        Route::post('/projectCount/selectWeek', [ShiftController::class, 'selectWeek'])->name('projectCountSelectWeek');

        Route::get('/csv', [ShiftController::class, 'csv'])->name('csv');
        Route::post('/csv/import', [ShiftController::class, 'csvImport'])->name('csvImport');


        // Route::get('/project', [ShiftController::class, 'project'])->name('project');
    });

    // Route::prefix('shiftImport')->name('shiftImport.')->group(function () {
    //     Route::get('/', [ShiftController::class, 'shiftImport'])->name('');
    //     Route::post('/confirm', [ShiftController::class, 'shiftConfirmCsv'])->name('confirm-csv');
    //     Route::post('/shiftImport-Csv', [ShiftController::class, 'shiftImportCsv'])->name('Csv');
    // });

    Route::prefix('csv-issue')->name('csv-issue.')->group(function () {
        Route::get('/', [CsvIssueController::class, 'index'])->name('');
        Route::post('/show', [CsvIssueController::class, 'show'])->name('show');
        Route::get('/export/{projectId}/{month}', [CsvIssueController::class, 'csvExport'])->name('export');
    });

    Route::prefix('csv-employee')->name('csv-employee.')->group(function () {
        Route::get('/', [CsvIssueController::class, 'employeeIndex'])->name('');
        Route::post('/show', [CsvIssueController::class, 'employeeShow'])->name('show');
        Route::get('/export/{employeeId}/{year}/{month}', [CsvIssueController::class, 'employeeCsvExport'])->name('export');
    });

    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('');

        // ドライバー関連
        Route::get('/driver', [InvoiceController::class, 'driverShift'])->name('driverShift');
        Route::post('/driver-create', [InvoiceController::class, 'driverShiftCreate'])->name('driverShiftCreate');
        Route::post('/driver-update', [InvoiceController::class, 'driverShiftUpdate'])->name('driverShiftUpdate');
        Route::post('/driver-calendar-pdf', [InvoiceController::class, 'driverCalendarPDF'])->name('driver-calendar-pdf');

        // 案件関連
        Route::get('/project', [InvoiceController::class, 'projectShift'])->name('projectShift');
        Route::post('/project-update', [InvoiceController::class, 'projectShiftUpdate'])->name('projectShiftUpdate');
        Route::post('/project-delete', [InvoiceController::class, 'projectShiftDelete'])->name('projectShiftDelete');
        Route::post('/project-calendar-pdf', [InvoiceController::class, 'projectCalendarPDF'])->name('project-calendar-pdf');

        // 検索機能関連
        Route::get('/search-shift', [InvoiceController::class, 'searchShift'])->name('searchShift');
        Route::post('/search-shift', [InvoiceController::class, 'searchShift'])->name('searchShift');
        Route::post('/search-project-shift', [InvoiceController::class, 'searchProjectShift'])->name('searchProjectShift');

        // チャーター関連
        Route::get('/charter', [InvoiceController::class, 'charterShift'])->name('charterShift');
        Route::post('/search-charter-shift', [InvoiceController::class, 'searchCharterShift'])->name('searchCharterShift');
        Route::get('/find-charter-shift/{year}/{month}', [InvoiceController::class, 'findCharterDate'])->name('findCharterShift');
        Route::post('/charter-shift-update', [InvoiceController::class, 'charterShiftUpdate'])->name('charter-shift-update');
        Route::post('/charter-client-update', [InvoiceController::class, 'charterClientUpdate'])->name('charter-client-update');
        Route::post('/charter-project-update', [InvoiceController::class, 'charterProjectUpdate'])->name('charter-project-update');
        Route::post('/charter-driver-update', [InvoiceController::class, 'charterDriverUpdate'])->name('charter-driver-update');
        Route::post('/charter-calendar-pdf', [InvoiceController::class, 'charterCalendarPDF'])->name('charter-calendar-pdf');

        // pdf発行前編集画面
        Route::post('/driver-edit-pdf', [PdfEditController::class, 'driver_edit_pdf'])->name('driver-edit-pdf');
        Route::post('/project-edit-pdf', [PdfEditController::class, 'project_edit_pdf'])->name('project-edit-pdf');
        // pdf発行
        Route::post('/driver-issue-pdf', [PdfputController::class, 'driver_issue_pdf'])->name('driver-issue-pdf');
        Route::post('/company-issue-pdf', [PdfputController::class, 'company_issue_pdf'])->name('company-issue-pdf');
        Route::post('/project-issue-pdf', [PdfputController::class, 'project_issue_pdf'])->name('project-issue-pdf');
    });

    Route::get('/dompdf/pdf', [PdfputController::class, 'pdf_sample']);
});




require __DIR__ . '/auth.php';
