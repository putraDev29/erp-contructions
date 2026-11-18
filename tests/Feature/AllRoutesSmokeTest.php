<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AllRoutesSmokeTest extends TestCase
{
    public function test_all_navigation_menus_render_successfully(): void
    {
        $user = User::first();
        $this->assertNotNull($user, 'Demo user must exist in database');

        $routes = [
            '/dashboard',
            '/projects',
            '/budgets',
            '/variation-orders',
            '/material-requests',
            '/purchase-orders',
            '/goods-receipts',
            '/supplier-invoices',
            '/inventory',
            '/employees',
            '/attendances',
            '/advances',
            '/payrolls',
            '/subcontractors',
            '/subcontractors/contracts',
            '/equipment',
            '/customer-invoices',
            '/finance/cash-bank',
            '/accounting/coa',
            '/accounting/journals',
            '/reports/cost-control',
            '/reports/project-profit-loss',
            '/reports/financial',
            '/reports/cash-flow',
            '/reports/aging',
            '/materials',
            '/partners',
            '/settings/company',
            '/users',
            '/audit-logs',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($user)->get($route);
            if ($response->status() >= 400 && isset($response->exception) && $response->exception) {
                dump("Error on {$route}: " . $response->exception->getMessage() . " at " . $response->exception->getFile() . ":" . $response->exception->getLine());
            }
            $this->assertTrue(
                in_array($response->status(), [200, 302]),
                "Route {$route} failed with HTTP status: " . $response->status()
            );
        }
    }
}
