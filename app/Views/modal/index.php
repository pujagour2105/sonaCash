<!-- Modal -->
<div class="modal fade right_modal" id="ajaxModal" role="dialog" aria-labelledby="ajaxModal" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-full ">
        
        <a class="close-task-detail in" id="close-task-detail" data-dismiss="modal">
        <i class="fa fa-close"></i></a>
        
        
        <div class="modal-content modal-wrapper">
            <div class="modal-header">
                <h1 class="modal-title heading-h1" id="ajaxModalTitle" data-title="<?php //echo get_setting('app_title'); ?>"></h1>
                <button type="button" class="close mdl-close close-modal" ></button>
            </div>
            
            <div class="modal-inner-wrapper">
                
                <div id="ajaxModalContent">
    
                </div>
                <div id='ajaxModalOriginalContent' class='hide'>
                    <div class="original-modal-body">
                        <div class="circle-loader"></div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close<?php //echo app_lang('close') ?></button> -->
                    </div>
                </div>
            </div>
                
        </div>
    </div>

</div>



<!--Inner  Modal -->
<div class="modal fade right_modal" id="ajaxModal_inner" role="dialog" aria-labelledby="ajaxModal" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-xl">

        <div class="modal-content modal-wrapper">
            <div class="modal-header">
                <h1 class="modal-title heading-h1" id="ajaxModalTitle_inner"></h1>
                <button type="button" class="close mdl-close" data-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="modal-inner-wrapper">

                    <div id="ajaxModalContent_inner">

                    </div>
                    <div id='ajaxModalOriginalContent_inner' class='hide'>
                        <div class="original-modal-body">
                            <div class="circle-loader"></div>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>