<?php

use App\Http\Controllers\Admin\ClientController as AdminClientController;use App\Http\Controllers\Admin\ClientFollowupController as AdminClientFollowupController;
use App\Http\Controllers\Admin\LeadController as AdminLeadController;
use App\Http\Controllers\Admin\LeadFollowupController as AdminLeadFollowupController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;use App\Http\Controllers\Admin\IncomeController as AdminIncomeController;
use App\Http\Controllers\Admin\ExpenseController as AdminExpenseController;
use App\Http\Controllers\Admin\InvoiceController as AdminInvoiceController;use App\Http\Controllers\Admin\FinanceOverviewController as AdminFinanceOverviewController;
use App\Http\Controllers\Admin\CrmOverviewController as AdminCrmOverviewController;
use App\Http\Controllers\Admin\MarketingOverviewController as AdminMarketingOverviewController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\BalanceController as AdminBalanceController;
use App\Http\Controllers\Admin\BankAccountController as AdminBankAccountController;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\TestimonialController as AdminTestimonialController;
use App\Http\Controllers\Admin\CaseStudyController as AdminCaseStudyController;
use App\Http\Controllers\Admin\LogoWallController as AdminLogoWallController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/privacy', [LegalController::class, 'privacy'])->name('privacy');
Route::get('/terms', [LegalController::class, 'terms'])->name('terms');
Route::get('/blog', [PublicBlogController::class, 'index'])->name('public.blog.index');
Route::get('/blog/{public_id}/{slug}', [PublicBlogController::class, 'show'])->name('public.blog.show');

// ============== PUBLIC CONTACT FORM (Phase 12.6) ==============
// Public — Google Ads Academy landing page
// Public — Services landing pages (Coming Soon for now)
Route::get('/google-ads-management', [\App\Http\Controllers\Public\ServicesController::class, 'management'])
    ->name('public.services.management');

Route::get('/google-ads-audit', [\App\Http\Controllers\Public\ServicesController::class, 'audit'])
    ->name('public.services.audit');

Route::get('/google-ads-consulting', [\App\Http\Controllers\Public\ServicesController::class, 'consulting'])
    ->name('public.services.consulting');

Route::get('/free-consultation', function () {
    return redirect()->route('public.contact.show', [
        'utm_source'   => 'services_menu',
        'utm_medium'   => 'internal',
        'utm_campaign' => 'free_consultation',
    ], 301);
})->name('public.services.consultation');

// Public — Tools landing pages (Coming Soon for now)
Route::get('/keyword-mixer', function () {
    return view('public.tools.keyword-mixer');
})->name('public.tools.keyword-mixer');

Route::get('/campaign-plan-generator', function () {
    return view('public.tools.campaign-plan-generator');
})->name('public.tools.campaign-plan');

Route::get('/lp-analyzer', function () {
    return view('public.tools.lp-analyzer');
})->name('public.tools.lp-analyzer');

Route::get('/troubleshooter', [\App\Http\Controllers\Public\TroubleshooterController::class, 'index'])
    ->name('public.troubleshooter');

Route::get('/url-builder', function () {
    return view('public.tools.url-builder');
})->name('public.tools.url-builder');

// Public — Academy sub-program landing pages (Coming Soon for now)
Route::get('/google-ads-next-gen', function () {
    return view('public.academy.google-ads-next-gen');
})->name('public.academy.nextgen');

Route::get('/corporate-training', [\App\Http\Controllers\Public\AcademyLandingController::class, 'corporate'])
    ->name('public.academy.corporate');

Route::get('/google-ads-playbook', function () {
    return view('public.academy.google-ads-playbook');
})->name('public.academy.playbook');

Route::get('/google-ads-academy', [\App\Http\Controllers\Public\AcademyLandingController::class, 'index'])
    ->name('public.academy.landing');

Route::get('/contact', [\App\Http\Controllers\PublicContactController::class, 'show'])
    ->name('public.contact.show');
    
Route::get('/proposal/{token}', [\App\Http\Controllers\Public\ProposalPublicController::class, 'show'])
    ->name('public.proposal.show');
Route::get('/proposal/{token}/pdf', [\App\Http\Controllers\Public\ProposalPublicController::class, 'downloadPdf'])
    ->name('public.proposal.pdf');

Route::post('/contact', [\App\Http\Controllers\PublicContactController::class, 'store'])
    ->middleware('throttle:5,60')
    ->name('public.contact.store');

Route::get('/thank-you', [\App\Http\Controllers\PublicContactController::class, 'thankYou'])
    ->name('public.contact.thank-you');

// Authenticated dashboard redirect (Breeze default)
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile (Breeze default)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin section
Route::middleware(['auth', 'role', 'prevent.duplicate.admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::middleware('role:super_admin')->group(function () {
            Route::resource('users', AdminUserController::class)->except(['show']);
            Route::resource('services', AdminServiceController::class)->except(['show']);

            // Settings (Tier 4.5)
            Route::get('settings', [AdminSettingController::class, 'index'])->name('settings.index');
            Route::patch('settings/company', [AdminSettingController::class, 'updateCompany'])->name('settings.update.company');
            Route::patch('settings/invoice', [AdminSettingController::class, 'updateInvoice'])->name('settings.update.invoice');
            Route::patch('settings/tracking', [AdminSettingController::class, 'updateTracking'])->name('settings.update.tracking');

            // Bank Accounts (Tier 4.5 R2 - Banking dynamic)
            Route::post('bank-accounts', [AdminBankAccountController::class, 'store'])->name('bank-accounts.store');
            Route::patch('bank-accounts/{bank_account}', [AdminBankAccountController::class, 'update'])->name('bank-accounts.update');
            Route::delete('bank-accounts/{bank_account}', [AdminBankAccountController::class, 'destroy'])->name('bank-accounts.destroy');

            // Activity Log (Tier 7)
            Route::get('activity-log', [AdminActivityLogController::class, 'index'])->name('activity-log.index');

            // Components (Tier 8) - super_admin only
            Route::resource('testimonials', AdminTestimonialController::class)->except(['show']);
            Route::resource('case-studies', AdminCaseStudyController::class)->except(['show']);
            Route::resource('faqs', AdminFaqController::class)->except(['show']);
            Route::resource('logo-wall', AdminLogoWallController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['logo-wall' => 'logoWallItem']);
            Route::resource('public-services', \App\Http\Controllers\Admin\PublicServiceController::class);
            Route::resource('comparison-rows', \App\Http\Controllers\Admin\ComparisonRowController::class);
            Route::resource('issue-categories', \App\Http\Controllers\Admin\IssueCategoryController::class)->except(['show']);

            // Troubleshooter (Tier 8) - super_admin only - tree-based content
            Route::prefix('troubleshooter')->name('troubleshooter.')->group(function () {
                Route::get('/', [\App\Http\Controllers\Admin\TroubleshooterController::class, 'index'])->name('index');
                Route::post('/', [\App\Http\Controllers\Admin\TroubleshooterController::class, 'store'])->name('store');
                Route::put('/{troubleshooter}', [\App\Http\Controllers\Admin\TroubleshooterController::class, 'update'])->name('update');
                Route::delete('/{troubleshooter}', [\App\Http\Controllers\Admin\TroubleshooterController::class, 'destroy'])->name('destroy');
            });
        });

        // Clients autocomplete search — cross-module endpoint (Invoice, Income, Project)
        // Accessible by all admin roles; controller applies role-aware scoping.
        Route::middleware('role:super_admin,admin,account_manager,advertiser')->group(function () {
            Route::get('clients-search', [AdminClientController::class, 'search'])->name('clients.search');
            Route::get('projects-search', [\App\Http\Controllers\Admin\ProjectController::class, 'search'])->name('projects.search');
        });

        Route::middleware('role:super_admin,admin')->group(function () {
            Route::get('crm/overview', [AdminCrmOverviewController::class, 'index'])->name('crm.overview');
            Route::resource('clients', AdminClientController::class);

            // Client Followups (Phase 12.2 — embedded followup, mirror Lead pattern, flat POST)
            Route::post('clients/{client}/followups', [AdminClientFollowupController::class, 'store'])->name('clients.followups.store');
            Route::post('client-followups/{followup}/update', [AdminClientFollowupController::class, 'update'])->name('clients.followups.update');
            Route::post('client-followups/{followup}/delete', [AdminClientFollowupController::class, 'destroy'])->name('clients.followups.destroy');
            Route::post('client-followups/{followup}/complete', [AdminClientFollowupController::class, 'complete'])->name('clients.followups.complete');

            Route::resource('pricing-tiers', \App\Http\Controllers\Admin\PricingTierController::class)
                ->parameters(['pricing-tiers' => 'pricingTier'])
                ->except(['show']);

            Route::resource('proposal-snippets', \App\Http\Controllers\Admin\ProposalSnippetController::class)
                ->parameters(['proposal-snippets' => 'proposalSnippet'])
                ->except(['show']);
                
            Route::post('proposals/upload-image', [\App\Http\Controllers\Admin\ProposalController::class, 'uploadImage'])
                ->name('proposals.upload-image');
            Route::get('proposals/{proposal}/preview', [\App\Http\Controllers\Admin\ProposalController::class, 'preview'])
                ->name('proposals.preview');
            Route::get('proposals/{proposal}/pdf', [\App\Http\Controllers\Admin\ProposalController::class, 'downloadPdf'])
                ->name('proposals.pdf');
            Route::resource('proposals', \App\Http\Controllers\Admin\ProposalController::class)
                ->except(['show']);

            Route::resource('proposal-templates', \App\Http\Controllers\Admin\ProposalTemplateController::class)
                ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
                ->parameters(['proposal-templates' => 'proposalTemplate']);

            Route::resource('incomes', AdminIncomeController::class)->except(['show']);
            Route::resource('expenses', AdminExpenseController::class)->except(['show']);
            Route::post('expenses/{expense}/confirm-recurring', [AdminExpenseController::class, 'confirmRecurring'])->name('expenses.confirm-recurring');
            Route::post('expenses/{expense}/skip-recurring', [AdminExpenseController::class, 'skipRecurring'])->name('expenses.skip-recurring');
            Route::resource('balances', AdminBalanceController::class)->except(['show']);

            // Invoices (Tier 5)
            Route::resource('invoices', AdminInvoiceController::class);
            Route::patch('invoices/{invoice}/mark-paid', [AdminInvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
            Route::get('invoices/{invoice}/pdf', [AdminInvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
            Route::get('invoices/{invoice}/pdf-preview', [AdminInvoiceController::class, 'previewPdf'])->name('invoices.pdf-preview');
            Route::get('finance/overview', [AdminFinanceOverviewController::class, 'index'])->name('finance.overview');
        });

        // Blog (Tier 6) - super_admin + admin + marketing
        Route::middleware('role:super_admin,admin,marketing')->group(function () {
            Route::get('marketing/overview', [AdminMarketingOverviewController::class, 'index'])->name('marketing.overview');
            Route::resource('blog-categories', AdminBlogCategoryController::class)->only(['index', 'store', 'update']);
            Route::get('leads-search', [AdminLeadController::class, 'search'])->name('leads.search');
            Route::resource('leads', AdminLeadController::class);
            Route::post('leads/{lead}/followups', [AdminLeadFollowupController::class, 'store'])->name('leads.followups.store');
            Route::post('lead-followups/{followup}/update', [AdminLeadFollowupController::class, 'update'])->name('leads.followups.update');
            Route::post('lead-followups/{followup}/delete', [AdminLeadFollowupController::class, 'destroy'])->name('leads.followups.destroy');
            Route::post('lead-followups/{followup}/complete', [AdminLeadFollowupController::class, 'complete'])->name('leads.followups.complete');
            Route::post('leads/{lead}/promote', [AdminLeadController::class, 'promote'])->name('leads.promote');
            Route::resource('blog-posts', AdminBlogPostController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
            Route::middleware('role:super_admin')->group(function () {
                Route::delete('blog-categories/{blog_category}', [AdminBlogCategoryController::class, 'destroy'])->name('blog-categories.destroy');
                Route::delete('blog-posts/{blog_post}', [AdminBlogPostController::class, 'destroy'])->name('blog-posts.destroy');
            });
        });

        // Operations module (Phase 14.3 + 14.4 + 14.5) - super_admin + admin + account_manager + advertiser
        Route::middleware('role:super_admin,admin,account_manager,advertiser')->group(function () {
            // Operations Overview Dashboard (Phase 14.5)
            Route::get('operations', [\App\Http\Controllers\Admin\OperationsController::class, 'overview'])->name('operations.overview');

            // Projects: specific routes (create/store/edit/update) — super_admin + admin + account_manager
            // IMPORTANT: declare specific routes BEFORE wildcard {project} routes to avoid match conflict
            Route::middleware('role:super_admin,admin,account_manager')->group(function () {
                Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class)->only(['create', 'store', 'edit', 'update']);
            });

            // Projects: delete — super_admin only
            Route::middleware('role:super_admin')->group(function () {
                Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class)->only(['destroy']);
            });

            // Projects: view (index/show) — all 4 roles
            Route::resource('projects', \App\Http\Controllers\Admin\ProjectController::class)->only(['index', 'show']);

            // AM-only: read-only list of clients assigned to the logged-in AM
            Route::middleware('role:account_manager')->group(function () {
                Route::get('operations/clients', [\App\Http\Controllers\Admin\OperationsClientController::class, 'index'])
                    ->name('operations.clients.index');
            });

            // Project Reports (Phase 14.4 inline pattern - aligned with Followup)
            Route::post('projects/{project}/reports', [\App\Http\Controllers\Admin\ProjectReportController::class, 'store'])->name('projects.reports.store');
            Route::put('project-reports/{report}', [\App\Http\Controllers\Admin\ProjectReportController::class, 'update'])->name('project-reports.update');
            Route::delete('project-reports/{report}', [\App\Http\Controllers\Admin\ProjectReportController::class, 'destroy'])->name('project-reports.destroy');
            Route::post('project-reports/{report}/review', [\App\Http\Controllers\Admin\ProjectReportController::class, 'review'])->name('project-reports.review');
            Route::post('project-reports/{report}/acknowledge', [\App\Http\Controllers\Admin\ProjectReportController::class, 'acknowledge'])->name('project-reports.acknowledge');
        });

        // Academy module (Phase C) - super_admin + admin
        Route::middleware('role:super_admin,admin')->prefix('academy')->name('academy.')->group(function () {
            // Overview (dashboard)
            Route::get('overview', [\App\Http\Controllers\Admin\AcademyOverviewController::class, 'index'])->name('overview');

            // Custom actions BEFORE resource (so resource doesn't catch them)
            Route::post('members/{member}/resend-setup', [\App\Http\Controllers\Admin\Academy\MemberController::class, 'resendSetup'])->name('members.resend-setup');
            Route::post('members/{member}/regenerate-token', [\App\Http\Controllers\Admin\Academy\MemberController::class, 'regenerateToken'])->name('members.regenerate-token');
            Route::post('members/{member}/toggle-active', [\App\Http\Controllers\Admin\Academy\MemberController::class, 'toggleActive'])->name('members.toggle-active');

            Route::get('members-search', [\App\Http\Controllers\Admin\Academy\MemberController::class, 'search'])
                ->name('members.search');
            Route::resource('members', \App\Http\Controllers\Admin\Academy\MemberController::class);

            // Modules (Phase B)
            Route::resource('modules', \App\Http\Controllers\Admin\Academy\ModuleController::class);

            // Certificate Requests (review inbox)
            Route::get('certificate-requests', [\App\Http\Controllers\Admin\Academy\CertificateRequestController::class, 'index'])
                ->name('certificate-requests.index');
            Route::post('certificate-requests/{certificateRequest}/approve', [\App\Http\Controllers\Admin\Academy\CertificateRequestController::class, 'approve'])
                ->name('certificate-requests.approve');
            Route::post('certificate-requests/{certificateRequest}/reject', [\App\Http\Controllers\Admin\Academy\CertificateRequestController::class, 'reject'])
                ->name('certificate-requests.reject');

            // Certificates (issued)
            Route::get('certificates', [\App\Http\Controllers\Admin\Academy\CertificateController::class, 'index'])
                ->name('certificates.index');
            Route::get('certificates/create', [\App\Http\Controllers\Admin\Academy\CertificateController::class, 'create'])
                ->name('certificates.create');
            Route::post('certificates', [\App\Http\Controllers\Admin\Academy\CertificateController::class, 'store'])
                ->name('certificates.store');
            Route::get('certificates/{certificate}', [\App\Http\Controllers\Admin\Academy\CertificateController::class, 'show'])
                ->name('certificates.show');
            Route::get('certificates/{certificate}/edit', [\App\Http\Controllers\Admin\Academy\CertificateController::class, 'edit'])
                ->name('certificates.edit');
            Route::put('certificates/{certificate}', [\App\Http\Controllers\Admin\Academy\CertificateController::class, 'update'])
                ->name('certificates.update');
            Route::post('certificates/{certificate}/revoke', [\App\Http\Controllers\Admin\Academy\CertificateController::class, 'revoke'])
                ->name('certificates.revoke');
            Route::get('certificates/{certificate}/preview-pdf', [\App\Http\Controllers\Admin\Academy\CertificateController::class, 'previewPdf'])
                ->name('certificates.preview-pdf');
            Route::get('certificates/{certificate}/download-pdf', [\App\Http\Controllers\Admin\Academy\CertificateController::class, 'downloadPdf'])
                ->name('certificates.download-pdf');


            // Materials (nested under modules, inline create + separate edit page)
            Route::post('modules/{module}/materials', [\App\Http\Controllers\Admin\Academy\MaterialController::class, 'store'])->name('modules.materials.store');
            Route::get('modules/{module}/materials/{material}/edit', [\App\Http\Controllers\Admin\Academy\MaterialController::class, 'edit'])->name('modules.materials.edit');
            Route::put('modules/{module}/materials/{material}', [\App\Http\Controllers\Admin\Academy\MaterialController::class, 'update'])->name('modules.materials.update');
            Route::delete('modules/{module}/materials/{material}', [\App\Http\Controllers\Admin\Academy\MaterialController::class, 'destroy'])->name('modules.materials.destroy');
        });
    });


// ====================
// PUBLIC CERTIFICATE VERIFY
// ====================
Route::get('/certificate/verify', [\App\Http\Controllers\Public\CertificateVerifyController::class, 'form'])
    ->name('certificate.verify.form');
Route::get('/certificate/verify/{number}', [\App\Http\Controllers\Public\CertificateVerifyController::class, 'show'])
    ->name('certificate.verify.show');

require __DIR__.'/auth.php';
// ====================
// MEMBER AUTH ROUTES (Academy)
// ====================

Route::middleware('guest:member')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\Member\MemberLoginController::class, 'create'])
        ->name('member.login');
    Route::post('/login', [\App\Http\Controllers\Auth\Member\MemberLoginController::class, 'store'])
        ->name('member.login.store');

    // Forgot password (single system pakai setup_token)
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\Member\MemberForgotPasswordController::class, 'create'])
        ->name('member.password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\Member\MemberForgotPasswordController::class, 'store'])
        ->name('member.password.email');

    // Setup password (token-based, single system for both new enroll + admin reset + forgot password)
    Route::get('/academy/setup-password/{token}', [\App\Http\Controllers\Auth\Member\MemberSetupController::class, 'show'])
        ->name('member.setup');
    Route::post('/academy/setup-password/{token}', [\App\Http\Controllers\Auth\Member\MemberSetupController::class, 'store'])
        ->name('member.setup.store');
});

Route::middleware(['auth:member', 'member.active'])->prefix('academy')->name('academy.')->group(function () {
    // Dashboard
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Academy\LearningController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/upgrade', [\App\Http\Controllers\Academy\LearningController::class, 'upgrade'])
        ->name('upgrade');

    Route::get('/announcements', [\App\Http\Controllers\Academy\LearningController::class, 'announcements'])
        ->name('announcements');

    // Learning routes
    Route::get('/learn/{module:slug}', [\App\Http\Controllers\Academy\LearningController::class, 'showModule'])
        ->name('module.show');
    Route::get('/learn/{module:slug}/{material}', [\App\Http\Controllers\Academy\LearningController::class, 'showMaterial'])
        ->name('material.show');

    // Progress toggle (AJAX)
    Route::post('/progress/{material}/toggle', [\App\Http\Controllers\Academy\LearningController::class, 'toggleProgress'])
        ->name('progress.toggle');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Academy\ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::post('/profile/password', [\App\Http\Controllers\Academy\ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    // Certificates
    Route::get('/certificates', [\App\Http\Controllers\Academy\CertificateController::class, 'index'])
        ->name('certificates.index');
    Route::post('/certificates/request', [\App\Http\Controllers\Academy\CertificateController::class, 'requestStore'])
        ->name('certificates.request.store');
    Route::get('/certificates/{certificate}/download', [\App\Http\Controllers\Academy\CertificateController::class, 'download'])
        ->name('certificates.download');

    Route::post('/logout', [\App\Http\Controllers\Auth\Member\MemberLoginController::class, 'destroy'])
        ->name('logout');
});
