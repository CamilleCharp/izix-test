<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\TenantDestroyRequest;
use App\Http\Requests\TenantStoreRequest;
use App\Http\Requests\TenantUpdateRequest;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::all()->map->only(['uuid', 'name'])->toArray();

        return response()->json(['tenants' => $tenants]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TenantStoreRequest $request)
    {
        Tenant::create([
            'name' => $request->name,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        return response()->json(['tenant' => $tenant]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TenantUpdateRequest $request, Tenant $tenant)
    {
        $tenant->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Tenant updated successfully.', 'tenant' => $tenant]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        if(!auth()->user()->hasPermissionTo(Permissions::DELETE_TENANT)) {
            return response()->json(['message' => 'You do not have permission to delete a tenant.'], 403);
        }
        $name = $tenant->name;
        $tenant->delete();

        return response()->json(['message' => "Tenant '{$name}' deleted successfully."]);
    }
}
