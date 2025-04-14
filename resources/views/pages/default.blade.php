<x-app-layout>
    @section('title', $page->title)

    <!-- BREADCRUMB AREA START -->
        @include('includes.layout.bread-crump')
    <!-- BREADCRUMB AREA END -->

    <!-- ABOUT US AREA START -->
    <div class="ltn__about-us-area pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 align-self-center">
                    <div class="about-us-info-wrap">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ABOUT US AREA END -->

</x-app-layout>