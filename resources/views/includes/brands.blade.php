<!-- BRAND LOGO AREA START -->
<div class="ltn__brand-logo-area  ltn__brand-logo-1 section-bg-1 pt-35 pb-35 plr--5">
    <div class="container-fluid">
        <div class="row ltn__brand-logo-active">
            @foreach($brands as $brand)
                <div class="col-lg-12">
                    <div class="ltn__brand-logo-item">
                        <img src="{{$brand->getImage()}}" alt="{{$brand->name}}">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- BRAND LOGO AREA END -->