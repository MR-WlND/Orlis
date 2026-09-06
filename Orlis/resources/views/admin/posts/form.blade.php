@extends('layouts.admin')

@section('title', isset($post) ? 'Sửa bài viết' : 'Tạo bài viết mới')

@section('page-style')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>

@endsection

@section('content')

@if ($errors->any())
    <div style="background: #fde8e8; color: #c53030; padding: 10px 16px; margin-bottom: 24px; border-radius: 4px; font-size: 13px; max-width: 1100px; margin-left: auto; margin-right: auto;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ isset($post) ? route('admin.posts.update', $post->id) : route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" id="postForm">
    @csrf
    @if(isset($post)) @method('PUT') @endif
    <input type="hidden" name="status" id="formStatus" value="{{ old('status', $post->status ?? 'published') }}">

    <div class="page-header">
        <h2 class="page-title">{{ isset($post) ? 'Sửa bài viết' : 'Tạo bài viết mới' }}</h2>
        <div class="header-actions">
            <a href="{{ route('admin.posts.index') }}" class="btn-cancel">HỦY</a>
            <button type="button" class="btn-draft" onclick="submitAs('draft')">LƯU NHÁP</button>
            <button type="button" class="btn-publish" onclick="submitAs('published')">XUẤT BẢN</button>
        </div>
    </div>

    <div class="post-form-container">
        <!-- Main Content -->
        <div class="post-main">
            <div class="card main-card">
                <input type="text" name="title" class="title-input" value="{{ old('title', $post->title ?? '') }}" placeholder="Nhập tiêu đề bài viết..." required>
                <div class="title-divider"></div>
                
                <textarea name="excerpt" class="form-control" style="font-family: var(--font-sans); font-size: 14px; margin-bottom: 20px; resize: vertical; min-height: 60px;" placeholder="Tóm tắt bài viết (Sa-pô)... Dẫn nhập ngắn 2-3 câu hiển thị ngoài Frontend">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                
                <textarea name="content" id="editor" required>{{ old('content', $post->content ?? '') }}</textarea>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="post-sidebar">
            <!-- Cài đặt xuất bản -->
            <div class="card sidebar-card">
                <h3 class="sidebar-title">CÀI ĐẶT XUẤT BẢN</h3>
                
                <div class="form-group">
                    <label class="form-label">TRẠNG THÁI</label>
                    <select class="form-control form-select" onchange="document.getElementById('formStatus').value = this.value">
                        <option value="published" {{ (old('status', $post->status ?? 'published') == 'published') ? 'selected' : '' }}>Xuất bản</option>
                        <option value="draft" {{ (old('status', $post->status ?? 'published') == 'draft') ? 'selected' : '' }}>Bản nháp</option>
                        <option value="archived" {{ (old('status', $post->status ?? 'published') == 'archived') ? 'selected' : '' }}>Lưu trữ</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">MẢNG (DEPARTMENT)</label>
                    <select class="form-control form-select" name="department">
                        <option value="fashion" {{ (old('department', $post->department ?? 'fashion') == 'fashion') ? 'selected' : '' }}>Thời trang</option>
                        <option value="beauty" {{ (old('department', $post->department ?? '') == 'beauty') ? 'selected' : '' }}>Làm đẹp / Nước hoa</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">CHUYÊN MỤC</label>
                    <select class="form-control form-select" name="category_id">
                        <option value="">Chọn chuyên mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $post->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">THẺ (TAGS)</label>
                    <input type="text" name="tags" class="form-control" value="{{ old('tags', $post->tags ?? '') }}" placeholder="VD: #Lookbook, #Spring2026...">
                    <div class="seo-hint">Các thẻ cách nhau bằng dấu phẩy</div>
                </div>
            </div>

            <!-- Ảnh đại diện -->
            <div class="card sidebar-card">
                <h3 class="sidebar-title">ẢNH ĐẠI DIỆN</h3>
                <div class="image-upload-area" id="imagePreviewContainer">
                    <input type="file" name="thumbnail" class="hidden-file-input" accept="image/*" onchange="previewImage(this)">
                    @if(isset($post) && $post->thumbnail)
                        <img src="{{ Storage::url($post->thumbnail) }}" id="imagePreview" style="max-width: 100%; object-fit: cover;">
                    @else
                        <div id="uploadPlaceholder">
                            <div class="upload-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            </div>
                            <div class="upload-text">Kéo thả ảnh hoặc click để tải lên</div>
                        </div>
                        <img src="" id="imagePreview" style="max-width: 100%; display: none; object-fit: cover;">
                    @endif
                </div>
            </div>

            <!-- Cài đặt SEO -->
            <div class="card sidebar-card">
                <h3 class="sidebar-title">CÀI ĐẶT SEO</h3>
                
                <div class="form-group">
                    <label class="form-label">THẺ TIÊU ĐỀ (META TITLE)</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title ?? '') }}" placeholder="Nhập tiêu đề SEO...">
                    <div class="seo-hint">Đề xuất: Dưới 60 ký tự</div>
                </div>

                <div class="form-group">
                    <label class="form-label">MÔ TẢ (META DESCRIPTION)</label>
                    <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $post->meta_description ?? '') }}" placeholder="Nhập mô tả ngắn gọn...">
                    <div class="seo-hint">Đề xuất: Dưới 160 ký tự</div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    CKEDITOR.replace('editor', {
        versionCheck: false,
        toolbar: [
            ['Format'],
            ['Bold', 'Italic'],
            ['JustifyLeft', 'JustifyCenter', 'JustifyBlock'],
            ['NumberedList', 'BulletedList'],
            ['Link'],
            ['Blockquote'],
            ['Image']
        ],
        format_tags: 'p;h2;h3',
        removePlugins: 'elementspath',
        resize_enabled: false,
        contentsCss: [
            'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap',
            'body { font-family: "Castoro", serif; font-size: 16px; color: #000; line-height: 1.6; padding: 0; margin: 0; }'
        ]
    });

    function submitAs(status) {
        document.getElementById('formStatus').value = status;
        document.getElementById('postForm').submit();
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
                var placeholder = document.getElementById('uploadPlaceholder');
                if(placeholder) placeholder.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
