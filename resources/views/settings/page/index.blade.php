@extends ('settings.layouts.app') 
@section ('title', trans('labels.backend.pages.management')) {{-- 
@section('page-header')
<h2>{{ trans('labels.backend.pages.management') }}</h1>
@endsection
 --}} 
@section('content')
    <div class="box box-info">

        <div class="box-header with-border header">
            <h3 class="header-title">
                <i class="fa fa fa-edit"></i> {{ trans('labels.backend.pages.management') }}
            </h3>
            <div class="box-tools pull-right">
                {{--
    @include('backend.pages.partials.pages-header-buttons') --}}
            </div>
        </div>

        <div class="box-body pure-g box-content">
            <div class="table-responsive data-table-wrapper pure-u-1 pure-u-md-1">
                <table id="pages-table" class="table table-condensed table-hover table-bordered pure-table pure-table-horizontal">
                    <thead>
                        <tr>
                            <th>{{ trans('labels.backend.pages.table.title') }}</th>
                            <th>{{ trans('labels.backend.pages.table.status') }}</th>
                            <th>{{ trans('labels.backend.pages.table.createdat') }}</th>
                            <th>{{ trans('labels.backend.pages.table.createdby') }}</th>
                            <th>{{ trans('labels.general.actions') }}</th>
                        </tr>
                    </thead>
                    <thead class="transparent-bg">
                        <tr>
                            <th>
                                {!! Form::text('first_name', null, ["class" => "search-input-text form-control", "data-column" => 0, "placeholder" => trans('labels.backend.pages.table.title')])
                                !!}
                                <a class="reset-data" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                            </th>
                            <th>
                                {!! Form::select('status', [0 => "InActive", 1 => "Active"], null, ["class" => "search-input-select form-control", "data-column"
                                => 1, "placeholder" => trans('labels.backend.pages.table.all')]) !!}
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
            <!--table-responsive-->
        </div>
        <!-- /.box-body -->
    </div>
    <!--box-->
@endsection
 
@section('after-scripts') {{-- For DataTables --}} {{ Html::script(mix('js/dataTable.js')) }}

    <script>
        $(function() {
            var dataTable = $('#pages-table').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    headers: {
                        'CSRFToken': document.head.querySelector('meta[name="csrf-token"]').content
                    },
                    url: '{{ route("settings.page.get") }}',
                    type: 'get'
                },
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'created_by', name: 'first_name'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[1, "asc"]],
                searchDelay: 500,
                dom: 'lBfrtip',
                buttons: {
                    buttons: [
                        { extend: 'copy', className: 'copyButton',  exportOptions: {columns: [ 0, 1, 2, 3 ]  }},
                        { extend: 'csv', className: 'csvButton',  exportOptions: {columns: [ 0, 1, 2, 3 ]  }},
                        { extend: 'excel', className: 'excelButton',  exportOptions: {columns: [ 0, 1, 2, 3 ]  }},
                        { extend: 'pdf', className: 'pdfButton',  exportOptions: {columns: [ 0, 1, 2, 3 ]  }},
                        { extend: 'print', className: 'printButton',  exportOptions: {columns: [ 0, 1, 2, 3 ]  }}
                    ]
                }
            });

            var Backend = {}; // common variable used in all the files of the backend          
            Backend = {    

                DataTableSearch: { //functionalities related to datable search at all the places
                    selector: {},

                    init: function (dataTable) {

                        this.setSelectors();
                        this.setSelectors.divAlerts.delay(2000).fadeOut(800);
                        this.addHandlers(dataTable);

                    },
                    setSelectors: function () {
                        this.selector.searchInput = document.querySelector("div.dataTables_filter input");
                        this.selector.columnSearchInput = document.querySelectorAll(".search-input-text");
                        this.selector.columnSelectInput = document.querySelectorAll(".search-input-select");
                        this.selector.restButton = document.querySelectorAll('.reset-data');
                        this.setSelectors.copyButton = document.getElementById("copyButton");
                        this.setSelectors.csvButton = document.getElementById("csvButton");
                        this.setSelectors.excelButton = document.getElementById("excelButton");
                        this.setSelectors.pdfButton = document.getElementById("pdfButton");
                        this.setSelectors.printButton = document.getElementById("printButton");
                        this.setSelectors.divAlerts = jQuery('div.alert').not('.alert-important');

                    },
                    cloneElement: function (element, callback) {
                        var clone = element.cloneNode();
                        while (element.firstChild) {
                            clone.appendChild(element.lastChild);
                        }
                        element.parentNode.replaceChild(clone, element);
                        Backend.DataTableSearch.setSelectors();
                        callback(this.selector.searchInput);
                    },
                    addHandlers: function (dataTable) {
                        // get the datatable search input and on its key press check if we hit enter then search with datatable
                        this.cloneElement(this.selector.searchInput, function (element) { //cloning done to remove any binding of the events
                            element.onkeypress = function (event) {
                                if (event.keyCode == 13) {
                                    dataTable.fnFilter(this.value);
                                }
                            };
                        }); // to remove all the listinerers

                        // for text boxes
                        //column input search if search box on the column of the datatable given with enter then search with datatable
                        if (this.selector.columnSearchInput.length > 0) {
                            this.selector.columnSearchInput.forEach(function (element) {
                                element.onkeypress = function (event) {
                                    if (event.keyCode == 13) {
                                        var i = element.getAttribute("data-column"); // getting column index
                                        var v = element.value; // getting search input value
                                        dataTable.api().columns(i).search(v).draw();
                                    }
                                };
                            });
                        }


                        // Individual columns search
                        if (this.selector.columnSelectInput.length >> 0) {
                            this.selector.columnSelectInput.forEach(function (element) {
                                element.onchange = function (event) {
                                    var i = element.getAttribute("data-column"); // getting column index
                                    var v = element.value; // getting search input value
                                    dataTable.api().columns(i).search(v).draw();
                                };
                            });
                        }

                        // Individual columns reset
                        if (this.selector.restButton.length >> 0) {
                            this.selector.restButton.forEach(function (element) {
                                element.onclick = function (event) {
                                    var inputelement = this.previousElementSibling;
                                    var i = inputelement.getAttribute("data-column");
                                    inputelement.value = "";
                                    dataTable.api().columns(i).search("").draw();
                                };
                            });
                        }

                        /*this.setSelectors.copyButton.onclick = function (element) {
                            document.querySelector(".copyButton").click();
                        };
                        this.setSelectors.csvButton.onclick = function (element) {
                            document.querySelector(".csvButton").click();
                        };
                        this.setSelectors.excelButton.onclick = function (element) {
                            document.querySelector(".excelButton").click();
                        };
                        this.setSelectors.pdfButton.onclick = function (element) {
                            document.querySelector(".pdfButton").click();
                        };
                        this.setSelectors.printButton.onclick = function (element) {
                            document.querySelector(".printButton").click();
                        };*/
                    }

                }
            };

            Backend.DataTableSearch.init(dataTable);
      

        });
    </script>
@endsection