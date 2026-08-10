{{-- resources/views/profile/edit.blade.php --}}
<?php $pageTitle = 'Profil - TSAQIB SMAN 1 Bukittinggi'; ?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    @include('partials.theme-head')
    @push('styles')
        /* ===== Profil Redesign (.pr-*) ===== */
        .pr-cover{
            height:7rem;
            background:
                radial-gradient(circle at 20% 0%, rgba(201,166,107,.25), transparent 60%),
                linear-gradient(135deg, var(--green), var(--green-dark));
            border-radius:1rem 1rem 0 0;
        }
        .pr-avatar{
            width:6rem;height:6rem;border-radius:999px;object-fit:cover;
            border:4px solid var(--ink);background:#161a14;
            margin-top:-3rem;box-shadow:0 8px 24px -8px rgba(0,0,0,.6);
        }
        .pr-badge{
            display:inline-flex;align-items:center;gap:.3rem;
            padding:.2rem .55rem;border-radius:999px;
            font-size:10px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;
        }
        .pr-badge-role{ background:rgba(1,121,95,.16); color:#3fd6b0; }
        .pr-badge-admin{ background:rgba(201,166,107,.18); color:var(--gold); }
        .pr-stat{ display:flex;flex-direction:column; }
        .pr-stat b{ font-family:'Plus Jakarta Sans',sans-serif;font-size:1.05rem;color:var(--cream); }
        .pr-stat span{ font-size:11px;color:rgba(247,245,239,.5);text-transform:uppercase;letter-spacing:.05em; }

        .pr-chip{
            display:inline-flex;align-items:center;gap:.35rem;
            padding:.35rem .7rem;border-radius:999px;font-size:11px;font-weight:700;
            background:rgba(247,245,239,.05);border:1px solid rgba(247,245,239,.12);
            color:rgba(247,245,239,.7);cursor:pointer;
            transition:background .15s ease,border-color .15s ease,color .15s ease;
        }
        .pr-chip:hover{ border-color:rgba(247,245,239,.25); }
        .pr-chip.is-on{ background:rgba(1,121,95,.2);border-color:var(--green);color:#3fd6b0; }

        .pr-tabs{ display:flex;gap:.25rem;border-bottom:1px solid rgba(247,245,239,.1); }
        .pr-tab{
            padding:.7rem 1rem;font-size:13px;font-weight:700;color:rgba(247,245,239,.55);
            border-bottom:2px solid transparent;cursor:pointer;background:none;
        }
        .pr-tab.is-active{ color:var(--cream);border-bottom-color:var(--gold); }
        .pr-panel{ display:none; }
        .pr-panel.is-active{ display:block; }

        .pr-follow{ transition:background .15s ease,color .15s ease,border-color .15s ease; }
        .pr-follow.is-following{
            background:transparent;border:1px solid rgba(247,245,239,.3);color:var(--cream);
        }
        .pr-iconbtn{
            display:inline-flex;align-items:center;gap:.4rem;
            padding:.35rem .7rem;border-radius:.6rem;font-size:12px;font-weight:700;
            background:rgba(247,245,239,.05);border:1px solid rgba(247,245,239,.12);
            color:rgba(247,245,239,.75);cursor:pointer;
        }
        .pr-iconbtn:hover{ border-color:rgba(247,245,239,.28); }
        .pr-iconbtn.is-on{ background:rgba(201,166,107,.16);border-color:var(--gold);color:var(--gold); }

        .pr-modal{ position:fixed;inset:0;z-index:60;display:none; }
        .pr-modal.is-open{ display:flex; }
        .pr-modal-bg{ position:absolute;inset:0;background:rgba(0,0,0,.65); }
        .pr-modal-card{ position:relative;margin:auto;width:min(440px,92vw);max-height:90vh;overflow:auto; }
        .pr-empty{ padding:2.5rem 1rem;text-align:center;color:rgba(247,245,239,.4);font-size:12px; }
    @endpush
</head>
<body class="text-[var(--cream)] font-sans antialiased min-h-screen flex flex-col">

    @include('partials.navbar')

    @php
        // ---- Konteks: user profil + pemilik? ----
        $authUser = Auth::user();
        $user = $user ?? $authUser;
        $isOwner = (bool) $authUser && $authUser->id === $user->id;
        $isAdmin = ($user->role ?? null) === 'admin';

        // ---- Postingan user (via model, tanpa relasi) ----
        $postsQuery = \App\Models\Post::where('user_id', $user->id);
        $postsCount = (clone $postsQuery)->count();
        $posts = (clone $postsQuery)->latest()->limit(10)->get();

        // ---- Komentar user (defensif) ----
        $comments = collect(); $commentsCount = 0;
        if (class_exists(\App\Models\Comment::class)) {
            try {
                $commentsCount = \App\Models\Comment::where('user_id', $user->id)->count();
                $comments = \App\Models\Comment::where('user_id', $user->id)->latest()->limit(10)->get();
            } catch (\Throwable $e) {}
        }

        // ---- Followers / following (defensif: relasi belum ada) ----
        $followersCount = 0; $followingCount = 0; $isFollowing = false;
        if (method_exists($user, 'followers')) {
            try { $followersCount = $user->followers()->count(); } catch (\Throwable $e) {}
        }
        if (method_exists($user, 'following')) {
            try { $followingCount = $user->following()->count(); } catch (\Throwable $e) {}
        }
        if ($authUser && !$isOwner && method_exists($user, 'followers')) {
            try { $isFollowing = $user->followers()->where('follower_id', $authUser->id)->exists(); } catch (\Throwable $e) {}
        }

        // ---- State like user (defensif) ----
        $likedIds = [];
        if ($authUser && class_exists(\App\Models\Like::class)) {
            try { $likedIds = \App\Models\Like::where('user_id', $authUser->id)->pluck('post_id')->all(); } catch (\Throwable $e) {}
        }

        // ---- Komunitas / chip minat (defensif) ----
        $communities = collect(); $allCommunities = collect();
        if (class_exists(\App\Models\Community::class)) {
            try { $allCommunities = \App\Models\Community::orderBy('name')->get(); } catch (\Throwable $e) {}
        }
        if (method_exists($user, 'communities')) {
            try { $communities = $user->communities()->pluck('communities.id')->all(); } catch (\Throwable $e) {}
        }
    @endphp

    <main class="flex-1 max-w-3xl mx-auto px-4 sm:px-6 py-8 sm:py-12 space-y-6 w-full">

        {{-- Status flash --}}
        @if(session('status'))
            <div class="tsaqib-card p-3 text-xs text-[#3fd6b0] flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i> Profil berhasil diperbarui.
            </div>
        @endif

        {{-- ============ HEADER PROFIL ============ --}}
        <section class="tsaqib-card overflow-hidden">
            <div class="pr-cover"></div>

            <div class="px-5 pb-5">
                <div class="flex items-end justify-between gap-4">
                    <img src="{{ $user->getAvatar() }}" alt="Avatar" class="pr-avatar">

                    @if($isOwner)
                        <div class="flex items-center gap-2 pb-1">
                            <button type="button" class="pr-iconbtn" onclick="document.getElementById('pr-edit-modal').classList.add('is-open')">
                                <i class="fa-solid fa-pen"></i> Edit Profil
                            </button>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="pr-iconbtn" title="Keluar">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <button type="button"
                                class="pr-follow cta-primary text-white text-xs font-bold px-4 py-2 rounded-lg {{ $isFollowing ? 'is-following' : '' }}"
                                data-follow-user="{{ $user->id }}" data-following="{{ $isFollowing ? '1' : '0' }}">
                            <i class="fa-solid fa-user-plus mr-1"></i>
                            <span>{{ $isFollowing ? 'Mengikuti' : 'Ikuti' }}</span>
                        </button>
                    @endif
                </div>

                <div class="mt-3">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h1 class="text-xl font-display font-extrabold text-[var(--cream)] leading-tight">{{ $user->name }}</h1>
                        @if($isAdmin)
                            <span class="pr-badge pr-badge-admin"><i class="fa-solid fa-shield-halved"></i> Admin</span>
                        @endif
                    </div>
                    <p class="text-xs text-white/45">@{{ explode('@', $user->email)[0] }}</p>

                    @if($isAdmin)
                        <span class="pr-badge pr-badge-role mt-2"><i class="fa-solid fa-user-shield"></i> Administrator Sistem</span>
                    @else
                        <span class="pr-badge pr-badge-role mt-2"><i class="fa-solid fa-user-check"></i> Member</span>
                    @endif

                    @if(!empty($user->bio))
                        <p class="text-sm text-white/70 leading-relaxed mt-2">{{ $user->bio }}</p>
                    @endif

                    @if($isOwner)
                        <p class="text-xs text-white/40 mt-2 flex items-center gap-1.5">
                            <i class="fa-solid fa-envelope"></i> {{ $user->email }}
                        </p>
                    @endif
                </div>

                {{-- Stats (plain text, no card) --}}
                <div class="flex items-center gap-6 mt-4 pt-4 border-t border-white/10">
                    <div class="pr-stat"><b>{{ $postsCount }}</b><span>Postingan</span></div>
                    <div class="pr-stat"><b>{{ $followersCount }}</b><span>Pengikut</span></div>
                    <div class="pr-stat"><b>{{ $followingCount }}</b><span>Mengikuti</span></div>
                </div>

                {{-- Chip minat komunitas (owner: toggle; tamu: read-only) --}}
                @if($allCommunities->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mt-4">
                        @foreach($allCommunities as $c)
                            @php $on = in_array($c->id, $communities); @endphp
                            @if($isOwner)
                                <button type="button" class="pr-chip {{ $on ? 'is-on' : '' }}"
                                        data-community="{{ $c->id }}" data-on="{{ $on ? '1' : '0' }}">
                                    <i class="fa-solid {{ $on ? 'fa-check' : 'fa-plus' }}"></i> {{ $c->name }}
                                </button>
                            @else
                                <span class="pr-chip {{ $on ? 'is-on' : '' }}" style="cursor:default">
                                    <i class="fa-solid fa-layer-group"></i> {{ $c->name }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        {{-- ============ TABS ============ --}}
        <section class="tsaqib-card">
            <div class="pr-tabs px-3">
                <button class="pr-tab is-active" data-tab="posts"><i class="fa-solid fa-newspaper mr-1"></i> Postingan</button>
                <button class="pr-tab" data-tab="comments"><i class="fa-solid fa-comment mr-1"></i> Komentar</button>
                @if($isOwner)
                    <button class="pr-tab" data-tab="saved"><i class="fa-solid fa-bookmark mr-1"></i> Disimpan</button>
                @endif
            </div>

            {{-- Panel: Postingan --}}
            <div class="pr-panel is-active p-4" id="panel-posts">
                @if($posts->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($posts as $post)
                            @php
                                $likeCount = method_exists($post, 'likes') ? $post->likes()->count() : 0;
                                $liked = in_array($post->id, $likedIds);
                            @endphp
                            <div class="p-4 rounded-xl bg-white/5 border border-white/10">
                                <div class="flex items-center justify-between gap-3">
                                    <a href="{{ route('komunitas.post.show', $post->id) }}" class="min-w-0">
                                        <span class="text-[10px] font-bold text-[var(--gold)] uppercase tracking-wider block">
                                            {{ $post->community_slug ?? 'komunitas' }} • {{ $post->created_at->diffForHumans() }}
                                        </span>
                                        <h4 class="font-bold text-sm text-[var(--cream)] truncate">{{ $post->title }}</h4>
                                        <p class="text-xs text-white/60 truncate">{{ $post->content }}</p>
                                    </a>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <button type="button" class="pr-iconbtn pr-like {{ $liked ? 'is-on' : '' }}"
                                                data-post="{{ $post->id }}" data-liked="{{ $liked ? '1' : '0' }}">
                                            <i class="fa-solid fa-heart"></i>
                                            <span class="pr-like-count">{{ $likeCount }}</span>
                                            <span class="pr-like-label">{{ $liked ? 'Disukai' : 'Suka' }}</span>
                                        </button>
                                        @if($isOwner)
                                            <button type="button" class="pr-iconbtn pr-save" data-post="{{ $post->id }}">
                                                <i class="fa-regular fa-bookmark"></i> Simpan
                                            </button>
                                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                                  onsubmit="return confirm('Hapus postingan ini?')">
                                                @csrf @method('DELETE')
                                                <button class="pr-iconbtn" style="color:#f87171">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="pr-empty">
                        <i class="fa-regular fa-newspaper text-2xl mb-2 block opacity-50"></i>
                        Belum ada postingan.
                    </div>
                @endif
            </div>

            {{-- Panel: Komentar --}}
            <div class="pr-panel p-4" id="panel-comments">
                @if($comments->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($comments as $comment)
                            <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                                <p class="text-xs text-white/70">{{ $comment->content ?? $comment->body ?? '' }}</p>
                                <span class="text-[10px] text-white/40 mt-1 block">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="pr-empty">
                        <i class="fa-regular fa-comment text-2xl mb-2 block opacity-50"></i>
                        Belum ada komentar.
                    </div>
                @endif
            </div>

            {{-- Panel: Disimpan (owner only) --}}
            @if($isOwner)
                <div class="pr-panel p-4" id="panel-saved">
                    <div class="pr-empty">
                        <i class="fa-regular fa-bookmark text-2xl mb-2 block opacity-50"></i>
                        Item tersimpan (buku &amp; postingan) akan tampil di sini.
                    </div>
                </div>
            @endif
        </section>

        @if($isOwner)
            <div class="tsaqib-card p-5">
                <h3 class="font-bold text-sm text-[var(--cream)] mb-1">Hapus Akun</h3>
                <p class="text-xs text-white/50 mb-3">Tindakan ini permanen dan menghapus data Anda.</p>
                <button type="button" class="pr-iconbtn" style="color:#f87171;border-color:rgba(248,113,113,.3)"
                        onclick="document.getElementById('pr-delete-modal').classList.add('is-open')">
                    <i class="fa-solid fa-trash"></i> Hapus Akun Saya
                </button>
            </div>
        @endif

    </main>

    {{-- ============ MODAL: EDIT PROFIL (owner) ============ --}}
    @if($isOwner)
    <div class="pr-modal" id="pr-edit-modal" role="dialog" aria-modal="true">
        <div class="pr-modal-bg" onclick="this.parentElement.classList.remove('is-open')"></div>
        <div class="tsaqib-card pr-modal-card p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="font-display font-bold text-[var(--cream)]">Edit Profil</h3>
                <button type="button" class="text-white/50 hover:text-white" onclick="this.closest('.pr-modal').classList.remove('is-open')">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PATCH')
                <div class="space-y-3">
                    <div>
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-wider block mb-1">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="tsaqib-input w-full px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-wider block mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="tsaqib-input w-full px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-wider block mb-1">Bio (maks 160)</label>
                        <textarea name="bio" rows="3" maxlength="160" class="tsaqib-input w-full px-3 py-2 text-sm"
                                  placeholder="Ceritakan sedikit tentang Anda...">{{ old('bio', $user->bio ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-white/40 uppercase tracking-wider block mb-1">Avatar</label>
                        <input type="file" name="avatar" accept="image/*" class="text-xs text-white/60 w-full">
                        <p class="text-[10px] text-white/35 mt-1">Maks 2MB. Diresize otomatis 400×400.</p>
                    </div>
                    @error('bio')<p class="text-[11px] text-red-400">{{ $message }}</p>@enderror
                    @error('avatar')<p class="text-[11px] text-red-400">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="cta-primary w-full mt-4 py-2.5 rounded-xl text-white text-sm font-bold">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    {{-- ============ MODAL: HAPUS AKUN (owner) ============ --}}
    <div class="pr-modal" id="pr-delete-modal" role="dialog" aria-modal="true">
        <div class="pr-modal-bg" onclick="this.parentElement.classList.remove('is-open')"></div>
        <div class="tsaqib-card pr-modal-card p-6 space-y-4">
            <h3 class="font-display font-bold text-[var(--cream)]">Hapus Akun?</h3>
            <p class="text-xs text-white/55">Masukkan password Anda untuk konfirmasi. Tindakan ini tidak dapat dibatalkan.</p>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf @method('DELETE')
                <input type="password" name="password" placeholder="Password" class="tsaqib-input w-full px-3 py-2 text-sm mb-3">
                <button type="submit" class="w-full py-2.5 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-bold">
                    <i class="fa-solid fa-trash mr-1"></i> Hapus Permanen
                </button>
            </form>
        </div>
    </div>
    @endif

    @include('partials.site-footer')

    <script>
    (function () {
        var csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
        function req(url, method){ return fetch(url,{method:method,headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'},credentials:'same-origin'}); }

        // ---- Tabs ----
        document.querySelectorAll('.pr-tab').forEach(function (tab) {
            tab.addEventListener('click', function () {
                document.querySelectorAll('.pr-tab').forEach(function (t){ t.classList.remove('is-active'); });
                document.querySelectorAll('.pr-panel').forEach(function (p){ p.classList.remove('is-active'); });
                tab.classList.add('is-active');
                var panel = document.getElementById('panel-' + tab.dataset.tab);
                if (panel) panel.classList.add('is-active');
            });
        });

        // ---- Follow / unfollow ----
        document.querySelectorAll('[data-follow-user]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var uid = btn.dataset.followUser, following = btn.dataset.following === '1';
                var label = btn.querySelector('span'), icon = btn.querySelector('i');
                req('/profile/' + uid + '/follow', following ? 'DELETE' : 'POST')
                  .then(function (r){ if(!r.ok) throw new Error('x'); return r.json(); })
                  .then(function () {
                      btn.dataset.following = following ? '0' : '1';
                      btn.classList.toggle('is-following', !following);
                      label.textContent = following ? 'Ikuti' : 'Mengikuti';
                      icon.className = 'fa-solid mr-1 ' + (following ? 'fa-user-plus' : 'fa-user-check');
                  })
                  .catch(function (){ /* endpoint belum ada — revert diam-diam */ });
            });
        });

        // ---- Like toggle ----
        document.querySelectorAll('.pr-like').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var pid = btn.dataset.post, liked = btn.dataset.liked === '1';
                var countEl = btn.querySelector('.pr-like-count'), labelEl = btn.querySelector('.pr-like-label');
                var n = parseInt(countEl.textContent || '0', 10) || 0;
                // optimistic
                countEl.textContent = liked ? Math.max(0, n - 1) : n + 1;
                labelEl.textContent = liked ? 'Suka' : 'Disukai';
                btn.classList.toggle('is-on', !liked);
                btn.dataset.liked = liked ? '0' : '1';
                req('/posts/' + pid + '/like', 'POST')
                  .then(function (r){ return r.ok ? r.json() : Promise.reject(); })
                  .catch(function (){ /* revert */ countEl.textContent = n; labelEl.textContent = liked ? 'Disukai' : 'Suka'; btn.classList.toggle('is-on', liked); btn.dataset.liked = liked ? '1' : '0'; });
            });
        });

        // ---- Save toggle ----
        document.querySelectorAll('.pr-save').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var pid = btn.dataset.post, on = btn.classList.contains('is-on');
                btn.classList.toggle('is-on', !on);
                btn.innerHTML = on ? '<i class="fa-regular fa-bookmark"></i> Simpan' : '<i class="fa-solid fa-bookmark"></i> Tersimpan';
                req('/posts/' + pid + '/save', 'POST')
                  .catch(function (){ btn.classList.toggle('is-on', on); btn.innerHTML = on ? '<i class="fa-solid fa-bookmark"></i> Tersimpan' : '<i class="fa-regular fa-bookmark"></i> Simpan'; });
            });
        });

        // ---- Community chip toggle ----
        document.querySelectorAll('[data-community]').forEach(function (chip) {
            chip.addEventListener('click', function () {
                var cid = chip.dataset.community, on = chip.dataset.on === '1';
                req('/profile/interest/' + cid, on ? 'DELETE' : 'POST')
                  .then(function (r){ if(!r.ok) throw new Error('x'); })
                  .then(function () {
                      chip.dataset.on = on ? '0' : '1';
                      chip.classList.toggle('is-on', !on);
                      chip.querySelector('i').className = 'fa-solid ' + (on ? 'fa-plus' : 'fa-check');
                  })
                  .catch(function (){});
            });
        });
    })();
    </script>

</body>
</html>
