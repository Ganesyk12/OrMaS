<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Organisasi <span class="text-danger">*</span></label>
    <select name="organization_id" class="form-select @error('organization_id') is-invalid @enderror" required>
      <option value="">-- Pilih Organisasi --</option>
      @foreach($organizations as $org)
        <option value="{{ $org->id }}" @selected(old('organization_id', $meeting->organization_id ?? '') == $org->id)>{{ $org->name }}</option>
      @endforeach
    </select>
    @error('organization_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Judul <span class="text-danger">*</span></label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $meeting->title ?? '') }}" required>
    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Tanggal & Waktu <span class="text-danger">*</span></label>
    <input type="datetime-local" name="meeting_date" class="form-control @error('meeting_date') is-invalid @enderror" value="{{ old('meeting_date', $meeting?->meeting_date?->format('Y-m-d\TH:i')) }}" required>
    @error('meeting_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Status <span class="text-danger">*</span></label>
    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
      @foreach(['scheduled' => 'Dijadwalkan', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $value => $label)
        <option value="{{ $value }}" @selected(old('status', $meeting->status ?? 'scheduled') === $value)>{{ $label }}</option>
      @endforeach
    </select>
    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-12">
    <label class="form-label">Lokasi</label>
    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $meeting->location ?? '') }}">
    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-12">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $meeting->description ?? '') }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
