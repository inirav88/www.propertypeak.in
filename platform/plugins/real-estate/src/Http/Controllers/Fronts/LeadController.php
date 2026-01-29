<?php

namespace Botble\RealEstate\Http\Controllers\Fronts;

use Botble\Base\Http\Controllers\BaseController;
use Botble\RealEstate\Models\Consult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LeadController extends BaseController
{
    public function index(Request $request)
    {
        $account = auth('account')->user();

        // Get leads for properties/projects owned by this account
        $leads = Consult::query()
            ->where(function ($q) use ($account) {
                $q->whereHas('property', function ($query) use ($account) {
                    $query->where('author_id', $account->id);
                })
                    ->orWhereHas('project', function ($query) use ($account) {
                        $query->where('author_id', $account->id);
                    });
            })
            ->with(['property', 'project'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('plugins/real-estate::themes.dashboard.leads.index', compact('leads'));
    }

    public function show($id)
    {
        $lead = Consult::with(['property', 'project'])->findOrFail($id);

        // Check if user owns the property/project
        $account = auth('account')->user();
        if ($lead->property && $lead->property->author_id !== $account->id) {
            abort(403);
        }
        if ($lead->project && $lead->project->author_id !== $account->id) {
            abort(403);
        }

        return view('plugins/real-estate::themes.dashboard.leads.show', compact('lead'));
    }

    public function updateStatus(Request $request, $id)
    {
        $lead = Consult::findOrFail($id);

        $lead->update([
            'status' => $request->status,
            'contacted_at' => $request->status !== 'new' ? now() : null,
        ]);

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function addNote(Request $request, $id)
    {
        $lead = Consult::findOrFail($id);

        $notes = $lead->notes ? json_decode($lead->notes, true) : [];
        $notes[] = [
            'text' => $request->note,
            'created_at' => now()->toDateTimeString(),
            'created_by' => auth('account')->user()->name,
        ];

        $lead->update(['notes' => json_encode($notes)]);

        return response()->json(['success' => true, 'message' => 'Note added successfully']);
    }

    public function export()
    {
        $account = auth('account')->user();

        // Check if user has premium package with export feature
        if (!$account->hasPackageFeature('export leads')) {
            abort(403, 'This feature requires a premium package');
        }

        $leads = Consult::query()
            ->where(function ($q) use ($account) {
                $q->whereHas('property', function ($query) use ($account) {
                    $query->where('author_id', $account->id);
                })
                    ->orWhereHas('project', function ($query) use ($account) {
                        $query->where('author_id', $account->id);
                    });
            })
            ->with(['property', 'project'])
            ->get();

        $filename = 'leads_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($leads) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Name', 'Email', 'Phone', 'Property', 'Status', 'Message']);

            foreach ($leads as $lead) {
                fputcsv($file, [
                    $lead->created_at->format('Y-m-d H:i'),
                    $lead->name,
                    $lead->email,
                    $lead->phone,
                    $lead->property?->name ?? $lead->project?->name,
                    $lead->status,
                    $lead->content,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
