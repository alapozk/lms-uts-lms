@extends('layouts.app')

@section('content')
<style>
  .page{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:1100px;margin:0 auto}

  .back{display:inline-flex;gap:8px;align-items:center;color:#667eea;text-decoration:none;font-weight:700;margin-bottom:14px}

  .hero{border-radius:20px;overflow:hidden;box-shadow:0 15px 40px rgba(0,0,0,.12);margin-bottom:18px}
  .hero-top{height:190px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;font-size:3rem}
  .hero-bottom{background:#fff;padding:26px 28px;display:flex;align-items:center;gap:14px}
  .title{font-size:2.1rem;font-weight:900;color:#111827;margin:0}
  .muted{color:#6b7280}

  .actions{display:flex;gap:12px;margin-left:auto}
  .btn{display:inline-flex;gap:8px;align-items:center;padding:10px 16px;border-radius:12px;border:1px solid #e5e7eb;background:#f9fafb;color:#111827;text-decoration:none;font-weight:700}
  .btn:hover{background:#f3f4f6}
  .btn-primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none;box-shadow:0 8px 20px rgba(102,126,234,.35)}
  .btn-primary:hover{transform:translateY(-1px)}

  .card{background:#fff;border-radius:18px;box-shadow:0 15px 40px rgba(0,0,0,.10);padding:22px;margin-top:14px}
  .card h3{display:flex;gap:10px;align-items:center;font-weight:900;margin:0 0 10px}

  .materials-grid{display:grid;grid-template-columns:1fr;gap:14px}
  .material-card{background:#fff;border-radius:14px;padding:14px 16px;box-shadow:0 8px 24px rgba(0,0,0,.08);display:flex;gap:12px;align-items:flex-start;transition:.25s}
  .material-card:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(0,0,0,.12)}
  .mat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;color:#fff}
  .mat-body{flex:1;min-width:0}
  .mat-title{font-weight:800;color:#111827;margin:0 0 4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .mat-meta{color:#6b7280;font-size:.85rem}
  .mat-actions{display:flex;gap:8px;margin-top:10px;flex-wrap:wrap}
  .btn-xs{padding:7px 10px;border-radius:8px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:700;text-decoration:none;font-size:.85rem}
  .btn-xs:hover{background:#f3f4f6}

  .badge{display:inline-flex;align-items:center;gap:6px;font-weight:800;font-size:.75rem;border-radius:999px;padding:5px 9px}
  .badge-video{background:#e0f2fe;color:#075985}
  .badge-pdf{background:#fee2e2;color:#7f1d1d}
  .badge-ppt{background:#fff7ed;color:#7c2d12}
  .badge-other{background:#e5e7eb;color:#374151}

  @media (max-width:900px){
    .two-col{grid-template-columns:1fr!important}
  }
</style>

<div class="page">
  <div class="container">

    <a href="{{ route('teacher.courses') }}" class="back">← Kembali</a>

    <div class="hero">
      <div class="hero-top">📖</div>
      <div class="hero-bottom">
        <h1 class="title">{{ $course->title }}</h1>
        <span class="muted" style="font-weight:800">({{ strtoupper($course->status) }})</span>
        <div class="actions">
          <a href="{{ route('teacher.courses.edit',$course) }}" class="btn">⚙️ Pengaturan</a>
        </div>
      </div>
    </div>

    <!-- Card gabungan Materi & Tugas -->
    <div class="card">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px">
        <h3 style="display:flex;align-items:center;gap:10px;margin:0;font-weight:900;">📚 Materi & 🚀 Tugas</h3>
        <div style="display:flex;gap:10px;flex-wrap:wrap">
          <a href="{{ route('teacher.materials.create',['course'=>$course->id]) }}" class="btn btn-primary">＋ Tambah Materi</a>
          <a href="{{ route('teacher.assignments.create',['course'=>$course->id]) }}" class="btn btn-primary">📝 Buat Tugas</a>
        </div>
      </div>

      <div class="two-col" style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
        <!-- Materi -->
        <div>
          <h4 style="font-weight:800;margin-bottom:8px">📄 Materi</h4>
          @php $materials = method_exists($course,'materials') ? $course->materials : collect(); @endphp
          @if($materials->count())
            <div class="materials-grid">
              @foreach($materials as $m)
                @php
                  $ext = strtolower($m->extension ?? pathinfo($m->file_path, PATHINFO_EXTENSION));
                  $mime = strtolower($m->mime ?? '');
                  $isVideo = str_starts_with($mime,'video') || in_array($ext,['mp4','mov','mkv','webm']);
                  $isPDF  = $ext==='pdf';
                  $isPPT  = in_array($ext,['ppt','pptx','pps','ppsx']);
                  $badgeClass = $isVideo?'badge-video':($isPDF?'badge-pdf':($isPPT?'badge-ppt':'badge-other'));
                  $badgeText  = $isVideo?'Video':($isPDF?'PDF':($isPPT?'PPT':strtoupper($ext ?: 'FILE')));
                  $iconColor  = $isVideo?'#3b82f6':($isPDF?'#ef4444':($isPPT?'#f97316':'#6b7280'));
                  $icon       = $isVideo?'🎬':($isPDF?'📕':($isPPT?'📊':'📎'));
                @endphp

                <div class="material-card">
                  <div class="mat-icon" style="background:{{ $iconColor }}">{{ $icon }}</div>
                  <div class="mat-body">
                    <div class="mat-title" title="{{ $m->title }}">{{ $m->title }}</div>
                    <div class="mat-meta">
                      <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                      <span style="margin-left:8px">{{ optional($m->created_at)->format('d M Y') }}</span>
                    </div>
                    <div class="mat-actions">
                      <a class="btn-xs" href="{{ route('teacher.materials.show',[$course->id,$m->id]) }}">👁️ View</a>
                      <a class="btn-xs" href="{{ route('teacher.materials.edit',[$course->id,$m->id]) }}">✏️ Edit</a>
                      <form method="POST" action="{{ route('teacher.materials.destroy',[$course->id,$m->id]) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-xs" onclick="return confirm('Hapus materi ini?')">🗑️ Hapus</button>
                      </form>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="muted">Belum ada materi.</p>
          @endif
        </div>

        <!-- Tugas -->
        <div>
          <h4 style="font-weight:800;margin-bottom:8px">🧾 Tugas</h4>
          @php $assignments = method_exists($course,'assignments') ? $course->assignments : collect(); @endphp
          @if($assignments->count())
            <div style="display:flex;flex-direction:column;gap:12px">
              @foreach($assignments as $a)
                <div class="material-card">
                  <div class="mat-icon" style="background:linear-gradient(135deg,#8b5cf6 0%,#6d28d9 100%)">📝</div>
                  <div class="mat-body">
                    <div class="mat-title">{{ $a->title }}</div>
                    <div class="mat-meta">
                      <span class="badge" style="background:#e9d5ff;color:#6b21a8">TUGAS</span>
                      <span style="margin-left:8px">Deadline {{ optional($a->due_at)->format('d M Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="mat-actions">
                      <a class="btn-xs" href="{{ route('teacher.assignments.show',[$course->id,$a->id]) }}">👁️ View</a>
                      <a class="btn-xs" href="{{ route('teacher.assignments.edit',[$course->id,$a->id]) }}">✏️ Edit</a>
                      <form method="POST" action="{{ route('teacher.assignments.destroy',[$course->id,$a->id]) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-xs" onclick="return confirm('Hapus tugas ini?')">🗑️ Hapus</button>
                      </form>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <p class="muted">Belum ada tugas.</p>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
