<x-filament-panels::page class="fi-dashboard-page">
    @if (method_exists($this, 'filtersForm'))
        {{ $this->filtersForm }}
    @endif

    <div style="position: relative;">
        <div style="background-image: url('/img/bg-airport.png');position:absolute;width: 110%;height: 300px;z-index: -1;top: -100px;left: -40px;background-size: cover; background-position: center">.</div>
    </div>

    <x-filament-widgets::widgets
        :columns="$this->getColumns()"
        :data="
            [
                ...(property_exists($this, 'filters') ? ['filters' => $this->filters] : []),
                ...$this->getWidgetData(),
            ]
        "
        :widgets="$this->getVisibleWidgets()"
    />
</x-filament-panels::page>
