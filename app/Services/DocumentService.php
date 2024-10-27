<?php
namespace App\Services;

use App\Models\Dokumen;
use App\Models\MasterDokumen;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ResponseHelper;

class DocumentService
{
    public function generateDocument($data, $id)
    {
        $template = MasterDokumen::findOrFail($id);

        // Process the template file with PhpWord
        $templateProcessor = new TemplateProcessor(storage_path('app/' . $template->file_path));

        // Change placeholder from template with data
        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
        }

        $outputPath = 'generated_documents/' . $template->doc_number . '_v' . $template->doc_rev . '.docx';
        $templateProcessor->saveAs(storage_path('app/' . $outputPath));

        return response()->download(storage_path('app/' . $outputPath));
    }
}

?>