@php
    $block = [
        'id' => 'bootstrap-button',
        'label' => '<svg width="20" height="20" viewBox="0 0 20 20"><rect width="20" height="20" fill="#007bff"/></svg> زر Bootstrap',
        'category' => 'bootstrap',
        'content' => [
            'type' => 'bootstrap-button',
            'content' => 'زر Bootstrap',
            'attributes' => [
                'class' => 'btn btn-primary'
            ]
        ]
    ];
@endphp

<script>
    const customBlocks = @json([$block]);
</script>
