<div class="card me-3">
    <div class="card-header d-flex justify-content-between align-items-baseline">
        <h4>Employee Information</h4>
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
                                                    Record ID
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->id }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="" style="width: 200px; font-size: 14px;">
                                                    Name
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->name }}
                                                </td>
                                            </tr>
                                           

                                            <tr>
                                                <td class="" style="width: 200px; font-size: 14px;">
                                                    Date of Birth
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->date_of_birth }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="" style="width: 200px; font-size: 14px;">
                                                    Designation
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->type }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="" style="width: 200px; font-size: 14px;">
                                                    Brand
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="" style="width: 200px; font-size: 14px;">
                                                    Region
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->region }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="" style="width: 200px; font-size: 14px;">
                                                    Branch
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->branch }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="" style="width: 200px; font-size: 14px;">
                                                    Created At
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->created_at }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="" style="width: 200px; font-size: 14px;">
                                                    Update At
                                                </td>
                                                <td class="" style="padding-left: 10px; font-size: 14px;">
                                                    {{ $AuthUser->updated_at }}
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