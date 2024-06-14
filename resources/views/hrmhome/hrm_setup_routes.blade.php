<div class="card sticky-top" style="top: 30px; border-radius: 15px; border: 1px solid #e9ecef; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <div class="list-group list-group-flush" id="useradd-sidenav">

        <a href="{{ url('hrm-leaves') }}" class="{{ Request::segment(1) == 'hrm-leaves' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'leavetype.index' ? 'active' : '')}}">
            <div>
                <i class="ti ti-calendar mr-2"></i>
                <span>{{__('Leave')}}</span>
            </div>
        </a>

        <a href="{{ url('hrm-jobs') }}" class="{{ Request::segment(1) == 'hrm-jobs' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'document.index' ? 'active' : '')}}">
            <div>
                <i class="ti ti-files mr-2"></i>
                <span>{{__('Jobs')}}</span>
            </div>
        </a>

        <a href="{{ url('hrm-payslip') }}" class="{{ Request::segment(1) == 'hrm-payslip' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'payslip.index' ? 'active' : '')}}">
            <div>
                <i class="ti ti-receipt mr-2"></i>
                <span>{{__('PaySlip')}}</span>
            </div>
        </a>

        <a href="{{ route('document.index') }}" class="{{ Request::segment(1) == 'hrm-home' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'attendance.index' ? 'active' : '')}}">
            <div>
                <i class="fa-solid fa-clock mr-2"></i>
                <span>{{__('Attendance')}}</span>
            </div>
        </a>

        <a href="{{ url('hrm-indicator') }}" class="{{ Request::segment(1) == 'hrm-indicator' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'indicator.index' ? 'active' : '')}}">
            <div>
                <i class="fa-solid fa-question mr-2"></i>
                <span>{{__('Indicator')}}</span>
            </div>
        </a>

        <a href="{{ url('hrm-appraisal') }}" class="{{ Request::segment(1) == 'hrm-appraisal' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'appraisal.index' ? 'active' : '')}}">
            <div>
                <i class="ti ti-star mr-2"></i>
                <span>{{__('Appraisal')}}</span>
            </div>
        </a>
    </div>
</div>
