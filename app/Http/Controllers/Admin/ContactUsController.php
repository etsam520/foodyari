<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ContactUsController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactUs::with(['customer', 'repliedBy']);
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }
        
        $contacts = $query->orderBy('created_at', 'desc')->paginate(15);
        $statuses = ContactUs::getStatuses();
        
        // Get statistics
        $stats = [
            'total' => ContactUs::count(),
            'pending' => ContactUs::where('status', ContactUs::STATUS_PENDING)->count(),
            'in_progress' => ContactUs::where('status', ContactUs::STATUS_IN_PROGRESS)->count(),
            'resolved' => ContactUs::where('status', ContactUs::STATUS_RESOLVED)->count(),
            'closed' => ContactUs::where('status', ContactUs::STATUS_CLOSED)->count(),
        ];
        
        return view('admin-views.contact-us.index', compact('contacts', 'statuses', 'stats'));
    }
    
    public function show($id)
    {
        $contact = ContactUs::with(['customer', 'repliedBy'])->findOrFail($id);
        $statuses = ContactUs::getStatuses();
        
        return view('admin-views.contact-us.show', compact('contact', 'statuses'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', array_keys(ContactUs::getStatuses()))
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $contact = ContactUs::findOrFail($id);
        $contact->status = $request->status;
        $contact->save();
        
        return redirect()->back()->with('success', 'Status updated successfully!');
    }
    
    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'admin_reply' => 'required|string|max:5000',
            'status' => 'required|in:' . implode(',', array_keys(ContactUs::getStatuses()))
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        $contact = ContactUs::findOrFail($id);
        $contact->admin_reply = $request->admin_reply;
        $contact->status = $request->status;
        $contact->replied_by = Auth::guard('admin')->id();
        $contact->replied_at = now();
        $contact->save();
        
        // Send reply email to customer
        try {
            $this->sendReplyEmail($contact);
            $emailMessage = ' Reply email sent successfully.';
        } catch (\Exception $e) {
            Log::error('Failed to send reply email: ' . $e->getMessage());
            $emailMessage = ' Reply saved, but email could not be sent.';
        }
        
        return redirect()->back()->with('success', 'Reply sent successfully!' . $emailMessage);
    }
    
    public function delete($id)
    {
        $contact = ContactUs::findOrFail($id);
        $contact->delete();
        
        return redirect()->route('admin.contact-us.index')->with('success', 'Contact message deleted successfully!');
    }
    
    public function bulkAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:delete,update_status',
            'selected_items' => 'required|array|min:1',
            'status' => 'required_if:action,update_status|in:' . implode(',', array_keys(ContactUs::getStatuses()))
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $selectedIds = $request->selected_items;
        
        if ($request->action === 'delete') {
            ContactUs::whereIn('id', $selectedIds)->delete();
            $message = count($selectedIds) . ' contact messages deleted successfully!';
        } elseif ($request->action === 'update_status') {
            ContactUs::whereIn('id', $selectedIds)->update(['status' => $request->status]);
            $message = count($selectedIds) . ' contact messages status updated successfully!';
        }
        
        return redirect()->back()->with('success', $message);
    }
    
    private function sendReplyEmail($contact)
    {
        $data = [
            'contact' => $contact,
            'customer_name' => $contact->name,
            'original_message' => $contact->message,
            'admin_reply' => $contact->admin_reply,
            'replied_by' => $contact->repliedBy->name ?? 'Customer Support',
            'business_name' => \App\Models\BusinessSetting::where('key', 'business_name')->first()->value ?? 'FoodYari'
        ];
        
        Mail::send('emails.contact-us-reply', $data, function ($message) use ($contact) {
            $message->to($contact->email)
                    ->subject('Re: ' . $contact->subject . ' - Response from ' . (\App\Models\BusinessSetting::where('key', 'business_name')->first()->value ?? 'FoodYari'));
        });
    }
    
    public function export(Request $request)
    {
        $query = ContactUs::with(['customer', 'repliedBy']);
        
        // Apply same filters as index
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->date_from));
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->date_to));
        }
        
        $contacts = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'contact_us_messages_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($contacts) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID', 'Name', 'Email', 'Phone', 'Subject', 'Message', 
                'Status', 'Admin Reply', 'Replied By', 'Customer ID', 
                'Submitted At', 'Replied At'
            ]);
            
            // CSV data
            foreach ($contacts as $contact) {
                fputcsv($file, [
                    $contact->id,
                    $contact->name,
                    $contact->email,
                    $contact->phone,
                    $contact->subject,
                    $contact->message,
                    $contact->status_label,
                    $contact->admin_reply,
                    $contact->repliedBy->name ?? '',
                    $contact->customer_id,
                    $contact->created_at->format('Y-m-d H:i:s'),
                    $contact->replied_at ? $contact->replied_at->format('Y-m-d H:i:s') : ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
