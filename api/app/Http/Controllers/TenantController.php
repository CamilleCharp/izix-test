<?php

namespace App\Http\Controllers;

use App\Enums\Permissions;
use App\Http\Requests\TenantDestroyRequest;
use App\Http\Requests\TenantStoreRequest;
use App\Http\Requests\TenantUpdateRequest;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TenantController extends Controller
{
    /**
     * Display a listing of the tenants.
     * 
     * @return JsonResponse All the tenants infos.
     */
    public function index(): JsonResponse
    {
        $tenants = Tenant::all()->map->only(['uuid', 'name'])->toArray();

        return response()->json(['tenants' => $tenants]);
    }

    /**
     * Store a newly created tenant in storage.
     * 
     * @param TenantStoreRequest $request The store request and its input, validated beforehand
     * @see project://app/Http/Requests/TenantStoreRequest.php
     * 
     * @return JsonResponse The new tenant informations
     */
    public function store(TenantStoreRequest $request): JsonResponse
    {
        $tenant = Tenant::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => "Tenant {$tenant->name} created",
            'tenant' => $tenant
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified tenant.
     * 
     * @param Tenant $tenant The tenant object, found from the route with its uuid.
     * 
     * @return JsonResponse The tenant infos
     */
    public function show(Tenant $tenant): JsonResponse
    {
        return response()->json(['tenant' => $tenant]);
    }

    /**
     * Update the specified tenant in storage.
     * 
     * @param TenantUpdateRequest $request The update request and its input, validated beforehand.
     * @see project://app/Http/Requests/TenantUpdateRequest.php
     * @param Tenant $tenant The tenant object, found from the route with its uuid.
     * 
     * @return JsonResponse The updated tenant infos.
     */
    public function update(TenantUpdateRequest $request, Tenant $tenant): JsonResponse
    {
        $tenant->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Tenant updated successfully.', 'tenant' => $tenant]);
    }

    /**
     * Remove the specified tenants from storage.
     * 
     * @param TenantDestroyRequest $request The destroy request, mostly used for authorization purposes.
     * @see project://app/Http/Requests/TenantDestroyRequest.php
     * @param Tenant $tenant The tenant object, found from the route with its uuid.
     * 
     * @return JsonResponse
     */
    public function destroy(TenantDestroyRequest $request, Tenant $tenant): JsonResponse
    {
        $name = $tenant->name;
        $tenant->delete();

        return response()->json(['message' => "Tenant '{$name}' deleted successfully."]);
    }
}
