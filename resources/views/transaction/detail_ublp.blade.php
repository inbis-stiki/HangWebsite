@include('template/header')
@include('template/sidebar')

<!--**********************************
    Content body start
***********************************-->
<div class="content-body">
    <div class="col-md-6">
        <div class="card-content">
            <div class="nestable">
                <div class="dd" id="nestable">
                    <ol class="dd-list">
                        <li class="dd-item" data-id="1">
                            <div class="dd-handle">Item 1</div>
                        </li>
                        <li class="dd-item" data-id="2"><button class="dd-collapse" data-action="collapse" type="button">Collapse</button><button class="dd-expand" data-action="expand" type="button">Expand</button>
                            <div class="dd-handle">Item 2</div>
                            <ol class="dd-list">
                                <li class="dd-item" data-id="3">
                                    <div class="dd-handle">Item 3</div>
                                </li>
                                <li class="dd-item" data-id="4">
                                    <div class="dd-handle">Item 4</div>
                                </li>
                                <li class="dd-item" data-id="5"><button class="dd-collapse" data-action="collapse" type="button">Collapse</button><button class="dd-expand" data-action="expand" type="button">Expand</button>
                                    <div class="dd-handle">Item 5</div>
                                    <ol class="dd-list">
                                        <li class="dd-item" data-id="6">
                                            <div class="dd-handle">Item 6</div>
                                        </li>
                                        <li class="dd-item" data-id="7">
                                            <div class="dd-handle">Item 7</div>
                                        </li>
                                        <li class="dd-item" data-id="8">
                                            <div class="dd-handle">Item 8</div>
                                        </li>
                                    </ol>
                                </li>
                                <li class="dd-item" data-id="9">
                                    <div class="dd-handle">Item 9</div>
                                </li>
                                <li class="dd-item" data-id="10">
                                    <div class="dd-handle">Item 10</div>
                                </li>
                            </ol>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>


<!--**********************************
    Content body end
***********************************-->
@include('template/footer')