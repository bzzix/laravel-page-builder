<!DOCTYPE html>
<html>
<head>
    <title>{{__('Page Builder')}}</title>
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

    <style>
        body { margin: 0; }
        .gjs-pn-panel.gjs-pn-commands {
            display: flex;
            justify-content: space-between;
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

        <button id="save-page-btn" style="padding:8px 18px;background:#28a745;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:16px;">
            {{ __('حفظ الصفحة') }}
        </button>
    </div>
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
          components: '<h1>{{__('Hello Page Builder')}}</h1>',
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


    //   // تحديث الـ slug تلقائياً عند تغيير العنوان
    //   document.getElementById('page-title').addEventListener('input', function(e) {
    //       const title = e.target.value;
    //       const slug = title
    //           .toLowerCase()
    //           .replace(/ /g, '-')
    //           .replace(/[^\w-]+/g, '')
    //           .replace(/--+/g, '-')
    //           .replace(/^-+/, '')
    //           .replace(/-+$/, '');
    //       document.getElementById('page-slug').value = slug;
    //   });

      editor.Commands.add('save-page', {
        run(editor) {
          const title = document.getElementById('page-title').value;
          const status = document.getElementById('page-status').value;
          const html = editor.getHtml();
          const css = editor.getCss();
          const components = editor.getComponents();

          fetch("{{ config('bzzix-pagebuilder.create_route') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
              title,
              status,
              html,
              css,
              components
            })
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  alert(data.message);
                  if (data.redirect) {
                      window.location.href = data.redirect;
                  }
              } else {
                  alert('حدث خطأ: ' + data.message);
              }
          })
          .catch(error => {
              console.error('Error:', error);
              alert('حدث خطأ أثناء حفظ الصفحة');
          });
        }
      });

      // زر الحفظ
      document.getElementById('save-page-btn').addEventListener('click', function() {
        editor.runCommand('save-page');
      });
    </script>
</body>
</html>