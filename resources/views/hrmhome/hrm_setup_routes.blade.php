<div class="card sticky-top" style="top: 30px; border-radius: 15px; border: 1px solid #e9ecef; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
    <div class="list-group list-group-flush" id="useradd-sidenav">

        <a href="{{ url('hrm-leaves') . (isset($_GET['emp_id']) ? '?emp_id=' . $_GET['emp_id'] : '')  }}" class="{{ Request::segment(1) == 'hrm-leaves' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'leavetype.index' ? 'active' : '')}}">
            <div>
                <i class="ti ti-calendar mr-2"></i>
                <span>{{__('Leave')}}</span>
            </div>
        </a>

        <a href="{{ url('hrm-jobs') . (isset($_GET['emp_id']) ? '?emp_id=' . $_GET['emp_id'] : '')  }}" class="{{ Request::segment(1) == 'hrm-jobs' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'document.index' ? 'active' : '')}}">
            <div>
                <i class="ti ti-files mr-2"></i>
                <span>{{__('Jobs')}}</span>
            </div>
        </a>

        <a href="{{ url('hrm-payslip') . (isset($_GET['emp_id']) ? '?emp_id=' . $_GET['emp_id'] : '')  }}" class="{{ Request::segment(1) == 'hrm-payslip' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'payslip.index' ? 'active' : '')}}">
            <div>
                <i class="ti ti-receipt mr-2"></i>
                <span>{{__('PaySlip')}}</span>
            </div>
        </a>

        

        {{-- <a href="{{ url('hrm-attendance') . (isset($_GET['emp_id']) ? '?emp_id=' . $_GET['emp_id'] : '')  }}" class="{{ Request::segment(1) == 'hrm-attendance' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'indicator.index' ? 'active' : '')}}">
            <div>
                <i class="fa-solid fa-question mr-2"></i>
                <span>{{__('Indicator')}}</span>
            </div>
        </a> --}}
        <a href="{{ url('hrm-attendance') . (isset($_GET['emp_id']) ? '?emp_id=' . $_GET['emp_id'] : '')  }}" class="{{ Request::segment(1) == 'hrm-attendance' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'indicator.index' ? 'active' : '')}}">
            <div>
                <i class="fa-solid fa-question mr-2"></i>
                <span>{{__('Attendance')}}</span>
            </div>
        </a>

        <a href="{{ url('hrm-training') . (isset($_GET['emp_id']) ? '?emp_id=' . $_GET['emp_id'] : '')  }}" class="{{ Request::segment(1) == 'hrm-training' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'indicator.index' ? 'active' : '')}}">
            <div>
                <i class="fa-solid fa-question mr-2"></i>
                <span>{{__('Training')}}</span>
            </div>
        </a>

        <a href="{{ url('hrm-appraisal') . (isset($_GET['emp_id']) ? '?emp_id=' . $_GET['emp_id'] : '')  }}" class="{{ Request::segment(1) == 'hrm-appraisal' ? 'active' : '' }} list-group-item list-group-item-action border-0 d-flex justify-content-between align-items-center {{ (Request::route()->getName() == 'appraisal.index' ? 'active' : '')}}">
            <div>
                <i class="ti ti-star mr-2"></i>
                <span>{{__('Appraisal')}}</span>
            </div>
        </a>
    </div>
</div>
