<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Diagnostic\Request as DiagnosticRequest;
use App\Models\Diagnostic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiDiagnosticController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // $data = Diagnostic::paginate($request->length ?? 10);
        $data = Diagnostic::get();
        return $this->ok("success", $data);
    }

    public function store(DiagnosticRequest $request): JsonResponse
    {
        $diagnostic = Diagnostic::create($request->all());
        return $this->ok("success", $diagnostic);
    }

    public function show(Diagnostic $diagnostic): JsonResponse
    {
        return $this->ok("success", $diagnostic);
    }

    public function update(DiagnosticRequest $request, Diagnostic $diagnostic): JsonResponse
    {
        $diagnostic->update($request->all());
        return $this->ok("success", $diagnostic);
    }


    public function destroy(Diagnostic $diagnostic): JsonResponse
    {
        $diagnostic->delete();
        return $this->ok("success", $diagnostic);
    }
}
