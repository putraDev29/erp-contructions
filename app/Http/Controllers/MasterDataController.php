<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\CompanySetting;
use App\Models\ActivityLog;

class MasterDataController extends Controller
{
    // ==========================================
    // MASTER MATERIAL
    // ==========================================
    public function materials(Request $request)
    {
        $query = Material::with(['category', 'unit']);

        if ($catId = $request->input('category_id')) {
            $query->where('material_category_id', $catId);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $materials = $query->orderBy('name')->paginate(20)->withQueryString();
        $categories = MaterialCategory::orderBy('name')->get();
        $units = Unit::orderBy('name')->get();

        return view('master.materials', compact('materials', 'categories', 'units'));
    }

    public function storeMaterial(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:materials,code'],
            'name' => ['required', 'string', 'max:150'],
            'material_category_id' => ['required', 'exists:material_categories,id'],
            'unit_id' => ['required', 'exists:units,id'],
            'specification' => ['nullable', 'string'],
            'last_purchase_price' => ['required', 'numeric', 'min:0'],
            'reference_price' => ['nullable', 'numeric', 'min:0'],
            'minimum_stock' => ['required', 'numeric', 'min:0'],
        ]);

        $material = Material::create(array_merge($validated, [
            'reference_price' => $validated['reference_price'] ?? $validated['last_purchase_price'],
            'is_active' => true,
        ]));

        ActivityLog::record(
            'created',
            'Material',
            $material->id,
            "Menambah master material {$material->code} - {$material->name}"
        );

        return back()->with('success', "Material {$material->name} berhasil ditambahkan.");
    }

    // ==========================================
    // PELANGGAN & SUPPLIER (PARTNERS)
    // ==========================================
    public function partners(Request $request)
    {
        $customers = Customer::orderBy('name')->paginate(15, ['*'], 'customers_page');
        $suppliers = Supplier::orderBy('name')->paginate(15, ['*'], 'suppliers_page');

        return view('master.partners', compact('customers', 'suppliers'));
    }

    public function storeCustomer(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:customers,code'],
            'name' => ['required', 'string', 'max:150'],
            'customer_type' => ['required', 'in:corporate,individual,government'],
            'contact_person' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string'],
            'npwp' => ['nullable', 'string', 'max:50'],
        ]);

        $cust = Customer::create(array_merge($validated, ['is_active' => true]));

        ActivityLog::record(
            'created',
            'Customer',
            $cust->id,
            "Mendaftarkan pelanggan baru {$cust->name}"
        );

        return back()->with('success', "Pelanggan {$cust->name} berhasil didaftarkan.");
    }

    public function storeSupplier(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:suppliers,code'],
            'name' => ['required', 'string', 'max:150'],
            'contact_person' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['nullable', 'string'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'payment_terms' => ['required', 'string', 'max:100'],
        ]);

        $sup = Supplier::create(array_merge($validated, ['is_active' => true]));

        ActivityLog::record(
            'created',
            'Supplier',
            $sup->id,
            "Mendaftarkan vendor/supplier baru {$sup->name}"
        );

        return back()->with('success', "Supplier {$sup->name} berhasil didaftarkan.");
    }

    // ==========================================
    // PENGATURAN PERUSAHAAN (SETTINGS)
    // ==========================================
    public function companySettings()
    {
        $company = CompanySetting::instance();
        return view('master.company_settings', compact('company'));
    }

    public function updateCompanySettings(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:100'],
            'address' => ['required', 'string'],
            'npwp' => ['nullable', 'string', 'max:50'],
            'document_footer_text' => ['nullable', 'string'],
            'default_tax_percent' => ['required', 'numeric', 'min:0', 'max:50'],
            'default_retention_percent' => ['required', 'numeric', 'min:0', 'max:20'],
        ]);

        $company = CompanySetting::instance();
        $company->update($validated);

        ActivityLog::record(
            'updated',
            'CompanySetting',
            $company->id,
            "Memperbarui profil dan konfigurasi perusahaan ERP"
        );

        return back()->with('success', "Konfigurasi perusahaan berhasil disimpan.");
    }
}
