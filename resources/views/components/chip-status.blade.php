{{--
  Status chip driven by ApplicationStatus::color() return values.
  Usage: <x-chip-status :status="$app->status->color()" :label="$app->status->label()" />
  Accepted status values: success, warning, danger, info
--}}
@props([
    'status' => 'info',
    'label' => '',
])
<span class="chip-status-{{ $status }}">
    {{ $label }}
</span>
