<?php
use SmallRuralDog\Admin\Components\SelectOption;

function getDevicesOptions($htmlOptions = true)
{
    return collect(config('auth.guards'))->filter(function ($item, $key) {
        return $item['driver'] === 'sanctum' && $key !== 'sanctum';
    })->map(function ($item) use ($htmlOptions) {
        $label = $item['name'] ?? $item['provider'];
        return $htmlOptions ? SelectOption::make($item['provider'], $label) : ['value' => $item['provider'], 'label' => $label];
    })->toArray();
}
