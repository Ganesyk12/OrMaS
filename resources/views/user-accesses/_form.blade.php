<div class="row g-3">
  <div class="col-md-12">
    @if($access)
    <label class="form-label">Pengguna</label>
    <input type="text" class="form-control" value="{{ $access->user->name ?? '-' }} ({{ $access->user->email ?? '-' }})" disabled>
    @else
    <label class="form-label">Pengguna <span class="text-danger">*</span></label>
    <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
      <option value="">-- Pilih Pengguna --</option>
      @foreach($users as $user)
      <option value="{{ $user->id }}" @selected(old('user_id')==$user->id)>{{ $user->name }} ({{ $user->email }})</option>
      @endforeach
    </select>
    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    @endif
  </div>

  <div class="col-md-12">
    <input type="hidden" name="access_mode" value="1">
    <label class="form-label">Hak Akses per Modul</label>
    <div class="table-responsive border rounded">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="min-width:200px;">Modul</th>
            @foreach(['view' => 'Lihat', 'create' => 'Buat', 'edit' => 'Ubah', 'delete' => 'Hapus'] as $permissionLabel)
            <th class="text-center">{{ $permissionLabel }}</th>
            @endforeach
          </tr>
        </thead>
        <tbody>
          @foreach($modules as $module)
          @php
            $moduleName = $module->name;
            $moduleLabel = $module->label;
            $existing = $accessByModule[$moduleName] ?? null;
          @endphp
          <tr>
            <td class="fw-semibold text-dark">{{ $moduleLabel }}</td>
            @foreach(['view' => 'Lihat', 'create' => 'Buat', 'edit' => 'Ubah', 'delete' => 'Hapus'] as $permission => $permissionLabel)
            <td class="text-center">
              <input type="checkbox"
                name="access[{{ $moduleName }}][{{ $permission }}]"
                id="access_{{ $moduleName }}_{{ $permission }}"
                value="1"
                class="form-check-input"
                @checked(old('access.'.$moduleName.'.'.$permission, $existing?->{'allow_'.$permission} ?? false))>
            </td>
            @endforeach
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="form-text">
      @if($access)
        Centang permission yang dibutuhkan. Modul yang tidak dicentang akan dihapus aksesnya.
      @else
        Centang permission yang dibutuhkan. Modul yang sudah ada tidak berubah kecuali dicentang dan disimpan.
      @endif
    </div>
  </div>
</div>
