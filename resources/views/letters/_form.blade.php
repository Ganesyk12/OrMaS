@php
    $isIncoming = $direction === 'incoming';
@endphp

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Kategori Surat <span class="text-danger">*</span></label>
    <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
      <option value="">-- Pilih Kategori --</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}" @selected((string) old('category_id', $letter->category_id ?? '') === (string) $category->id)>{{ $category->name }}</option>
      @endforeach
    </select>
    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
    <input type="text" name="letter_number" class="form-control @error('letter_number') is-invalid @enderror" value="{{ old('letter_number', $letter->letter_number ?? '') }}" placeholder="Contoh: 001/KT/XI/2026" required>
    @error('letter_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-12">
    <label class="form-label">Perihal <span class="text-danger">*</span></label>
    <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $letter->subject ?? '') }}" placeholder="Perihal surat" required>
    @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">{{ $isIncoming ? 'Pengirim' : 'Tujuan' }} <span class="text-danger">*</span></label>
    @if($isIncoming)
      <input type="text" name="sender" class="form-control @error('sender') is-invalid @enderror" value="{{ old('sender', $letter->sender ?? '') }}" placeholder="Nama pengirim / instansi" required>
      @error('sender')<div class="invalid-feedback">{{ $message }}</div>@enderror
    @else
      <input type="text" name="recipient" class="form-control @error('recipient') is-invalid @enderror" value="{{ old('recipient', $letter->recipient ?? '') }}" placeholder="Nama tujuan / instansi" required>
      @error('recipient')<div class="invalid-feedback">{{ $message }}</div>@enderror
    @endif
  </div>

  <div class="col-md-6">
    <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
    <input type="date" name="letter_date" class="form-control @error('letter_date') is-invalid @enderror" value="{{ old('letter_date', $letter?->letter_date?->format('Y-m-d')) }}" required>
    @error('letter_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>

  @if($isIncoming)
    <div class="col-md-6">
      <label class="form-label">Tanggal Diterima</label>
      <input type="date" name="received_date" class="form-control @error('received_date') is-invalid @enderror" value="{{ old('received_date', $letter?->received_date?->format('Y-m-d')) }}">
      @error('received_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
  @endif

  <div class="col-md-12">
    <label class="form-label">Catatan</label>
    <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Catatan tambahan (opsional)">{{ old('notes', $letter->notes ?? '') }}</textarea>
    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
