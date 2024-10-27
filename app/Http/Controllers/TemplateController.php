<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TemplateService;

class TemplateController extends Controller
{
    protected $templateService;

    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'doc_number' => 'required|string|unique:master_dokumens,doc_number',
            'doc_title' => 'required|string',
            'doc_desc' => 'required|string',
            'doc_date' => 'required|date',
            'doc_rev' => 'required|string',
            'file' => 'required|file|mimes:doc,docx',
        ]);

        return $this->templateService->storeTemplate($request->all(), $request->file('file'), $request->user());
    }
}
