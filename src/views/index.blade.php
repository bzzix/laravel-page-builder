<!DOCTYPE html>
<html>
<head>
    <title>Page Builder</title>
    <link href="https://unpkg.com/grapesjs/dist/css/grapes.min.css" rel="stylesheet">
</head>
<body>
    <div id="gjs" style="height:100vh;"></div>

    <script src="https://unpkg.com/grapesjs"></script>
    <script>
        grapesjs.init({
            container: '#gjs',
            components: '<h1>Hello Page Builder</h1>',
        });
    </script>
</body>
</html>
