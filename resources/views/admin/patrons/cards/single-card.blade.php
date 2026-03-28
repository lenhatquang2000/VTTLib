<div class="card">
    <!-- Header -->
    <div class="card-header">
        <div class="library-logo">
            TL
        </div>
        <div class="card-title">Thẻ Độc Giả</div>
    </div>
    
    <!-- Body -->
    <div class="card-body">
        <!-- Photo Section -->
        <div class="photo-section">
            @if($patron->avatar)
                <img src="{{ asset('storage/' . $patron->avatar) }}" alt="{{ $patron->name }}" style="width: 100%; height: 100%; object-fit: cover;">
            @else
                <div class="photo-placeholder">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
            @endif
        </div>
        
        <!-- Info Section -->
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Họ tên:</span>
                <span class="info-value">{{ $patron->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Mã số:</span>
                <span class="info-value">{{ $patron->patron_code }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ngày sinh:</span>
                <span class="info-value">{{ $patron->date_of_birth ? \Carbon\Carbon::parse($patron->date_of_birth)->format('d/m/Y') : 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Giới tính:</span>
                <span class="info-value">{{ $patron->gender == 'male' ? 'Nam' : ($patron->gender == 'female' ? 'Nữ' : 'Khác') }}</span>
            </div>
        </div>
    </div>
    
    <!-- Barcode Section -->
    <div class="barcode-section">
        <div class="barcode">
            {!! $patron->barcode_svg !!}
        </div>
        <div class="barcode-text">{{ $patron->patron_code }}</div>
    </div>
    
    <!-- Footer -->
    <div class="card-footer">
        <div>Thư viện - Hết hạn: <span class="expiry-date">{{ $patron->expiry_date ? \Carbon\Carbon::parse($patron->expiry_date)->format('d/m/Y') : 'Không giới hạn' }}</span></div>
    </div>
</div>
