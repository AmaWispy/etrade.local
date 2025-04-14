<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class OsmPolygon extends Field
{
    protected string $view = 'forms.components.osm-polygon';

    protected float | \Closure | null $lat = null;
    protected float | \Closure | null $lng = null;

    public function initialLat(float | \Closure | null $lat): static
    {
        $this->lat = $lat;
        return $this;
    }

    public function getInitialLat(): ?float
    {
        return $this->evaluate($this->lat);
    }

    public function initialLng(float | \Closure | null $lng): static
    {
        $this->lng = $lng;
        return $this;
    }

    public function getInitialLng(): ?float
    {
        return $this->evaluate($this->lng);
    }
}