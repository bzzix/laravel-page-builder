<!DOCTYPE html>
<html>
<head>
    <title>Page Builder</title>
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
    <div id="gjs" style="height:100vh;"></div>

    <script src="https://unpkg.com/grapesjs"></script>

    {{-- Scripts للبلجنات من config --}}
    @foreach ($pluginScripts as $script)
        <script src="{{ $script }}"></script>
    @endforeach

    <script>

      const pluginNames = @json($pluginNames);
      const pluginOptions = @json($pluginOptions);

      const editor = grapesjs.init({
          container: '#gjs',
          fromElement: false,
          height: '100vh',
          storageManager: false,
          components: '<h1>Hello Page Builder</h1>',
          canvas: {
              styles: {!! json_encode(config('bzzix-pagebuilder.styles', [])) !!},
              scripts: {!! json_encode(config('bzzix-pagebuilder.scripts', [])) !!}
          },
          plugins: pluginNames,
          pluginsOpts: pluginOptions,
      });

      editor.Commands.add('save-page', {
        run(editor) {
          const html = editor.getHtml();
          const css = editor.getCss();
          const json = editor.getComponents();

          fetch('/api/pages', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
              html: html,
              css: css,
              components: json
            })
          }).then(res => res.json()).then(data => {
            alert('تم الحفظ بنجاح');
          });
        }
      });

    </script>
</body>
</html>

