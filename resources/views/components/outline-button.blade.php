@props(['title' => '', 'icon' => '', 'class' => 'px-4 py-2.5'])
<button class="border-2 border-[#0000001A] {{ $class }} flex items-center space-x-4 rounded-lg">
    @if ($icon)
        <div>
            {!! $icon !!}
        </div>
    @endif
    <p class="font-[Arial] font-normal text-[14px] leading-[20px] tracking-[0px]">
        {{ $title }}
    </p>
</button>
