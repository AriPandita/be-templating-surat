<?php
namespace App\Services;

use App\Models\MasterDokumen;
use App\Helpers\ResponseHelper;
use App\Models\LogActivity;
use Illuminate\Support\Facades\Storage;

class TemplateService
{
    public function storeTemplate($data, $file, $user)
    {
        $filePath = $file->store('templates');

        $template = MasterDokumen::create([
            'doc_number' => $data['doc_number'],
            'doc_title' => $data['doc_title'],
            'doc_desc' => $data['doc_desc'],
            'doc_date' => $data['doc_date'],
            'doc_rev' => $data['doc_rev'],
        ]);

        LogActivity::create([
            'karyawan_id' => $user->id, // Reference to the user who created the template
            'aksi' => 'Created template: ' . $template->doc_number,
            'ip_address' => request()->ip(),
        ]);

        return ResponseHelper::successResponse('Template created successfully', $template);
    }
}

?>