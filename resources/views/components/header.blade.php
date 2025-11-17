<div class="app-page-title">
    <div class="row">
        <div class="col-2"
            style="display: flex; align-items: center; justify-content: center;">
            <a href="{{ url()->previous() }}"
                style="display: inline-flex; align-items: center; justify-content: center; width: 48px; height: 48px; border-radius: 8px; background-color: white; box-shadow: 0 2px 6px rgba(0,0,0,0.2); text-decoration: none;">
                <i class="pe-7s-left-arrow icon-gradient bg-mean-fruit" style="font-size: 24px; color: black;"></i>
            </a>
        </div>

        <div class="col-10" style="">
            <div class="page-title-heading">
                <div>
                    {{$slot}}
                </div>
            </div>
        </div>
    </div>
</div>