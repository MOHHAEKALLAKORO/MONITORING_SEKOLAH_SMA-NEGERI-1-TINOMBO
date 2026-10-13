<?php

namespace App\Http\Controllers;

use App\Models\ForumReply;
use App\Models\ForumTopic;
use App\Support\Roles;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public const CATEGORIES = ['Umum', 'Akademik', 'Kesiswaan', 'Administrasi', 'Pengumuman'];

    public function index(Request $request)
    {
        $q        = trim((string) $request->input('q'));
        $kategori = $request->input('kategori');

        $topics = ForumTopic::with('user')
            ->withCount('replies')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('title', 'like', '%' . $q . '%')
                      ->orWhere('body', 'like', '%' . $q . '%');
                });
            })
            ->when($kategori, fn ($query) => $query->where('category', $kategori))
            ->orderByDesc('is_pinned')
            ->orderByDesc('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('forum.index', [
            'topics'     => $topics,
            'categories' => self::CATEGORIES,
            'q'          => $q,
            'kategori'   => $kategori,
        ]);
    }

    public function create()
    {
        return view('forum.create', ['categories' => self::CATEGORIES]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|in:' . implode(',', self::CATEGORIES),
            'title'    => 'required|string|max:200',
            'body'     => 'required|string|max:10000',
        ]);

        if ($data['category'] === 'Pengumuman' && !Roles::isLeader($request->user()->role)) {
            return back()->withInput()->withErrors([
                'category' => 'Kategori Pengumuman hanya untuk Admin dan Kepala Sekolah.',
            ]);
        }

        $topic = ForumTopic::create($data + ['user_id' => $request->user()->id]);

        return redirect()->route('forum.show', $topic)->with('success', 'Topik berhasil dibuat!');
    }

    public function show(ForumTopic $topic)
    {
        $topic->load('user');
        $replies = $topic->replies()->with('user')->oldest()->get();

        return view('forum.show', compact('topic', 'replies'));
    }

    public function destroy(Request $request, ForumTopic $topic)
    {
        abort_unless($this->canDelete($request, $topic->user_id), 403);

        $topic->delete();

        return redirect()->route('forum.index')->with('success', 'Topik berhasil dihapus.');
    }

    public function togglePin(Request $request, ForumTopic $topic)
    {
        abort_unless(Roles::isLeader($request->user()->role), 403);

        $topic->is_pinned = !$topic->is_pinned;
        $topic->save();

        return back()->with('success', $topic->is_pinned ? 'Topik disematkan.' : 'Sematan topik dilepas.');
    }

    public function reply(Request $request, ForumTopic $topic)
    {
        $data = $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $reply = $topic->replies()->create([
            'user_id' => $request->user()->id,
            'body'    => $data['body'],
        ]);

        $topic->touch(); // naikkan topik ke atas daftar

        return redirect()->to(route('forum.show', $topic) . '#balasan-' . $reply->id)
            ->with('success', 'Balasan terkirim.');
    }

    public function destroyReply(Request $request, ForumReply $reply)
    {
        abort_unless($this->canDelete($request, $reply->user_id), 403);

        $topicId = $reply->forum_topic_id;
        $reply->delete();

        return redirect()->route('forum.show', $topicId)->with('success', 'Balasan dihapus.');
    }

    /** Penulis sendiri atau admin. */
    private function canDelete(Request $request, ?int $ownerId): bool
    {
        $user = $request->user();

        return $user->role === Roles::ADMIN || ($ownerId !== null && $user->id === $ownerId);
    }
}