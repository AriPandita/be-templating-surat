<?php
namespace App\Services;

use App\Models\LogActivity;
use App\Models\MasterDokumen;
use App\Helpers\ResponseHelper;
use PhpOffice\PhpWord\IOFactory;
use App\Models\DetailMasterDokumen;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class TemplateService
{
    // saving document info to database
    public function saveDocumentInfo($docData, $originalName, $fullPath) {
        return MasterDokumen::create([
            'doc_number' => $docData['doc_number'],
            'doc_title' => $originalName,
            'doc_desc' => $docData['doc_desc'],
            'doc_date' => now(),
            'doc_rev' => $docData['doc_rev'],
            'file_path' => $fullPath,
            'is_approved' => false
        ]);
    }

    // extract placeholders from template
    public function extractPlaceholders($filePath)
    {
        $templateProcessor = new TemplateProcessor($filePath);
        $placeholders = $templateProcessor->getVariables();
        $parsedPlaceholders = [];

        foreach ($placeholders as $placeholder) {
            if (preg_match('/\${(.*?)#(.*?)}/', $placeholder, $matches)) {
                $parsedPlaceholders[] = [
                    'type' => $matches[1],        // 'DATE', 'STRING', etc.
                    'placeholder' => $matches[2]  // 'TANGGAL_PERMOHONAN', etc.
                ];
            }
        }

        return $parsedPlaceholders;
    }

    public function savePlaceholdersToDatabase($documentId, $placeholders) {
        foreach ($placeholders as $placeholder) {
            DetailMasterDokumen::create([
                'doc_id' => $documentId,
                'placeholder' => $placeholder['placeholder'],
                'type' => $placeholder['type'],
                'desc' => 'Auto-generated placeholder'
            ]);
        }
    }

    // public function storeTemplate($data, $file)
    // {
    //     $originalName = $file->getClientOriginalName();
    //     $filePath = $file->storeAs('templates', $originalName, 'public');
    //     $fullPath = storage_path("app/public/{$filePath}");
    //     $fullPath = str_replace('\\', '/', $fullPath);

    //     if (!file_exists($fullPath)) {
    //         return ResponseHelper::errorResponse('File not found!', null);
    //     }

    //     // reading placeholder using PHPWord
    //     // $placeholders = $this->parsePlaceholders($fullPath);
    //     $templateProcessor = new TemplateProcessor($fullPath);
    //     $placeholders = $this->extractPlaceholders($templateProcessor);

    //     // save template to database 
    //     $template = MasterDokumen::create([
    //         'doc_number' => $data['doc_number'],
    //         'doc_title' => $file->getClientOriginalName(),
    //         'doc_desc' => $data['doc_desc'],
    //         'doc_date' => now(),
    //         'doc_rev' => $data['doc_rev'],
    //         'file_path' => $fullPath,
    //         'is_approved' => false
    //     ]);

    //     // save placeholders to detail_master_dokumen
    //     foreach ($placeholders as $placeholder) {
    //         DetailMasterDokumen::create([
    //             'doc_id' => $template->id,
    //             'placeholder' => $placeholder['key'],
    //             'type' => $placeholder['type'],
    //             'desc' => 'Auto-generated placeholder'
    //         ]);
    //     }

    //     return ResponseHelper::successResponse('Template saved successfully', $template);
    // }

        // // determine placeholder type
    // private function determinePlaceholderType($placeholder)
    // {
    //     if (str_contains($placeholder, 'DATE')) {
    //         return 'date';
    //     } elseif (str_contains($placeholder, 'TEXT')) {
    //         return 'text';
    //     } elseif (str_contains($placeholder, 'STRING')) {
    //         return 'string';
    //     }
    //     return 'unknown';
    // }

    // approve template by id
    public function approveTemplate($id)
    {
        $template = MasterDokumen::find($id);

        if (!$template) {
            return ResponseHelper::errorResponse('Template not found', null, 404);
        }

        $template->update([
            'is_approved' => true,
        ]);

        return ResponseHelper::successResponse('Template approved successfully', $template);
    }

     // get all approved templates
     public function getApprovedTemplate()
     {
         $approvedTemplates = MasterDokumen::where('is_approved', true)->get();

         if ($approvedTemplates->isEmpty()) {
             $approvedTemplates = [
                    'data' => null
             ];
         }

         return ResponseHelper::successResponse('All templates retrieved successfully', $approvedTemplates);
     }

    // get all pending templates
    public function getPendingTemplates()
    {
        $pendingTemplates = MasterDokumen::where('is_approved', false)->get();

        if ($pendingTemplates->isEmpty()) {
            $pendingTemplates = [
                'data' => null
            ];
        }

        return ResponseHelper::successResponse('Pending templates retrieved successfully', $pendingTemplates);
    }

    // delete template 
    public function deleteTemplate($id)
    {
        $template = MasterDokumen::find($id);

        if (!$template) {
            return ResponseHelper::errorResponse('Template not found', null);
        }

        Storage::delete($template->file_path);
        $template->delete();

        return ResponseHelper::successResponse('Template deleted successfully', null);
    }

    // restore template that has been soft deleted
    public function restoreTemplate($id)
    {
        $template = MasterDokumen::withTrashed()->find($id);

        if (!$template) {
            return ResponseHelper::errorResponse('Template not found', null);
        }

        $template->restore();

        return ResponseHelper::successResponse('Template restored successfully', $template);
    }

    // get all soft deleted templates 
    public function getSoftDeletedTemplates()
    {
        $templates = MasterDokumen::onlyTrashed()->get();

        if ($templates->isEmpty()) {
            $templates = [
                'data' => null
            ];
        }
        
        return ResponseHelper::successResponse('Soft deleted templates retrieved successfully', $templates);
    }

}

?>