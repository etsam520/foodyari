<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents
     */
    public function index()
    {
        $documents = Document::latest()->get();
        return view('admin-views.doc._list', compact('documents'));
    }
    
    public function kyc(){
        return view('admin-views.doc.kyc');
    }

    public function kycDocumentTable(){
        $documents = Document::latest()->get();
        return view('admin-views.doc._list', compact('documents'));
    }


    public function create(){
        return view('admin-views.doc.create');
    }

    public function show($id)
    {
        $document = Document::findOrFail($id);
        return view('admin-views.doc.show', compact('document'));
    }

    public function edit($id)
    {
        $document = Document::findOrFail($id);
        return view('admin-views.doc._edit', compact('document'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|in:restaurant_kyc,deliveryman_kyc,user_kyc',
            'name' => 'required|string|max:255',
            'is_required' => 'nullable|boolean',
            'is_text' => 'nullable|boolean',
            'is_text_required' => 'nullable|boolean',
            'is_media' => 'nullable|boolean',
            'is_media_required' => 'nullable|boolean',
            'has_expiry_date' => 'nullable|boolean',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $document = Document::findOrFail($id);
            
            $document->update([
                'type' => $request->type,
                'name' => $request->name,
                'is_required' => $request->is_required ?? 0,
                'is_text' => $request->is_text ?? 0,
                'is_text_required' => $request->is_text_required ?? 0,
                'is_media' => $request->is_media ?? 0,
                'is_media_required' => $request->is_media_required ?? 0,
                'has_expiry_date' => $request->has_expiry_date ?? 0,
                'status' => $request->status,
            ]);

            return redirect()->route('admin.doc.index')->with('success', 'Document updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update document: ' . $e->getMessage())->withInput();
        }
    }
    public function store(Request $request)
    {

        $request->validate([
            'type' => 'required|in:restaurant_kyc,deliveryman_kyc,user_kyc',
            'name' => 'required|string|max:255',
            'is_required' => 'nullable|boolean',
            'is_text' => 'nullable|boolean',
            'is_text_required' => 'nullable|boolean',
            'is_media' => 'nullable|boolean',
            'is_media_required' => 'nullable|boolean',
            'has_expiry_date' => 'nullable|boolean',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            Document::create([
                'type' => $request->type,
                'name' => $request->name,
                'is_required' => $request->is_required ?? 0,
                'is_text' => $request->is_text ?? 0,
                'is_text_required' => $request->is_text_required ?? 0,
                'is_media' => $request->is_media ?? 0,
                'is_media_required' => $request->is_media_required ?? 0,
                'has_expiry_date' => $request->has_expiry_date ?? 0,
                'status' => $request->status,
            ]);
            
            return redirect()->route('admin.doc.index')->with('success', 'Document created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create document: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $document = Document::findOrFail($id);
            
            // Check if document is being used by any document details
            if ($document->documentDetails()->exists()) {
                return redirect()->route('admin.doc.index')
                    ->with('error', 'Cannot delete document as it is being used in KYC records.');
            }
            
            $document->delete();
            
            return redirect()->route('admin.doc.index')
                ->with('success', 'Document deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.doc.index')
                ->with('error', 'Failed to delete document: ' . $e->getMessage());
        }
    }
}
