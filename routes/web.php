<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FixedShiftController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\CsvIssueController;
use App\Http\Controllers\PdfEditController;
use App\Http\Controllers\PdfputController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InfoManagementController;
use App\Http\Controllers\DefinitiveShiftPdfController;
use App\Http\Controllers\MailSendController;

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

// 初期サクセスはログイン画面
Route::get('/', function () {
    return redirect()->guest(route('login'));
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 管理者以上
Route::middleware('can:admin-higher')->group(function () {
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
        Route::get('/register/{id}', [EmployeeController::class, 'register'])->name('register');
        Route::post('/registerStore', [EmployeeController::class, 'registerStore'])->name('registerStore');
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
        Route::get('/create', [VehicleController::class, 'create'])->name('create');
        Route::post('/store', [VehicleController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [VehicleController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [VehicleController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [VehicleController::class, 'delete'])->name('delete');
        Route::post('/allShow', [VehicleController::class, 'allShow'])->name('allShow');
        Route::get('/allShow', [VehicleController::class, 'allShow'])->name('allShow');
        Route::post('/downloadPdf', [VehicleController::class, 'downloadPdf'])->name('downloadPdf');
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
        // 手当削除
        Route::get('allowanceDelete/{allowanceId}/{clientId}', [ProjectController::class, 'allowanceDelete'])->name('allowanceDelete');
        Route::get('/employee_payment_show/{id}', [ProjectController::class, 'employeePaymentShow'])->name('employeePaymentShow');

        // 案件情報
        Route::get('/info/{id}', [ProjectController::class, 'info'])->name('info');
        Route::get('/info/{id}/edit', [ProjectController::class, 'infoEdit'])->name('infoEdit');
        Route::post('/info/update', [ProjectController::class, 'infoUpdate'])->name('infoUpdate');
        Route::post('/infoPdf', [ProjectController::class, 'infoPdf'])->name('infoPdf');

        // 固定シフト
        Route::get('/{id}/fixedShift', [FixedShiftController::class, 'index'])->name('fixedShift');
        Route::get('/{id}/fixedShift/create', [FixedShiftController::class, 'create'])->name('fixedShiftCreate');

        Route::post('/csv', [ProjectController::class, 'csvImport'])->name('csv');
    });

    Route::prefix('shift')->name('shift.')->group(function () {
        Route::get('/', [ShiftController::class, 'index'])->name('');
        Route::post('/', [ShiftController::class, 'selectWeek'])->name('');
        Route::get('/selectWeek', [ShiftController::class, 'selectWeek'])->name('selectWeek');
        Route::post('/selectWeek', [ShiftController::class, 'selectWeek'])->name('selectWeek');
        Route::post('/store', [ShiftController::class, 'store'])->name('store');
        Route::post('/weekStore', [ShiftController::class, 'weekStore'])->name('weekStore');

        Route::get('/edit', [ShiftController::class, 'selectWeek'])->name('edit');
        Route::post('/edit', [ShiftController::class, 'selectWeek'])->name('edit');
        Route::post('/edit/selectWeek', [ShiftController::class, 'selectWeek'])->name('editSelectWeek');

        Route::post('/update', [ShiftController::class, 'update'])->name('update');
        Route::post('/delete', [ShiftController::class, 'delete'])->name('delete');

        Route::post('/bulkReflection', [ShiftController::class, 'bulkReflection'])->name('bulkReflection');

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

        // PDF発行
        Route::post('/allViewPdf', [PdfputController::class, 'allViewDownloadPdf'])->name('allViewPdf');
    });

    // API
    Route::get('/fetch-data/{id}', [ShiftController::class, 'fetchData']);
    Route::get('/fetch-employee-data/{id}', [ShiftController::class, 'fetchEmployeeData']);
    Route::post('/store-memo', [ShiftController::class, 'storeMemo']);
    Route::get('/fetch-project-data/{id}', [ShiftController::class, 'fetchProjectData']);
    Route::get('/fetch-project-amount/{projectId}/{employeeId}', [ShiftController::class, 'fetchProjectAmount']);
    Route::post('/create-allowance', [InvoiceController::class, 'allowanceCreate']);
    Route::post('/allowance-update', [InvoiceController::class, 'allowanceUpdate']);
    Route::get('/allowance-delete/{allowanceId}/{shiftPvId}', [InvoiceController::class, 'allowanceDelete']);

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

    // 請求書
    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('');

        // ドライバー関連
        Route::get('/driver', [InvoiceController::class, 'driverShift'])->name('driverShift');
        Route::post('/driver-create', [InvoiceController::class, 'driverShiftCreate'])->name('driverShiftCreate');
        Route::post('/driver-update', [InvoiceController::class, 'driverShiftUpdate'])->name('driverShiftUpdate');
        Route::post('/over-time-update', [InvoiceController::class, 'overTimeUpdate'])->name('overTimeUpdate');
        Route::get('/allowance-delete/{id}', [InvoiceController::class, 'allowanceDelete'])->name('allowanceDelete');
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
        Route::get('/find-charter-shift', [InvoiceController::class, 'findCharterDate'])->name('findCharterShift');
        Route::post('/find-charter-shift', [InvoiceController::class, 'findCharterDate'])->name('findCharterShift');
        Route::post('/charter-shift-update', [InvoiceController::class, 'charterShiftUpdate'])->name('charter-shift-update');
        Route::post('/charter-client-update', [InvoiceController::class, 'charterClientUpdate'])->name('charter-client-update');
        Route::post('/charter-project-update', [InvoiceController::class, 'charterProjectUpdate'])->name('charter-project-update');
        Route::post('/charter-driver-update', [InvoiceController::class, 'charterDriverUpdate'])->name('charter-driver-update');
        Route::post('/charter-project-unregister', [InvoiceController::class, 'charterProjectChangeUnregister'])->name('charter-project-unregister');
        Route::post('/charter-calendar-pdf', [InvoiceController::class, 'charterCalendarPDF'])->name('charter-calendar-pdf');
        Route::post('/charter-calendar-csv', [InvoiceController::class, 'charterCalendarCsv'])->name('charter-calendar-csv');

        // pdf発行前編集画面
        Route::post('/driver-edit-pdf', [PdfEditController::class, 'driver_edit_pdf'])->name('driver-edit-pdf');
        Route::post('/project-edit-pdf', [PdfEditController::class, 'project_edit_pdf'])->name('project-edit-pdf');
        // pdf発行
        Route::post('/driver-issue-pdf', [PdfputController::class, 'driver_issue_pdf'])->name('driver-issue-pdf');
        Route::post('/company-issue-pdf', [PdfputController::class, 'company_issue_pdf'])->name('company-issue-pdf');
        Route::post('/project-issue-pdf', [PdfputController::class, 'project_issue_pdf'])->name('project-issue-pdf');
    });

    // 情報管理
    Route::prefix('info-management')->name('info-management.')->group(function () {
        Route::get('/', [InfoManagementController::class, 'index'])->name('');
        Route::get('/edit', [InfoManagementController::class, 'edit'])->name('edit');
        Route::post('/update', [InfoManagementController::class, 'updateOrCreate'])->name('update');
    });

    // 稼働表確定版一覧
    Route::prefix('definitive-shift-pdf')->name('definitive.')->group(function () {
        Route::get('/', [DefinitiveShiftPdfController::class, 'index'])->name('');
        Route::get('/list-month/{year}', [DefinitiveShiftPdfController::class, 'listMonth'])->name('listMonth');
        Route::get('/list-pdf/{year}/{month}', [DefinitiveShiftPdfController::class, 'listPdf'])->name('listPdf');
        Route::get('/pdf-download/{year}/{month}/{fileName}', [DefinitiveShiftPdfController::class, 'pdfDownload'])->name('pdfDownload');
    });

    // メール
    // Route::get('/mail', [MailSendController::class, 'send']);

    Route::get('/dompdf/pdf', [PdfputController::class, 'pdf_sample']);

});

// 一般ユーザー
Route::middleware('can:user-higher')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/home', function(){
        return view('home');
    })->name('home');

    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('');
        Route::get('/show/{id}', [EmployeeController::class, 'edit'])->name('show');
    });

    Route::prefix('vehicle')->name('vehicle.')->group(function () {
        Route::get('/', [VehicleController::class, 'index'])->name('');
    });

    Route::prefix('shift')->name('shift.')->group(function () {
        Route::get('/', [ShiftController::class, 'index'])->name('');

        Route::get('/selectWeek', [ShiftController::class, 'selectWeek'])->name('selectWeek');
        Route::post('/selectWeek', [ShiftController::class, 'selectWeek'])->name('selectWeek');

        Route::get('/employeeShowShift', [ShiftController::class, 'selectWeek'])->name('employeeShowShift');
        Route::post('/employeeShowShift', [ShiftController::class, 'selectWeek'])->name('employeeShowShift');
        Route::post('/employeeShowShift/selectWeek', [ShiftController::class, 'selectWeek'])->name('employeeShowShiftSelectWeek');

        Route::post('/employeePriceShift', [ShiftController::class, 'selectWeek'])->name('employeePriceShift');
        Route::post('/employeePriceShift/selectWeek', [ShiftController::class, 'selectWeek'])->name('employeePriceShiftSelectWeek');

        Route::post('/projectCount', [ShiftController::class, 'selectWeek'])->name('projectCount');
        Route::post('/projectCount/selectWeek', [ShiftController::class, 'selectWeek'])->name('projectCountSelectWeek');

    });

    Route::prefix('csv-issue')->name('csv-issue.')->group(function () {
        Route::get('/', [CsvIssueController::class, 'index'])->name('');
        Route::post('/show', [CsvIssueController::class, 'show'])->name('show');
        Route::get('/export/{projectId}/{month}', [CsvIssueController::class, 'csvExport'])->name('export');
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
        Route::get('/find-charter-shift', [InvoiceController::class, 'findCharterDate'])->name('findCharterShift');
        Route::post('/find-charter-shift', [InvoiceController::class, 'findCharterDate'])->name('findCharterShift');
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

// ドライバー
Route::middleware('can:driver-higher')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/home', function(){
        return view('home');
    })->name('home');

    Route::prefix('shift')->name('shift.')->group(function () {
        Route::get('/', [ShiftController::class, 'index'])->name('');

        Route::get('/selectWeek', [ShiftController::class, 'selectWeek'])->name('selectWeek');
        Route::post('/selectWeek', [ShiftController::class, 'selectWeek'])->name('selectWeek');

        Route::get('/employeeShowShift', [ShiftController::class, 'selectWeek'])->name('employeeShowShift');
        Route::post('/employeeShowShift', [ShiftController::class, 'selectWeek'])->name('employeeShowShift');
        Route::post('/employeeShowShift/selectWeek', [ShiftController::class, 'selectWeek'])->name('employeeShowShiftSelectWeek');

    });
});




require __DIR__ . '/auth.php';
