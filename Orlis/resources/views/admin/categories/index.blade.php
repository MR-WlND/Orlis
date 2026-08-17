@extends('layouts.admin')

@section('title', 'Quản lý Danh mục')

@section('page-style')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 40px;
    }
    .header-text {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .page-title {
        font-family: var(--font-serif);
        font-size: 32px;
        color: var(--text-primary);
        font-weight: 500;
        margin: 0;
    }
    .page-subtitle {
        font-family: var(--font-sans);
        font-size: 13px;
        color: var(--text-secondary);
        margin: 0;
    }
    .btn-add-new {
        display: inline-flex;
        align-items: center;
        background-color: transparent;
        color: var(--text-primary);
        padding: 12px 20px;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        border: 1px solid var(--text-primary);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-add-new span {
        margin-right: 8px;
        font-size: 14px;
        font-weight: 400;
    }
    .btn-add-new:hover { background-color: #333; color: #fff;}

    .table-container {
        background: #fff;
        border: 1px solid var(--border-color);
        padding: 0;
    }
    .luxury-table {
        width: 100%;
        border-collapse: collapse;
    }
    .luxury-table th, .luxury-table td {
        padding: 12px 30px; /* Reduced padding from 20px to 12px */
        text-align: left;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    .luxury-table th {
        font-size: 10px;
        font-weight: 600;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 2px;
        background: #fdfdfd;
        padding: 16px 30px;
    }
    .luxury-table tr:last-child td { border-bottom: none; }
    
    .cat-name-col {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .cat-icon-box {
        width: 40px;
        height: 40px;
        background-color: #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        flex-shrink: 0;
    }
    .cat-icon-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .cat-icon-box svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
        stroke-width: 1.5;
        fill: none;
    }
    .cat-info {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .cat-title {
        font-family: var(--font-serif);
        font-size: 18px;
        color: var(--text-primary);
        font-weight: 500;
    }
    .cat-slug {
        font-size: 11px;
        color: #888;
        margin-top: 2px;
    }
    .cat-count {
        font-size: 12px;
        color: #555;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        border: 1px solid #e0e0e0;
        font-size: 10px;
        color: #555;
        background: #fbfbfb;
        white-space: nowrap;
    }
    .status-badge::before {
        content: '';
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background-color: var(--text-primary);
    }
    .status-badge.inactive {
        color: #999;
    }
    .status-badge.inactive::before {
        background-color: #999;
    }

    .action-links {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: transparent;
        border: 1px solid transparent;
        color: #666;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .action-btn svg {
        width: 16px;
        height: 16px;
        stroke: currentColor;
        fill: none;
        stroke-width: 1.5;
        stroke-linecap: round;
        stroke-linejoin: round;
    }
    .action-btn:hover { background: #f5f5f5; border-color: #ddd; color: var(--text-primary); }
    .action-btn.delete:hover { background: #fff1f0; border-color: #ffccc7; color: #d93025; }



    /* Toggle Tree styles */
    .toggle-tree {
        background: none;
        border: none;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #888;
        border-radius: 4px;
        transition: all 0.2s;
    }
    .toggle-tree:hover {
        background: #eee;
        color: #333;
    }
    .toggle-tree svg {
        width: 16px;
        height: 16px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        transition: transform 0.3s;
    }
    .toggle-tree.expanded svg {
        transform: rotate(90deg);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div class="header-text">
        <h2 class="page-title">Quản lý Danh mục</h2>
        <p class="page-subtitle">Phân loại các bộ sưu tập và dòng sản phẩm xa xỉ của thương hiệu Orlis.</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-add-new">
        <span>+</span> THÊM DANH MỤC MỚI
    </a>
</div>

@if(session('success'))
    <div style="padding: 15px 20px; background: #fff; border: 1px solid #000; color: #000; margin-bottom: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">
        {{ session('success') }}
    </div>
@endif

<div class="table-container">
    <table class="luxury-table" id="categoryTable">
        <thead>
            <tr>
                <th>TÊN DANH MỤC & SLUG</th>
                <th>DANH MỤC GỐC</th>
                <th>SỐ LƯỢNG SP</th>
                <th>TRẠNG THÁI</th>
                <th>HÀNH ĐỘNG</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $cat)
            <!-- Cấp 1 -->
            <tr class="cat-row level-1">
                <td>
                    <div class="cat-name-col">
                        @if($cat->children->count() > 0)
                            <button type="button" class="toggle-tree" onclick="toggleChildren({{ $cat->id }}, this)">
                                <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                            </button>
                        @else
                            <div style="width: 24px;"></div>
                        @endif
                        <div class="cat-icon-box">
                            @if($cat->image)
                                <img src="{{ filter_var($cat->image, FILTER_VALIDATE_URL) ? $cat->image : Storage::url($cat->image) }}" alt="{{ $cat->name }}">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M4 19h16v-9H4v9z"></path><path d="M16 10V6c0-2.21-1.79-4-4-4S8 3.79 8 6v4"></path></svg>
                            @endif
                        </div>
                        <div class="cat-info">
                            <span class="cat-title">{{ $cat->name }}</span>
                            <span class="cat-slug">/{{ $cat->slug }}</span>
                        </div>
                    </div>
                </td>
                <td><span class="cat-count" style="font-weight: 600;">Danh mục gốc</span></td>
                <td>
                    <span class="cat-count">{{ $cat->products()->count() ?? 0 }}</span>
                </td>
                <td>
                    @if(isset($cat->status) && $cat->status == 0)
                        <span class="status-badge inactive">Ẩn</span>
                    @else
                        <span class="status-badge">Hiển thị</span>
                    @endif
                </td>
                <td>
                    <div class="action-links">
                        <a href="{{ route('admin.categories.edit', $cat->id) }}" class="action-btn" title="Chỉnh sửa">
                            <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Xóa">
                                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            
                <!-- Cấp 2 -->
                @foreach($cat->children as $child)
                <tr class="cat-row level-2 child-of-{{ $cat->id }}" style="display: none; background: #fdfdfd;">
                    <td>
                        <div class="cat-name-col" style="padding-left: 39px;">
                            @if($child->children->count() > 0)
                                <button type="button" class="toggle-tree" onclick="toggleChildren({{ $child->id }}, this)">
                                    <svg viewBox="0 0 24 24"><path d="M9 18l6-6-6-6"/></svg>
                                </button>
                            @else
                                <div style="width: 24px; display: flex; justify-content: center; color: #ccc;">-</div>
                            @endif
                            <div class="cat-icon-box" style="width: 32px; height: 32px;">
                                @if($child->image)
                                    <img src="{{ filter_var($child->image, FILTER_VALIDATE_URL) ? $child->image : Storage::url($child->image) }}" alt="{{ $child->name }}">
                                @else
                                    <svg viewBox="0 0 24 24" style="width: 16px; height: 16px;"><path d="M4 19h16v-9H4v9z"></path><path d="M16 10V6c0-2.21-1.79-4-4-4S8 3.79 8 6v4"></path></svg>
                                @endif
                            </div>
                            <div class="cat-info">
                                <span class="cat-title" style="font-size: 15px;">{{ $child->name }}</span>
                                <span class="cat-slug" style="font-size: 10px;">/{{ $child->slug }}</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="cat-count">{{ $cat->name }}</span></td>
                    <td>
                        <span class="cat-count">{{ $child->products()->count() ?? 0 }}</span>
                    </td>
                    <td>
                        @if(isset($child->status) && $child->status == 0)
                            <span class="status-badge inactive">Ẩn</span>
                        @else
                            <span class="status-badge">Hiển thị</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-links">
                            <a href="{{ route('admin.categories.edit', $child->id) }}" class="action-btn" title="Chỉnh sửa">
                                <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $child->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete" title="Xóa">
                                    <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                    <!-- Cấp 3 -->
                    @foreach($child->children as $grandchild)
                    <tr class="cat-row level-3 child-of-{{ $child->id }} grandchild-of-{{ $cat->id }}" style="display: none; background: #fafafa;">
                        <td>
                            <div class="cat-name-col" style="padding-left: 78px;">
                                <div style="width: 24px; display: flex; justify-content: center; color: #ddd;">-</div>
                                <div class="cat-icon-box" style="width: 26px; height: 26px; background: #fff; border: 1px solid #eee;">
                                    @if($grandchild->image)
                                        <img src="{{ filter_var($grandchild->image, FILTER_VALIDATE_URL) ? $grandchild->image : Storage::url($grandchild->image) }}" alt="{{ $grandchild->name }}">
                                    @else
                                        <svg viewBox="0 0 24 24" style="width: 14px; height: 14px; stroke: #999;"><path d="M4 19h16v-9H4v9z"></path><path d="M16 10V6c0-2.21-1.79-4-4-4S8 3.79 8 6v4"></path></svg>
                                    @endif
                                </div>
                                <div class="cat-info">
                                    <span class="cat-title" style="font-size: 13px; color: #555;">{{ $grandchild->name }}</span>
                                    <span class="cat-slug" style="font-size: 10px; color: #aaa;">/{{ $grandchild->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td><span class="cat-count" style="font-size: 11px; color: #888;">{{ $child->name }}</span></td>
                        <td>
                            <span class="cat-count" style="color: #888;">{{ $grandchild->products()->count() ?? 0 }}</span>
                        </td>
                        <td>
                            @if(isset($grandchild->status) && $grandchild->status == 0)
                                <span class="status-badge inactive">Ẩn</span>
                            @else
                                <span class="status-badge">Hiển thị</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-links">
                                <a href="{{ route('admin.categories.edit', $grandchild->id) }}" class="action-btn" title="Chỉnh sửa">
                                    <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $grandchild->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Xóa">
                                        <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>



<script>
    function toggleChildren(parentId, btnEl) {
        const isExpanded = btnEl.classList.contains('expanded');
        
        // Tất cả các dòng con trực tiếp
        const children = document.querySelectorAll('.child-of-' + parentId);
        
        if (isExpanded) {
            // Thu gọn: ẩn con trực tiếp và cả cháu
            children.forEach(child => {
                child.style.display = 'none';
                // Nếu child này là cấp 2, nó có thể có nút bấm đang mở
                const childBtn = child.querySelector('.toggle-tree');
                if(childBtn && childBtn.classList.contains('expanded')) {
                    childBtn.classList.remove('expanded');
                }
            });
            // Ẩn luôn cả cháu
            const grandchildren = document.querySelectorAll('.grandchild-of-' + parentId);
            grandchildren.forEach(gc => {
                gc.style.display = 'none';
            });
            
            btnEl.classList.remove('expanded');
        } else {
            // Mở rộng: chỉ hiện con trực tiếp
            children.forEach(child => {
                if(child.classList.contains('level-2') || child.classList.contains('level-3')) {
                     // Nếu parent là level 2 thì child là level 3. Code này đảm bảo hiện đúng con trực tiếp
                     // classList.contains('child-of-'+parentId) đã lọc chuẩn con trực tiếp
                     child.style.display = 'table-row';
                }
            });
            btnEl.classList.add('expanded');
        }
    }
</script>
@endsection
