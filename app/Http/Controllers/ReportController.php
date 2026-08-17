<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    /**
     * User melaporkan konten (post/comment) via AJAX.
     * Satu laporan per user per konten (dicegah eksplisit + unique DB).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'reportable_type' => ['required', Rule::in(['post', 'comment'])],
            'reportable_id' => ['required', 'integer'],
            'reason' => ['nullable', 'string', 'max:200'],
        ]);

        // Pastikan konten yang dilaporkan benar-benar ada — tanpa ini, laporan
        // untuk ID acak/postingan terhapus tetap masuk ke antrean moderation.
        $exists = $validated['reportable_type'] === 'post'
            ? Post::whereKey($validated['reportable_id'])->exists()
            : Comment::whereKey($validated['reportable_id'])->exists();

        if (! $exists) {
            return response()->json([
                'ok' => false,
                'message' => 'Konten yang dilaporkan tidak ditemukan.',
            ], 404);
        }

        $already = Report::where('reporter_user_id', Auth::id())
            ->where('reportable_type', $validated['reportable_type'])
            ->where('reportable_id', $validated['reportable_id'])
            ->exists();

        if ($already) {
            return response()->json(['already' => true, 'message' => 'Konten ini sudah Anda laporkan.']);
        }

        Report::create([
            'reporter_user_id' => Auth::id(),
            'reportable_type' => $validated['reportable_type'],
            'reportable_id' => $validated['reportable_id'],
            'reason' => $validated['reason'] ?? 'Spam / penyalahgunaan',
            'status' => 'pending',
        ]);

        return response()->json(['ok' => true, 'message' => 'Terlapor, terima kasih.']);
    }

    /**
     * Admin menandai laporan selesai (status -> resolved).
     */
    public function resolve(Report $report): RedirectResponse
    {
        if (! Auth::user() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Hanya admin.');
        }

        $report->update(['status' => 'resolved']);

        return redirect()->back()->with('success', 'Laporan ditandai selesai.');
    }
}
