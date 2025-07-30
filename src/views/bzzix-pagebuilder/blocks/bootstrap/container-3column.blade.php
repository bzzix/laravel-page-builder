@php
    $block = [
        'id' => 'bootstrap-3-cols',
        'label' => '<svg width="20" height="20" viewBox="0 0 24 24"><rect width="24" height="24" fill="#17a2b8"/><text x="4" y="16" fill="#fff" font-size="10">Cols</text></svg> 3 أعمدة داخل كونتينر',
        'category' => 'bootstrap',
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
                
                if (typeTrait) {
                    const newClass = typeTrait.get('value');
                    if (container) {
                        container.className = newClass;
                    }
                }
            }
        JS
    ];
@endphp

<div class="container">
    <div class="row">
        <div class="col-12 col-md-4">عمود 1</div>
        <div class="col-12 col-md-4">عمود 2</div>
        <div class="col-12 col-md-4">عمود 3</div>
    </div>
</div>

