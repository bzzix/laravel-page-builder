@php
    $block = [
        'id' => 'bootstrap-2-cols',
        'label' => '<svg width="20" height="20" viewBox="0 0 24 24"><rect width="24" height="24" fill="#20c997"/><text x="4" y="16" fill="#fff" font-size="10">2 Cols</text></svg> عمودين داخل كونتينر',
        'category' => 'bootstrap',
        'content' => '
            <div class="container">
                <div class="row">
                    <div class="col">عمود 1</div>
                    <div class="col">عمود 2</div>
                </div>
            </div>
        ',
        'traits' => [
            [
                'type' => 'select',
                'label' => 'نوع الكونتينر',
                'name' => 'container-type',
                'options' => [
                    ['value' => 'container', 'name' => 'عادي'],
                    ['value' => 'container-fluid', 'name' => 'فل'],
                ]
            ],
        ],
        'script' => <<<JS
            function (block) {
                const container = block.querySelector('.container, .container-fluid');
                const traits = block.model.get('traits');
                const typeTrait = traits.find(t => t.name === 'container-type');
                
                if (typeTrait && container) {
                    container.className = typeTrait.get('value');
                }
            }
        JS
    ];
@endphp

<div class="container">
    <div class="row">
        <div class="col-12 col-md-6">عمود 1</div>
        <div class="col-12 col-md-6">عمود 2</div>
    </div>
</div>
