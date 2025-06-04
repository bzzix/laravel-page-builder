@php
    $block = [
        'id' => 'bootstrap-card',
        'label' => '<svg width="20" height="20" viewBox="0 0 24 24"><rect width="24" height="24" fill="#6c757d"/><text x="4" y="16" fill="#fff" font-size="10">Card</text></svg> كارد Bootstrap',
        'category' => 'bootstrap',
        'traits' => [
            ['type' => 'text', 'label' => 'العنوان', 'name' => 'card-title'],
            ['type' => 'text', 'label' => 'الوصف', 'name' => 'card-text'],
        ],
        'script' => <<<JS
            function (block) {
                const el = block.firstChild;
                const traits = block.model.get('traits');
                const titleTrait = traits.find(t => t.name === 'card-title');
                const textTrait = traits.find(t => t.name === 'card-text');
                if (titleTrait && el.querySelector('.card-title')) {
                    el.querySelector('.card-title').innerText = titleTrait.get('value');
                }
                if (textTrait && el.querySelector('.card-text')) {
                    el.querySelector('.card-text').innerText = textTrait.get('value');
                }
            }
        JS
    ];
@endphp

<div class="card" style="width: 18rem;">
    <div class="card-body">
        <h5 class="card-title">عنوان البطاقة</h5>
        <p class="card-text">نص تجريبي لشرح محتوى البطاقة.</p>
        <a href="#" class="btn btn-primary">اذهب لمكان ما</a>
    </div>
</div>
