<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DocumentService;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function generateDocument(Request $request, $id)
    {
        $request->validate([
            // Validasi data sesuai placeholder dalam template
        ]);

        return $this->documentService->generateDocument($request->all(), $id);
    }
}
