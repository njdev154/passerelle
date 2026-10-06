<?php

use App\Http\Controllers\Auth\GoogleAuthenticationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyApplicationController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\JobOfferController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (\Illuminate\Http\Request $request) {
    return view('welcome', ['offers' => app(JobOfferController::class)->featured($request)]);
})->name('home');

Route::get('/offres', [JobOfferController::class, 'index'])->name('offers.index');
Route::get('/offres/{offer:slug}', [JobOfferController::class, 'show'])->name('offers.show');
Route::get('/entreprises', [PublicPageController::class, 'companies'])->name('public.companies');
Route::get('/a-propos', [PublicPageController::class, 'about'])->name('public.about');
Route::get('/conseils', [PublicPageController::class, 'advice'])->name('public.advice');
Route::get('/faq', [PublicPageController::class, 'faq'])->name('public.faq');
Route::get('/contact', [PublicPageController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicPageController::class, 'storeContact'])->middleware('throttle:6,1')->name('public.contact.store');
Route::get('/confidentialite', [PublicPageController::class, 'privacy'])->name('public.privacy');
Route::get('/conditions', [PublicPageController::class, 'terms'])->name('public.terms');

Route::middleware('guest')->group(function (): void {
    Route::view('/connexion', 'auth.login')->name('login');
    Route::post('/connexion', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/inscription', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/inscription', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get('/mot-de-passe-oublie', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/mot-de-passe-oublie', [PasswordResetController::class, 'send'])->name('password.email');
    Route::get('/nouveau-mot-de-passe/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/nouveau-mot-de-passe', [PasswordResetController::class, 'update'])->name('password.update');
});
Route::get('/auth/google/redirect', [GoogleAuthenticationController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthenticationController::class, 'callback'])->name('auth.google.callback');

Route::middleware('auth')->group(function (): void {
    Route::get('/verifier-votre-email', [EmailVerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/verifier-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/verification-email', [EmailVerificationController::class, 'send'])->middleware('throttle:6,1')->name('verification.send');
    Route::post('/deconnexion', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/demarrage/role', [DashboardController::class, 'role'])->name('onboarding.role');
    Route::post('/demarrage/role', [DashboardController::class, 'storeRole'])->name('onboarding.role.store');
    Route::get('/tableau-de-bord', [DashboardController::class, 'show'])->name('dashboard');

    Route::middleware('role:student')->group(function (): void {
        Route::get('/mon-profil', [StudentProfileController::class, 'edit'])->name('student.profile.edit');
        Route::put('/mon-profil', [StudentProfileController::class, 'update'])->name('student.profile.update');
        Route::post('/mon-profil/projets', [StudentProfileController::class, 'storeProject'])->name('student.projects.store');
        Route::delete('/mon-profil/projets/{project}', [StudentProfileController::class, 'destroyProject'])->name('student.projects.destroy');
        Route::post('/offres/{offer:slug}/candidatures', [ApplicationController::class, 'store'])->name('applications.store');
    });

    Route::middleware('role:company')->group(function (): void {
        Route::get('/entreprise/demarrage', [CompanyController::class, 'create'])->name('company.create');
        Route::post('/entreprise/demarrage', [CompanyController::class, 'store'])->name('company.store');
        Route::get('/entreprise/tableau-de-bord', [CompanyController::class, 'dashboard'])->name('company.dashboard');
        Route::get('/entreprise/offres/nouvelle', [JobOfferController::class, 'create'])->name('company.offers.create');
        Route::post('/entreprise/offres', [JobOfferController::class, 'store'])->name('company.offers.store');
        Route::get('/entreprise/offres/{offer}/candidatures', [CompanyApplicationController::class, 'index'])->name('company.offers.applications');
        Route::patch('/entreprise/candidatures/{application}', [CompanyApplicationController::class, 'update'])->name('company.applications.update');
    });

    Route::middleware('role:school')->group(function (): void {
        Route::get('/etablissement/demarrage', [SchoolController::class, 'create'])->name('school.create');
        Route::post('/etablissement/demarrage', [SchoolController::class, 'store'])->name('school.store');
        Route::get('/etablissement/tableau-de-bord', [SchoolController::class, 'dashboard'])->name('school.dashboard');
        Route::get('/etablissement/stages/nouveau', [SchoolController::class, 'createInternship'])->name('school.internships.create');
        Route::post('/etablissement/stages', [SchoolController::class, 'storeInternship'])->name('school.internships.store');
        Route::patch('/etablissement/stages/{internship}', [SchoolController::class, 'updateInternship'])->name('school.internships.update');
    });

    Route::middleware('role:admin')->group(function (): void {
        Route::get('/administration', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::patch('/administration/entreprises/{company}/verifier', [AdminController::class, 'verifyCompany'])->name('admin.companies.verify');
        Route::patch('/administration/entreprises/{company}/refuser', [AdminController::class, 'rejectCompany'])->name('admin.companies.reject');
        Route::patch('/administration/offres/{offer}/publier', [AdminController::class, 'publishOffer'])->name('admin.offers.publish');
        Route::patch('/administration/offres/{offer}/retirer', [AdminController::class, 'archiveOffer'])->name('admin.offers.archive');
        Route::patch('/administration/messages/{message}/lire', [AdminController::class, 'markMessageRead'])->name('admin.messages.read');
    });
});
