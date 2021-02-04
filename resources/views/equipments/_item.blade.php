<div class="">
    <a href="{{ route('equipments.show', $equipment) }}" class="@if($equipment->isLine()) link-danger @endif">{{ $equipment->name }}</a>
    <div class="ps-4">
        @foreach($equipment->children ?? [] as $childEquipment)
            @include('equipments._item', ['equipment' => $childEquipment])
        @endforeach
    </div>
</div>
