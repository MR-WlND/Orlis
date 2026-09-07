@extends('layouts.customer')
@section('customer_title', 'Quản Lý Yêu Cầu Hỗ Trợ & Concierge')
@section('customer_content')

<div>
    <div class="tix-container">

        <!-- ===== Page Header ===== -->
        <div class="tix-page-header">
            <div>
                <h1 class="tix-page-title">Quản Lý Yêu Cầu Hỗ Trợ & Concierge</h1>
                <p class="tix-page-sub">Đội ngũ Concierge & Chăm sóc khách hàng Orlis luôn sẵn sàng đồng hành, giải quyết trọn vẹn mọi nguyện vọng của Quý khách theo chuẩn mực thanh lịch nhất.</p>
            </div>
            @auth
            @if(auth()->user()->membership_level)
            <div class="tix-badge-wrap">
                <div class="tix-badge">
                    <div class="tix-badge-label">HẠNG HỘI VIÊN</div>
                    <div class="tix-badge-level">{{ strtoupper(auth()->user()->membership_level) }}</div>
                </div>
            </div>
            @endif
            @endauth
        </div>

        <!-- ===== Main Layout ===== -->
        <div class="tix-layout">

            <!-- Left Column -->
            <div class="tix-left">

                <!-- Toolbar -->
                <div class="tix-toolbar">
                    <div class="tix-search-wrap">
                        <svg class="tix-search-icon" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" id="tixSearch" class="tix-search" placeholder="Tìm mã ticket, nội dung...">
                    </div>
                    <div class="tix-filters">
                        <button class="tix-filter-btn active" data-filter="all">Tất cả trạng thái</button>
                        <button class="tix-filter-btn" data-filter="open">Đang mở</button>
                        <button class="tix-filter-btn" data-filter="closed">Đã đóng</button>
                    </div>
                    <a href="{{ route('tickets.create') }}" class="tix-new-btn">
                        <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        GỬI YÊU CẦU MỚI
                    </a>
                </div>

                @if(session('success'))
                <div class="tix-alert-success">{{ session('success') }}</div>
                @endif

                <!-- Ticket Cards -->
                <div id="tixList">
                    @forelse($tickets as $ticket)
                    @php
                        $statusLabel = $ticket->status === 'open' ? 'Chờ Phản Hồi' : 'Đã Giải Quyết';
                        $statusClass = $ticket->status === 'open' ? 'tix-status-open' : 'tix-status-closed';

                        $priorityLabel = match($ticket->priority) {
                            'high'   => 'Ưu Tiên Cao',
                            'normal' => 'Tiêu Chuẩn',
                            default  => 'Thấp',
                        };
                        $priorityClass = match($ticket->priority) {
                            'high'   => 'tix-pri-high',
                            'normal' => 'tix-pri-normal',
                            default  => 'tix-pri-low',
                        };

                        // Parse the full subject to extract parts
                        preg_match('/^\[([^\]]+)\]\s*(.+?)(?:\s*-\s*Mã ĐH:\s*(.+))?$/', $ticket->subject, $m);
                        $topic       = $m[1] ?? '';
                        $summary     = $m[2] ?? $ticket->subject;
                        $orderRef    = $m[3] ?? null;

                        $firstReply  = $ticket->replies->first();
                        $snippet     = $firstReply ? \Illuminate\Support\Str::limit($firstReply->message, 120) : '—';
                        $handler     = $ticket->replies->contains(fn($r) => $r->user && $r->user->role === 'admin')
                                        ? $ticket->replies->first(fn($r) => $r->user && $r->user->role === 'admin')->user->name
                                        : null;
                    @endphp
                    <div class="tix-card" data-status="{{ $ticket->status }}">
                        <div class="tix-card-top">
                            <div class="tix-card-meta">
                                <span class="tix-ticket-id">#TK-{{ str_pad($ticket->id, 4, '0', STR_PAD_LEFT) }}</span>
                                @if($topic)
                                <span class="tix-topic">{{ strtoupper($topic) }}</span>
                                @endif
                            </div>
                            <div class="tix-card-badges">
                                <span class="tix-pri {{ $priorityClass }}">{{ $priorityLabel }}</span>
                                <span class="tix-status {{ $statusClass }}">{{ $statusLabel }}</span>
                            </div>
                        </div>

                        <a href="{{ route('tickets.show', $ticket) }}" class="tix-card-title">{{ $summary }}</a>

                        <p class="tix-card-snippet">{{ $snippet }}</p>

                        <div class="tix-card-bottom">
                            <div class="tix-card-info">
                                <span class="tix-card-date">
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    Cập nhật: {{ $ticket->updated_at->format('d/m/Y') }}
                                </span>
                                @if($handler)
                                <span class="tix-card-handler">
                                    <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Chuyên viên: {{ $handler }}
                                </span>
                                @elseif($orderRef)
                                <span class="tix-card-handler">
                                    <svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                    Đơn hàng: {{ $orderRef }}
                                </span>
                                @endif
                            </div>
                            <a href="{{ route('tickets.show', $ticket) }}" class="tix-detail-link">
                                CHI TIẾT
                                <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="tix-empty">
                        <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <p>Chưa có yêu cầu hỗ trợ nào.</p>
                        <a href="{{ route('tickets.create') }}" class="tix-new-btn" style="display:inline-flex; margin-top:15px;">Gửi yêu cầu đầu tiên</a>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination info -->
                @if($tickets->count() > 0)
                <div class="tix-pagination">
                    <span class="tix-page-count">Hiển thị {{ $tickets->count() }} trong tổng số {{ $tickets->count() }} yêu cầu</span>
                    <div class="tix-page-nav">
                        <button class="tix-page-btn" disabled>Trang trước</button>
                        <button class="tix-page-btn active">1</button>
                        <button class="tix-page-btn" disabled>Trang kế</button>
                    </div>
                </div>
                @endif

            </div><!-- /tix-left -->

            <!-- Right Sidebar -->
            <div class="tix-right">

                <!-- Card: Concierge Privé -->
                <div class="tix-sidebar-card tix-concierge-card">
                    <div class="tix-sc-label">DỊCH VỤ CONCIERGE PRIVÉ</div>
                    <h3 class="tix-sc-title">Trợ Lý Riêng &amp; Concierge 24/7</h3>
                    <p class="tix-sc-desc">Mỗi quý khách thuộc Maison Orlis được kết nối trực tiếp với chuyên viên tư vấn phong cách và nghệ nhân bảo tồn trang phục các nhân.</p>

                    <div class="tix-contact-list">
                        <div class="tix-contact-item">
                            <div class="tix-contact-icon">
                                <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.1 11.91 19.79 19.79 0 0 1 1.04 3.27 2 2 0 0 1 3 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.09 8.91A16 16 0 0 0 13 14.82l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 21 16z"/></svg>
                            </div>
                            <div>
                                <div class="tix-contact-label">HOTLINE (08:00 – 21:00)</div>
                                <div class="tix-contact-value">1800 6886 • Nhánh 1</div>
                            </div>
                        </div>
                        <div class="tix-divider"></div>
                        <div class="tix-contact-item">
                            <div class="tix-contact-icon tix-icon-green">
                                <span class="tix-dot"></span>
                            </div>
                            <div>
                                <div class="tix-contact-label">NHẮN TIN TRỰC TIẾP</div>
                                <div class="tix-contact-value tix-link-underline">Khởi tạo hội thoại riêng →</div>
                            </div>
                        </div>
                        <div class="tix-divider"></div>
                        <div class="tix-contact-item">
                            <div class="tix-contact-icon">
                                <svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            </div>
                            <div>
                                <div class="tix-contact-label">BOUTIQUE FLAGSHIP</div>
                                <div class="tix-contact-value tix-link-underline">Đặt lịch hẹn tại Salon Privé →</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Cam kết -->
                <div class="tix-sidebar-card">
                    <div class="tix-sc-label">CAM KẾT DỊCH VỤ ORLIS</div>
                    <ul class="tix-commit-list">
                        <li>
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Phản hồi yêu cầu trong <strong>2 – 4 giờ</strong> làm việc.
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Bảo mật dữ liệu tuyệt đối với mã hóa <strong>SSL 256-bit</strong>.
                        </li>
                        <li>
                            <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            Nghệ nhân chế tác & chuyên gia thẩm định trực tiếp tư vấn.
                        </li>
                    </ul>
                </div>

                <!-- Card: FAQ -->
                <div class="tix-sidebar-card">
                    <div class="tix-sc-label">CÂU HỎI THƯỜNG GẶP</div>
                    <ul class="tix-faq-list">
                        <li>
                            <button class="tix-faq-btn" onclick="this.parentElement.classList.toggle('open')">
                                Chính sách đổi trả đồ may đo cao cấp
                                <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="tix-faq-ans">Quý khách có thể đổi trả trong vòng 14 ngày kể từ ngày nhận hàng, miễn là sản phẩm còn nguyên seal và bao bì gốc.</div>
                        </li>
                        <li>
                            <button class="tix-faq-btn" onclick="this.parentElement.classList.toggle('open')">
                                Thời gian hoàn tiền tối đa bao nhiêu ngày?
                                <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="tix-faq-ans">Quy trình hoàn tiền được xử lý trong vòng 5–7 ngày làm việc sau khi yêu cầu được xác nhận.</div>
                        </li>
                        <li>
                            <button class="tix-faq-btn" onclick="this.parentElement.classList.toggle('open')">
                                Quy trình niêm phong & vận chuyển bí mật
                                <svg viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="tix-faq-ans">Mọi đơn hàng đều được đóng gói và vận chuyển bằng dịch vụ chuyên biệt đảm bảo tính riêng tư tuyệt đối.</div>
                        </li>
                    </ul>
                    <a href="#" class="tix-faq-link">XEM TẤT CẢ HƯỚNG DẪN – FAQ →</a>
                </div>

            </div><!-- /tix-right -->

        </div><!-- /tix-layout -->
    </div><!-- /tix-container -->
</div><!-- /tix-page -->

<style>
/* ===== PAGE WRAPPER ===== */
.tix-page {
    background: #f6f6f4;
    padding: 90px 0 80px;
    min-height: 80vh;
    font-family: var(--font-sans, 'Inter', sans-serif);
}
.tix-container {
    max-width: 1140px;
    margin: 0 auto;
    padding: 0 24px;
}

/* ===== PAGE HEADER ===== */
.tix-page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
    gap: 20px;
}
.tix-page-title {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 28px;
    font-weight: 400;
    color: #111;
    margin: 0 0 8px;
}
.tix-page-sub {
    font-size: 12.5px;
    color: #7a9bb8;
    margin: 0;
    max-width: 480px;
    line-height: 1.6;
}
.tix-badge-wrap { flex-shrink: 0; }
.tix-badge {
    border: 1.5px solid #b8a07e;
    padding: 8px 18px;
    text-align: center;
}
.tix-badge-label {
    font-size: 8px;
    letter-spacing: 1.5px;
    color: #b8a07e;
    font-weight: 600;
}
.tix-badge-level {
    font-size: 13px;
    font-weight: 700;
    color: #8c6d3f;
    letter-spacing: 1px;
    margin-top: 2px;
}

/* ===== MAIN LAYOUT ===== */
.tix-layout {
    display: grid;
    grid-template-columns: 1fr 310px;
    gap: 28px;
    align-items: start;
}

/* ===== TOOLBAR ===== */
.tix-toolbar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.tix-search-wrap {
    position: relative;
    flex: 1;
    min-width: 160px;
}
.tix-search-icon {
    width: 14px;
    height: 14px;
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    stroke: #aaa;
    fill: none;
    stroke-width: 2;
    pointer-events: none;
}
.tix-search {
    width: 100%;
    padding: 9px 12px 9px 36px;
    border: 1px solid #e0e0e0;
    background: white;
    font-size: 12.5px;
    color: #333;
    outline: none;
    transition: border-color 0.2s;
    font-family: inherit;
    border-radius: 2px;
    box-sizing: border-box;
}
.tix-search:focus { border-color: #cda873; }
.tix-search::placeholder { color: #bbb; }

.tix-filters { display: flex; gap: 6px; }
.tix-filter-btn {
    padding: 8px 14px;
    background: white;
    border: 1px solid #e0e0e0;
    font-size: 11.5px;
    color: #777;
    cursor: pointer;
    font-family: inherit;
    transition: all 0.15s;
    border-radius: 2px;
    white-space: nowrap;
}
.tix-filter-btn:hover, .tix-filter-btn.active {
    background: #111;
    color: white;
    border-color: #111;
}

.tix-new-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    background: #111;
    color: white;
    text-decoration: none;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.8px;
    white-space: nowrap;
    transition: background 0.2s;
    border-radius: 2px;
    border: none;
    cursor: pointer;
    font-family: inherit;
}
.tix-new-btn svg {
    width: 12px;
    height: 12px;
    stroke: white;
    fill: none;
    stroke-width: 2.5;
}
.tix-new-btn:hover { background: #333; }

/* ===== ALERT ===== */
.tix-alert-success {
    padding: 12px 16px;
    background: #e8f5e9;
    color: #2e7d32;
    border: 1px solid #c8e6c9;
    font-size: 13px;
    margin-bottom: 14px;
    border-radius: 2px;
}

/* ===== TICKET CARD ===== */
.tix-card {
    background: white;
    border: 1px solid #ebebeb;
    padding: 22px 24px;
    margin-bottom: 12px;
    transition: box-shadow 0.2s, border-color 0.2s;
}
.tix-card:hover {
    box-shadow: 0 4px 18px rgba(0,0,0,0.06);
    border-color: #ddd;
}

.tix-card-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}
.tix-card-meta {
    display: flex;
    align-items: center;
    gap: 10px;
}
.tix-ticket-id {
    font-size: 11px;
    font-weight: 700;
    color: #888;
    letter-spacing: 0.5px;
}
.tix-topic {
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #888;
    padding: 3px 8px;
    background: #f5f5f5;
    border: 1px solid #eee;
}
.tix-card-badges { display: flex; gap: 8px; align-items: center; }

/* Status & Priority Badges */
.tix-pri, .tix-status {
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    padding: 3px 10px;
}
.tix-pri-high   { background: #fff3f3; color: #c0392b; border: 1px solid #f5c6c6; }
.tix-pri-normal { background: #f0f6ff; color: #2980b9; border: 1px solid #c5ddf5; }
.tix-pri-low    { background: #f5f5f5; color: #777; border: 1px solid #e5e5e5; }

.tix-status-open   { background: #fffbf0; color: #d4940a; border: 1px solid #f5e4a0; }
.tix-status-closed { background: #f0faf2; color: #27ae60; border: 1px solid #b4dfc0; }

.tix-card-title {
    display: block;
    font-size: 14.5px;
    font-weight: 600;
    color: #111;
    text-decoration: none;
    margin-bottom: 8px;
    line-height: 1.4;
    transition: color 0.15s;
}
.tix-card-title:hover { color: #cda873; }

.tix-card-snippet {
    font-size: 12px;
    color: #888;
    line-height: 1.55;
    margin: 0 0 14px;
}

.tix-card-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 12px;
    border-top: 1px solid #f5f5f5;
}
.tix-card-info { display: flex; gap: 18px; }
.tix-card-date, .tix-card-handler {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    color: #999;
}
.tix-card-date svg, .tix-card-handler svg {
    width: 12px;
    height: 12px;
    stroke: #bbb;
    fill: none;
    stroke-width: 1.8;
    flex-shrink: 0;
}
.tix-detail-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: #555;
    text-decoration: none;
    transition: color 0.15s;
}
.tix-detail-link svg {
    width: 12px;
    height: 12px;
    stroke: currentColor;
    fill: none;
    stroke-width: 2;
}
.tix-detail-link:hover { color: #111; }

/* ===== EMPTY STATE ===== */
.tix-empty {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border: 1px solid #ebebeb;
}
.tix-empty svg {
    width: 40px;
    height: 40px;
    stroke: #ccc;
    fill: none;
    stroke-width: 1.5;
    margin-bottom: 12px;
}
.tix-empty p {
    color: #aaa;
    font-size: 14px;
    margin: 0;
}

/* ===== PAGINATION ===== */
.tix-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 0;
    margin-top: 8px;
}
.tix-page-count { font-size: 11.5px; color: #aaa; }
.tix-page-nav { display: flex; gap: 6px; }
.tix-page-btn {
    padding: 6px 14px;
    background: white;
    border: 1px solid #e0e0e0;
    font-size: 11.5px;
    color: #555;
    cursor: pointer;
    font-family: inherit;
    border-radius: 2px;
    transition: all 0.15s;
}
.tix-page-btn.active {
    background: #111;
    color: white;
    border-color: #111;
}
.tix-page-btn:disabled { opacity: 0.4; cursor: default; }

/* ===== SIDEBAR CARDS ===== */
.tix-sidebar-card {
    background: white;
    border: 1px solid #ebebeb;
    padding: 24px;
    margin-bottom: 16px;
}
.tix-sc-label {
    font-size: 9px;
    font-weight: 700;
    letter-spacing: 1.5px;
    color: #bbb;
    margin-bottom: 10px;
}
.tix-sc-title {
    font-family: var(--font-serif, Georgia, serif);
    font-size: 17px;
    font-weight: 400;
    color: #111;
    margin: 0 0 10px;
    line-height: 1.4;
}
.tix-sc-desc {
    font-size: 11.5px;
    color: #888;
    line-height: 1.6;
    margin: 0 0 20px;
}

/* Contact list */
.tix-contact-list { display: flex; flex-direction: column; gap: 0; }
.tix-contact-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 0;
}
.tix-divider { height: 1px; background: #f0f0f0; }
.tix-contact-icon {
    width: 32px;
    height: 32px;
    background: #f7f5f2;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.tix-contact-icon svg {
    width: 14px;
    height: 14px;
    stroke: #9a7c5a;
    fill: none;
    stroke-width: 1.8;
}
.tix-contact-icon.tix-icon-green {
    background: #e8f8ee;
    position: relative;
}
.tix-dot {
    width: 8px;
    height: 8px;
    background: #27ae60;
    border-radius: 50%;
}
.tix-contact-label {
    font-size: 8.5px;
    font-weight: 700;
    letter-spacing: 1px;
    color: #bbb;
    margin-bottom: 3px;
}
.tix-contact-value {
    font-size: 12.5px;
    font-weight: 600;
    color: #222;
}
.tix-link-underline {
    font-weight: 500;
    text-decoration: underline;
    cursor: pointer;
    color: #333 !important;
    font-size: 11.5px;
}

/* Commit list */
.tix-commit-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.tix-commit-list li {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    font-size: 11.5px;
    color: #666;
    line-height: 1.5;
}
.tix-commit-list li svg {
    width: 13px;
    height: 13px;
    stroke: #27ae60;
    fill: none;
    stroke-width: 2.5;
    flex-shrink: 0;
    margin-top: 1px;
}
.tix-commit-list li strong { color: #333; }

/* FAQ */
.tix-faq-list {
    list-style: none;
    padding: 0;
    margin: 0 0 16px;
    display: flex;
    flex-direction: column;
    gap: 0;
}
.tix-faq-list li { border-bottom: 1px solid #f0f0f0; }
.tix-faq-btn {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    padding: 12px 0;
    background: none;
    border: none;
    font-size: 11.5px;
    color: #444;
    cursor: pointer;
    text-align: left;
    font-family: inherit;
    gap: 8px;
}
.tix-faq-btn svg {
    width: 13px;
    height: 13px;
    stroke: #bbb;
    fill: none;
    stroke-width: 2;
    flex-shrink: 0;
    transition: transform 0.2s;
}
.tix-faq-list li.open .tix-faq-btn svg { transform: rotate(180deg); }
.tix-faq-ans {
    font-size: 11px;
    color: #888;
    line-height: 1.6;
    padding: 0 0 12px;
    display: none;
}
.tix-faq-list li.open .tix-faq-ans { display: block; }
.tix-faq-link {
    display: block;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.8px;
    color: #888;
    text-decoration: none;
    padding-top: 4px;
    transition: color 0.15s;
}
.tix-faq-link:hover { color: #111; }

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .tix-layout { grid-template-columns: 1fr; }
    .tix-right { order: -1; }
    .tix-toolbar { flex-wrap: wrap; }
    .tix-filters { flex-wrap: wrap; }
}
@media (max-width: 600px) {
    .tix-page-header { flex-direction: column; }
    .tix-card-info { flex-direction: column; gap: 6px; }
}
</style>

<script>
// Filter buttons
document.querySelectorAll('.tix-filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tix-filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const filter = this.dataset.filter;
        document.querySelectorAll('.tix-card').forEach(card => {
            if (filter === 'all' || card.dataset.status === filter) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});

// Search
document.getElementById('tixSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.tix-card').forEach(card => {
        const text = card.textContent.toLowerCase();
        card.style.display = text.includes(q) ? '' : 'none';
    });
});
</script>

@endsection
