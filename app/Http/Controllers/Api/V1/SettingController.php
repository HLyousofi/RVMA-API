<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\StoreSettingsRequest;
use App\Http\Requests\V1\UpdateSettingsRequest;
use App\Http\Resources\V1\SettingsResource;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::firstOrCreate();
        return new SettingsResource($settings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSettingsRequest $request)
    {
        \Log::info('Fichiers détectés', $request->all());

        $settings = Setting::firstOrCreate();
        $this->handleLogoUpload($request, $settings);
        $settings->update($request->validated());
        return new SettingsResource($settings);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingsRequest $request, Setting $setting)
    {
    

        $this->handleLogoUpload($request, $setting);
        $setting->update($request->validated());
        return new SettingsResource($setting);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

 
    private function handleLogoUpload(Request $request, Setting $setting)
    {
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $setting->logo_path = $logoPath;
            $setting->save();
        } else {
            \Log::info("Aucun fichier détecté dans la requête"); // Pour débogage
        }
    }
}
