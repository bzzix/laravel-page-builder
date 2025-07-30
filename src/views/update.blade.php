<!DOCTYPE html>
<html>
<head>
    <title>{{$page ? 'تعديل: '.$page->title : __('Update Page Builder')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS الأساسي --}}
    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet">
    
  @php
      $plugins = collect(config('bzzix-pagebuilder.plugins', []))
          ->where('enabled', true);

      $pluginStyles = $plugins->pluck('style')->filter()->unique();
      $pluginScripts = $plugins->pluck('script')->filter()->unique();
      $pluginNames = $plugins->pluck('name')->filter()->toArray();

      $pluginOptions = [];
      foreach ($plugins as $plugin) {
          $pluginOptions[$plugin['name']] = $plugin['options'] ?? [];
      }
  @endphp

    {{-- تحميل CSS للبلجنات من config --}}
    @foreach ($pluginStyles as $style)
        <link rel="stylesheet" href="{{ $style }}">
    @endforeach

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { margin: 0; }
        .gjs-pn-panel.gjs-pn-commands {
            display: flex;
            justify-content: space-between;
        }
        .floating-save-btn {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            padding: 15px 25px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        .floating-save-btn:hover {
            background: #218838;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .floating-save-btn:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
        .floating-save-btn i {
            font-size: 16px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .spinning {
            animation: spin 1s linear infinite;
        }
    </style>

  </head>
<body>
    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px;background:#f7f7f7;border-bottom:1px solid #eee">
        <div style="display:flex;gap:10px;align-items:center">
            <div style="display:flex;flex-direction:column;gap:5px">
                <label for="page-title" style="font-size:14px;color:#666">عنوان الصفحة:</label>
                <input type="text" id="page-title" name="title" value="{{ $page->title ?? 'بدون عنوان' }}" 
                    style="padding:8px;border:1px solid #ddd;border-radius:4px;width:300px">
            </div>
            
            <div style="display:flex;flex-direction:column;gap:5px">
                <label for="page-slug" style="font-size:14px;color:#666">الرابط:</label>
                <input type="text" id="page-slug" name="slug" value="{{ $page->slug ?? '' }}" 
                    style="padding:8px;border:1px solid #ddd;border-radius:4px;width:200px" readonly>
            </div>

            <div style="display:flex;flex-direction:column;gap:5px">
                <label for="page-status" style="font-size:14px;color:#666">الحالة:</label>
                <select id="page-status" name="status" style="padding:8px;border:1px solid #ddd;border-radius:4px;width:150px">
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ ($page->status ?? 'draft') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>
    
    <button id="save-page-btn" class="floating-save-btn">
        <i class="fas fa-save"></i>
        <span>{{ __('تحديث الصفحة') }}</span>
    </button>
    <div id="gjs" style="height:calc(100vh - 50px)"></div>

    <script src="https://unpkg.com/grapesjs"></script>

    {{-- Scripts للبلجنات من config --}}
    @foreach ($pluginScripts as $script)
        <script src="{{ $script }}"></script>
    @endforeach

    @php
      $rtlLocales = ['ar', 'he', 'fa', 'ur', 'ps', 'sd']; // اللغات التي تستخدم اتجاه RTL
      $isRtl = in_array(app()->getLocale(), $rtlLocales);
    @endphp

    <script>

      const pluginNames = @json($pluginNames);
      const pluginOptions = @json($pluginOptions);

      const editor = grapesjs.init({
          container: '#gjs',
          fromElement: false,
          height: '100vh',
          storageManager: false,
          // تحميل محتوى الصفحة المحفوظ
          components: {!! json_encode($page->html) !!},
          style: {!! json_encode($page->css) !!},
          canvas: {
              styles: {!! json_encode(config('bzzix-pagebuilder.styles', [])) !!},
              scripts: {!! json_encode(config('bzzix-pagebuilder.scripts', [])) !!}
          },
          plugins: pluginNames,
          pluginsOpts: pluginOptions,
      });
        
    @if($isRtl)
        editor.setStyle(`
            body {
                direction: rtl;
                text-align: right !important;
            }
            .gjs-title, gjs-pn-panel {
                text-align: right !important;
            }
        `);
    @endif
      
    
    editor.Panels.getButton('views', 'open-blocks').set('active', true);

      //////////
      const blocks = @json($blocks);

      blocks.forEach(block => {
          editor.BlockManager.add(block.id, {
              label: block.label,
              category: block.category,
              content: block.content,
              traits: block.traits,
              script: block.script
          });
      });

      // زر الحفظ
      document.getElementById('save-page-btn').addEventListener('click', async function() {
        const btn = this;
        const icon = btn.querySelector('i');
        const span = btn.querySelector('span');
        
        try {
            // تعطيل الزر وإظهار أيقونة التحميل
            btn.disabled = true;
            icon.classList.remove('fa-save');
            icon.classList.add('fa-spinner', 'spinning');
            span.textContent = 'جاري الحفظ...';

            const title = document.getElementById('page-title').value;
            const status = document.getElementById('page-status').value;
            const html = editor.getHtml();
            const css = editor.getCss();
            const components = editor.getComponents();

            const response = await fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    title,
                    status,
                    html,
                    css,
                    components
                })
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            } else {
                throw new Error(data.message || 'حدث خطأ غير معروف');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ أثناء الحفظ: ' + error.message);
        } finally {
            // إعادة الزر لحالته الأصلية
            setTimeout(() => {
                btn.disabled = false;
                icon.classList.remove('fa-spinner', 'spinning');
                icon.classList.add('fa-save');
                span.textContent = 'تحديث الصفحة';
            }, 1000);
        }
      });
    </script>
</body>
</html>