<?php

namespace App\Http\Controllers\API\patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalReport;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicalReportController extends Controller
{
    public function index(Request $request)
    {
        $patient = $request->user()->patient;
        $reports = $patient->medicalReports()->latest()->get();
        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $patient = $request->user()->patient;

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'report' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:2048',
            'description' => 'nullable|string',
            'report_date' => 'required|date'
        ]);

        $file = $request->file('report');
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('medical_reports', $filename, 'public');
        $data['report'] = 'storage/' . $path;
       
        $report = $patient->medicalReports()->create([
            'title' => $request->title,
            'file_path' => $path,
            'description' => $request->description,
            'report_date' => $request->report_date
        ]);

        return response()->json([
            'message' => 'Report uploaded successfully',
            'report' => $report
        ], 201);
    }

    public function destroy(MedicalReport $report)
    {
        if ($report->patient_id !== auth()->user()->patient->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Storage::disk('public')->delete($report->file_path);
        $report->delete();
        return response()->json(['message' => 'Report deleted successfully']);
    }


public function getPatientReports(Patient $patient)
{
    $doctor = auth()->user()->doctor;
    
    if (!$doctor->appointments()->where('patient_id', $patient->id)->exists()) {
        return response()->json(['message' => 'Not authorized to view these reports'], 403);
    }

    $reports = $patient->medicalReports()->latest()->get();
    return response()->json($reports);
}

public function getReport(MedicalReport $report)
{
    $doctor = auth()->user()->doctor;
    
    if (!$doctor->appointments()->where('patient_id', $report->patient_id)->exists()) {
        return response()->json(['message' => 'Not authorized to view this report'], 403);
    }

    if (!Storage::disk('public')->exists($report->file_path)) {
        return response()->json(['message' => 'File not found'], 404);
    }

    return response()->file(storage_path('app/public/' . $report->file_path));
}
}
