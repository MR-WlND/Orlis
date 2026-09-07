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

    <div class="edit-grid">
        <!-- Main Content -->
        <div class="form-card" style="padding: 0;">
            <div style="padding: 30px 40px;">
                <input type="text" name="title" class="title-input" value="{{ old('title', $post->title ?? '') }}" placeholder="Nhập tiêu đề bài viết..." required style="width: 100%; border: none; font-family: var(--font-serif); font-size: 26px; padding: 0 0 10px 0; outline: none; color: #000; box-sizing: border-box;">
                <div style="height: 1px; background: #eee; margin: 0 0 20px 0;"></div>
                
                <textarea name="excerpt" class="form-control" style="font-family: var(--font-sans); font-size: 14px; margin-bottom: 20px; resize: vertical; min-height: 60px; width: 100%; border: 1px solid var(--border-color); padding: 12px; outline: none;" placeholder="Tóm tắt bài viết (Sa-pô)... Dẫn nhập ngắn 2-3 câu hiển thị ngoài Frontend">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                
                <textarea name="content" id="editor" required>{{ old('content', $post->content ?? '') }}</textarea>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="right-col">
            <!-- Cài đặt xuất bản -->
            <div class="form-card" style="padding: 24px;">
                <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">CÀI ĐẶT XUẤT BẢN</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px; letter-spacing: 0.5px;">TRẠNG THÁI</label>
                    <select class="form-control" onchange="document.getElementById('formStatus').value = this.value" style="width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #e0e0e0; font-family: var(--font-serif); font-size: 14px; color: #000; background: transparent; outline: none; appearance: none; border-radius: 0;">
                        <option value="published" {{ (old('status', $post->status ?? 'published') == 'published') ? 'selected' : '' }}>Xuất bản</option>
                        <option value="draft" {{ (old('status', $post->status ?? 'published') == 'draft') ? 'selected' : '' }}>Bản nháp</option>
                        <option value="archived" {{ (old('status', $post->status ?? 'published') == 'archived') ? 'selected' : '' }}>Lưu trữ</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px; letter-spacing: 0.5px;">MẢNG (DEPARTMENT)</label>
                    <select class="form-control" name="department" style="width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #e0e0e0; font-family: var(--font-serif); font-size: 14px; color: #000; background: transparent; outline: none; appearance: none; border-radius: 0;">
                        <option value="fashion" {{ (old('department', $post->department ?? 'fashion') == 'fashion') ? 'selected' : '' }}>Thời trang</option>
                        <option value="beauty" {{ (old('department', $post->department ?? '') == 'beauty') ? 'selected' : '' }}>Làm đẹp / Nước hoa</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px; letter-spacing: 0.5px;">CHUYÊN MỤC</label>
                    <select class="form-control" name="category_id" style="width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #e0e0e0; font-family: var(--font-serif); font-size: 14px; color: #000; background: transparent; outline: none; appearance: none; border-radius: 0;">
                        <option value="">Chọn chuyên mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('category_id', $post->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px; letter-spacing: 0.5px;">THẺ (TAGS)</label>
                    <input type="text" name="tags" class="form-control" value="{{ old('tags', $post->tags ?? '') }}" placeholder="VD: #Lookbook, #Spring2026..." style="width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #e0e0e0; font-family: var(--font-serif); font-size: 14px; color: #000; background: transparent; outline: none; border-radius: 0;">
                    <div style="font-size: 10px; color: #aaa; margin-top: 4px;">Các thẻ cách nhau bằng dấu phẩy</div>
                </div>
            </div>

            <!-- Ảnh đại diện -->
            <div class="form-card" style="padding: 24px;">
                <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">ẢNH ĐẠI DIỆN</h3>
                <div style="position: relative; width: 100%; aspect-ratio: 4/3; background: #fbfbfb; border: 1px dashed #ddd; display: flex; align-items: center; justify-content: center; overflow: hidden; cursor: pointer;" onclick="document.getElementById('thumbnailInput').click()">
                    <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" onchange="previewImage(this)" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    @if(isset($post) && $post->thumbnail)
                        <img src="{{ Storage::url($post->thumbnail) }}" id="imagePreview" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div id="uploadPlaceholder" style="text-align: center; color: #999;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 8px;"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            <div style="font-size: 11px;">Tải ảnh lên</div>
                        </div>
                        <img src="" id="imagePreview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                    @endif
                </div>
            </div>

            <!-- Cài đặt SEO -->
            <div class="form-card" style="padding: 24px;">
                <h3 style="font-family: var(--font-sans); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #000; margin: 0 0 16px 0; padding-bottom: 12px; border-bottom: 1px solid #eee;">CÀI ĐẶT SEO</h3>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px; letter-spacing: 0.5px;">THẺ TIÊU ĐỀ (META TITLE)</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $post->meta_title ?? '') }}" placeholder="Nhập tiêu đề SEO..." style="width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #e0e0e0; font-family: var(--font-serif); font-size: 14px; color: #000; background: transparent; outline: none; border-radius: 0;">
                    <div style="font-size: 10px; color: #aaa; margin-top: 4px;">Đề xuất: Dưới 60 ký tự</div>
                </div>

                <div class="form-group">
                    <label style="display: block; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #888; margin-bottom: 6px; letter-spacing: 0.5px;">MÔ TẢ (META DESCRIPTION)</label>
                    <input type="text" name="meta_description" class="form-control" value="{{ old('meta_description', $post->meta_description ?? '') }}" placeholder="Nhập mô tả ngắn gọn..." style="width: 100%; padding: 8px 0; border: none; border-bottom: 1px solid #e0e0e0; font-family: var(--font-serif); font-size: 14px; color: #000; background: transparent; outline: none; border-radius: 0;">
                    <div style="font-size: 10px; color: #aaa; margin-top: 4px;">Đề xuất: Dưới 160 ký tự</div>
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
