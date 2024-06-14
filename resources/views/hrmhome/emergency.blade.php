<div class="card me-3">
    <div class="card-header d-flex justify-content-between align-items-baseline">
        <h4>Emergency Contact</h4>
    </div>
    <div class="card-body px-2">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-details" role="tabpanel"
                aria-labelledby="pills-details-tab">
                <div class="accordion" id="accordionPanelsStayOpenExample">
                    <!-- Open Accordion Item -->
                    <div class="accordion-item">
                        <div id="panelsStayOpen-collapseinfo" class="accordion-collapse collapse show"
                            aria-labelledby="panelsStayOpen-headinginfo">
                            <div class="accordion-body">
                                <div class="table-responsive mt-1" style="margin-left: 10px;">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td class="" style="width: 200px; font-size: 14px;">
                                                    Email
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->email }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="" style="width: 200px; font-size: 14px;">
                                                    Phone
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->phone }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>