<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Services\TemplateService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TemplateController extends Controller
{
    protected $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    public function index()
    {
        return $this->templateService->getApprovedTemplate();
    }

    public function getPendingTemplates()
    {
        return $this->templateService->getPendingTemplates();
    }

    public function storeTemplate(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'doc_number' => 'required|string|unique:master_dokumen,doc_number',
                'doc_desc' => 'required|string',
                'doc_rev' => 'required|string',
                'file' => 'required|file|mimes:doc,docx',
            ]);
            // get file from request
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            
            // save file to storage
            $filePath = $file->storeAs('templates', $originalName, 'public');
            $fullPath = storage_path("app/public/{$filePath}");
            $fullPath = str_replace('\\', '/', $fullPath);

            //save document info to database
            $docData = $request->only(['doc_number', 'doc_desc', 'doc_rev']);
            $document = $this->templateService->saveDocumentInfo($docData, $originalName, $fullPath);

            $placeholders = $this->templateService->extractPlaceholders($fullPath);

            $documentId = $document->id;
            $this->templateService->savePlaceholdersToDatabase($documentId, $placeholders);

            DB::commit();

            return ResponseHelper::successResponse('Template Saved Successfully!', [
                'document' => $document,
                'placeholders' => $placeholders,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ResponseHelper::errorResponse('Failed Save Data!', ['error' => $th->getMessage()]);
        }

    }

    public function approveTemplate($id) // add $request in parameter to get user
    {
        return $this->templateService->approveTemplate($id);
    }

    public function deleteTemplate($id)
    {
        return $this->templateService->deleteTemplate($id);
    }

    public function restoreTemplate($id)
    {
        return $this->templateService->restoreTemplate($id);
    }

    public function getSoftDeletedTemplates()
    {
        return $this->templateService->getSoftDeletedTemplates();
    }

}
