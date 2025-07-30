@php
    $block = [
        'id' => 'bootstrap-2-cols-md-4-8',
        'label' => '<svg width="20" height="20" viewBox="0 0 24 24"><rect width="24" height="24" fill="#ffc107"/><text x="2" y="16" fill="#000" font-size="9">md 4/8</text></svg> عمود 4 و 8 متجاوب',
        'category' => 'bootstrap',
        'content' => '
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-4">عمود 4</div>
                    <div class="col-12 col-md-8">عمود 8</div>
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
        <div class="col-12 col-md-4">عمود 4</div>
        <div class="col-12 col-md-8">عمود 8</div>
    </div>
</div>
