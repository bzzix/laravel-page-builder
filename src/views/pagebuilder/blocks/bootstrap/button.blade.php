@php
    $block = [
        'id' => 'bootstrap-button',
        'label' => '<svg width="20" height="20" viewBox="0 0 20 20"><rect width="20" height="20" fill="#007bff"/></svg> زر Bootstrap',
        'category' => 'bootstrap',
        'traits' => [
            ['type' => 'text', 'label' => 'النص', 'name' => 'inner-text']
        ],
        'script' => <<<JS
            function (block) {
                const el = block.firstChild;
                const traits = block.model.get('traits');
                const textTrait = traits.find(t => t.name === 'inner-text');
                if (textTrait) {
                    el.innerHTML = textTrait.get('value');
                }
            }
        JS
    ];
@endphp

<button type="button" class="btn btn-primary">زر Bootstrap</button>
