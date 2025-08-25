<!-- Modal -->
<!-- <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModal" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="confirmationModalTitle">Delete<?php //echo app_lang('delete') . "?"; ?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="confirmationModalContent" class="modal-body">
                <div class="container-fluid">
                    Delete message<?php //echo app_lang('delete_confirmation_message'); ?>
                </div>
            </div>
            <div class="modal-footer clearfix">
                <button id="confirmDeleteButton" type="button" class="btn btn-danger" data-bs-dismiss="modal"><i data-feather="trash-2" class="icon-16"></i> Delete<?php //echo app_lang("delete"); ?></button>
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i data-feather="x" class="icon-16"></i>Cancel <?php //echo app_lang('cancel'); ?></button>
            </div>
        </div>
    </div>
</div> -->


<div class="modal fade right_modal" id="ajaxModal" role="dialog" aria-labelledby="ajaxModal" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog ">
        
        <a class="close-task-detail in" id="close-task-detail" data-dismiss="modal">
        <i class="fa fa-close"></i></a>
        
        <div class="modal-content modal-wrapper">
            <div class="modal-header">
                    <h1 class="modal-title heading-h1" id="ajaxModalTitle" data-title="<?php //echo get_setting('app_title'); ?>"></h1>
                    <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>-->
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