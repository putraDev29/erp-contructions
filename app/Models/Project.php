<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_code',
        'name',
        'type',
        'customer_id',
        'location',
        'coordinates',
        'project_manager_id',
        'site_manager_id',
        'tender_date',
        'start_date',
        'deadline_date',
        'actual_end_date',
        'contract_amount',
        'approved_budget_amount',
        'target_margin_percent',
        'contract_method',
        'payment_system',
        'retention_percent',
        'retention_amount',
        'down_payment_percent',
        'down_payment_amount',
        'status',
        'physical_progress_percent',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'tender_date' => 'date',
            'start_date' => 'date',
            'deadline_date' => 'date',
            'actual_end_date' => 'date',
            'contract_amount' => 'decimal:2',
            'approved_budget_amount' => 'decimal:2',
            'target_margin_percent' => 'decimal:2',
            'retention_percent' => 'decimal:2',
            'retention_amount' => 'decimal:2',
            'down_payment_percent' => 'decimal:2',
            'down_payment_amount' => 'decimal:2',
            'physical_progress_percent' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function projectManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function siteManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'site_manager_id');
    }

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_project_assignments');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(ProjectContract::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(ProjectTask::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    public function activeBudget()
    {
        return $this->hasOne(Budget::class)->where('status', 'approved')->latest('version');
    }

    public function variationOrders(): HasMany
    {
        return $this->hasMany(VariationOrder::class);
    }

    public function materialRequests(): HasMany
    {
        return $this->hasMany(MaterialRequest::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function subcontractorContracts(): HasMany
    {
        return $this->hasMany(SubcontractorContract::class);
    }

    public function customerInvoices(): HasMany
    {
        return $this->hasMany(CustomerInvoice::class);
    }

    public function cashTransactions(): HasMany
    {
        return $this->hasMany(CashTransaction::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    // Dynamic calculations
    public function getActiveContractAmountAttribute(): float
    {
        $approvedVO = (float) $this->variationOrders()->where('status', 'approved')->sum('contract_amount_change');
        return (float) $this->contract_amount + $approvedVO;
    }

    public function getActiveBudgetAmountAttribute(): float
    {
        $approvedVO = (float) $this->variationOrders()->where('status', 'approved')->sum('budget_amount_change');
        return (float) $this->approved_budget_amount + $approvedVO;
    }

    public function getTotalInvoicedAttribute(): float
    {
        return (float) $this->customerInvoices()
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->sum('net_amount');
    }

    public function getTotalPaidByCustomerAttribute(): float
    {
        return (float) $this->customerInvoices()
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->sum('paid_amount');
    }

    public function getOutstandingReceivableAttribute(): float
    {
        return (float) $this->customerInvoices()
            ->whereIn('status', ['sent', 'partially_paid', 'overdue'])
            ->sum('remaining_amount');
    }

    public function getActualCostAttribute(): float
    {
        // Actual cost calculated strictly from posted journal entries where COA type is direct_expense
        return (float) JournalLine::whereHas('journalEntry', function ($q) {
            $q->where('project_id', $this->id)->where('status', 'posted');
        })->whereHas('coa', function ($q) {
            $q->where('type', 'direct_expense');
        })->sum('debit');
    }

    public function getRecognizedRevenueAttribute(): float
    {
        // Revenue recognized from posted journal entries where COA type is revenue
        return (float) JournalLine::whereHas('journalEntry', function ($q) {
            $q->where('project_id', $this->id)->where('status', 'posted');
        })->whereHas('coa', function ($q) {
            $q->where('type', 'revenue');
        })->sum('credit');
    }

    public function getGrossProfitAttribute(): float
    {
        return $this->recognized_revenue - $this->actual_cost;
    }

    public function getProfitMarginPercentAttribute(): float
    {
        if ($this->recognized_revenue <= 0) {
            return 0.0;
        }

        return round(($this->gross_profit / $this->recognized_revenue) * 100, 2);
    }

    public function getBudgetAbsorptionPercentAttribute(): float
    {
        $budget = $this->active_budget_amount;
        if ($budget <= 0) {
            return 0.0;
        }

        return round(($this->actual_cost / $budget) * 100, 2);
    }
}
