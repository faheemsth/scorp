<style>
    .card-custom {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card-custom .card-header {
        background-color: #007bff;
        color: white;
        border-bottom: 1px solid #007bff;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        font-weight: bold;
    }

    .list-group-item-custom {
        border: none;
        border-bottom: 1px solid #e9ecef;
        padding: 10px 15px;
        transition: background-color 0.3s, color 0.3s;
    }

    .list-group-item-custom:hover,
    .list-group-item-custom.active {
        background-color: #007bff;
        color: white;
    }

    .list-group-item-custom .float-end i {
        transition: transform 0.3s;
    }

    .list-group-item-custom:hover .float-end i,
    .list-group-item-custom.active .float-end i {
        transform: translateX(5px);
    }

    .sticky-top {
        top: 30px;
    }

    .StepActivity {
        position: relative;
        padding-left: 0px;
        list-style: none;
    }

    .StepActivity::before {
        display: inline-block;
        content: '';
        position: absolute;
        top: 0;
        left: -13px;
        width: 10px;
        height: 100%;
        border-left: 5px solid cornflowerblue;
    }

    .StepActivity-item {
        position: relative;
        counter-increment: list;
    }

    .StepActivity-item:not(:last-child) {
        padding-bottom: 20px;
    }

    .StepActivity-item::before {
        display: inline-block;
        content: '';
        position: absolute;
        left: 100px;
        height: 100%;
        width: 10px;
    }

    .StepActivity-item::after {
        content: '';
        display: inline-block;
        position: absolute;
        top: -1px;
        left: -21px;
        width: 20px;
        height: 20px;
        background-size: cover;
        border: 2px solid #CCC;
        border-radius: 50%;
        background-color: #FFF;

        @if (\Auth::user()->avatar == null || \Auth::user()->avatar == '')
            background-image: url('{{ asset('assets/images/user/default.jpg') }}');
        @else
            background-image: url('{{ asset('storage/uploads/avatar') . '/' . Auth::user()->avatar }}');
        @endif
    }
</style>
<div class="sticky-top">
    <div class="list-group list-group-flush" id="useradd-sidenav">
        <ul class="StepActivity">

            <li class="card card-custom py-3 StepActivity-item is-done">
                <div class="bold time">
                </div>
                <div class="bold" style="text-align: left; margin-left: 80px;">
                    <p class="bold" style="margin-bottom: 0rem; color: #000000;">
                        Notes created</p>
                    <p class="m-0">Noted created successfully</p>
                    <span class="text-muted text-sm" style="cursor: pointer;"
                        onclick="openSidebar('/user/employee/9645/show')"><i class="step__icon fa fa-user me-2"
                            aria-hidden="true"></i>Fatima Maqbool</span>
                </div>
            </li>
        </ul>
    </div>
</div>
