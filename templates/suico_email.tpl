<{include file="db:suico_navbar.tpl"}>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div id="content" class="content content-full-width">
                        <!-- start -->
                        <h5><{$smarty.const._MD_SUICO_CHANGEMAIL}> <span class="fa fa-envelope"></span></h5><br>

                        <{include file="db:suico_form.tpl" xoForm=$emailform}>


                        <!-- end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
