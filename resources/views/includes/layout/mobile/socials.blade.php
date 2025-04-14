@foreach (App\Models\Settings::all() as $info )
    @if ($info->key === 'company-telephone')
        <div >
            <h1 class="font-medium text-2xl border-t-2 border-neutral-200 pt-2">{{ str_replace('-',' ', $info->content) }}</h1>
            @include('includes.links.messangers')
        </div>
    @endif
    <div class="text-sm !gap-0">
        @if ($info->key === 'address')
            <p class="text-neutral-500">{{ $info->content }}</p>
        @endif
        <div class="!flex w-full">
            @if ($info->key === 'working-hours')
                <p class="text-neutral-500"><span>{{ (__('template.monday_saturday')) }}: </span><span>{{ $info->content }}</span></p>
            @endif
        </div>
    </div>
@endforeach
<div class="mb-20 h-6">
    @include('includes.links.media')
</div>