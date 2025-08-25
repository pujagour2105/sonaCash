


$(document).ready(function () {
    // Add more row
    function toggleRemoveButtons() {
        if ($(".add_row_wrapper").length === 1) {
            $(".removeRow").hide();
        } else {
            $(".removeRow").show();
        }
    }

    toggleRemoveButtons();

    $("body").on("click", ".cloneTable", function () {
        var clonedElement = $(".add_row_wrapper:first").clone();
        clonedElement.find("input, select, textarea").val(""); 
        clonedElement.find("input[type='checkbox'], input[type='radio']").prop("checked", false); 
        $(".addMoreWrapper").append(clonedElement);
        toggleRemoveButtons();
    });

    $("body").on("click", ".removeRow", function () {
        if ($(".add_row_wrapper").length > 1) {
            $(this).parents(".add_row_wrapper").remove();
        }
        toggleRemoveButtons();
    });
    // end add more row

    // start select all
    // When the 'select all' checkbox is clicked
    $(document).on("change", "#selectAll", function () {
        // Set all checkboxes with class 'selectItem' to the same checked state as the 'select all' checkbox
        $('.selectItem').prop('checked', this.checked);

        if ($(".selectItem:checked").length > 0) {
            $("#submitButton").prop("disabled", false);
        } else {
            $("#submitButton").prop("disabled", true);
        }
        
    });
    

    $(document).on("change", ".selectItem", function () {

        if ($('.selectItem:checked').length === $('.selectItem').length) {
            $('#selectAll').prop('checked', true);
        } else {
            $('#selectAll').prop('checked', false);
        }

        if ($(".selectItem:checked").length > 0) {
            $("#submitButton").prop("disabled", false);
        } else {
            $("#submitButton").prop("disabled", true);
        }
    });
    // end select all
    


    $('.selectpicker').on('shown.bs.select', function (e) {
        $('.bootstrap-select .dropdown-menu.inner').css({
            'max-height': '200px', // Set your desired height here
            'overflow-y': 'auto'
        });
    });


    $(document).on('change', '.branchfund.selectpicker', function () {
        $.ajax({
            url: '/common/get_branch_fund',
            method: 'post',
            data: { id: $(this).val()},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.host_code == 0) {
                    $("#balance_amount").val(result.amount);
                }                
            }
        });
    });

    // check row wise checkbox
    $(document).on("change",".chkrow",function(){
        _rowTR=$(this).parents("tr").find(":checkbox");
        if(this.checked){
            $(_rowTR).prop('checked', true);
        }else{
            $(_rowTR).prop('checked', false);
        }
        verify_checkAll();
    });
    
    $(document).on("change",".check",function(){
        if ( $(this).parents("tr").find('.check:checked').length == $(this).parents("tr").find('.check').length ) {
            $(this).parents("tr").find('.chkrow').prop('checked',true);
        }else{
            $(this).parents("tr").find('.chkrow').prop('checked',false);
        }
        verify_checkAll();        
    });
    
    function verify_checkAll() {
        if ( $('.chkrow:checked').length == $('.chkrow').length ) {
            $('#chkall').prop('checked',true);
        }else{
            $('#chkall').prop('checked',false);
        }
	}
    
    $(document).on("change","#chkall",function(){
        _rowTable=$(this).parents("table").find(":checkbox");
        if(this.checked){
            $(_rowTable).prop('checked', true);
        }else{
            $(_rowTable).prop('checked', false);
        }
    }); 


    /*
    //
    $("body").on("click", ".removeimg", function() {
        _value = $(this).attr("data-value");
        _obj=$(this);   
        $.ajax({
            url: '/university/rmimages',
            method: 'post',
            data: { cid: _value },
            success: function(data) {
                result = $.parseJSON(data);
                console.log("Result - ", result);
                if (result.code == '0') {
                    $(_obj).parents(".imgrm").remove();
                    appAlert.success(result.message);
                } 
            }
        });
    });

    // get courses for lead preferences
    $('.get_preCourse.selectpicker').off('changed.bs.select').on('changed.bs.select', function () {
        var cltStrBin = '<option value="">Select</option>';
        $.ajax({
            url: '/common/get_pre_courses',
            method: 'post',
            data: { p_level: $("#p_level").val(), p_mode: $("#p_mode").val(), p_stream_id: $("#p_stream_id").val(), p_university_id: $("#p_university_id").val()},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.host_code == 0) {
                    $.each(result.data, function(index, data) {
                        cltStrBin += '<option value = "' + data.id + '">' + data.name + '</option>';
                    });
                }
                $('select.cls_ct_stream').html(cltStrBin);
                $('select.cls_ct_stream').selectpicker('refresh');
            }
        });
    });
    
    
    // get Ct wise stream
    $('.get_ct_stream.selectpicker').off('changed.bs.select').on('changed.bs.select', function () {
        var cltStrBin = '<option value="">Select</option>';
        $.ajax({
            url: '/common/get_ct_stream',
            method: 'post',
            data: { id: $(this).val()},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.host_code == 0) {
                    $.each(result.data, function(index, data) {
                        cltStrBin += '<option value = "' + data.id + '">' + data.name + '</option>';
                    });
                }
                $('select.cls_ct_stream').html(cltStrBin);
                $('select.cls_ct_stream').selectpicker('refresh');
            }
        });
    });
    
    // get Ct/stream wise university
    $('.get_ct_stream_university.selectpicker').off('changed.bs.select').on('changed.bs.select', function () {
        var cltStrBin = '<option value="">Select</option>';
        $.ajax({
            url: '/common/get_streamuniversity',
            method: 'post',
            data: { id: $(this).val()},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.host_code == 0) {
                    $.each(result.data, function(index, data) {
                        cltStrBin += '<option value = "' + data.id + '">' + data.name + '</option>';
                    });
                }
                $('select.cls_ct_stream_university').html(cltStrBin);
                $('select.cls_ct_stream_university').selectpicker('refresh');
            }
        });
    });
    
    // get Ct/university courses    
    $(document).on('change', '.get_ct_university_course.selectpicker', function () {
        var cltStrBin = '<option value="">Select</option>';
        $.ajax({
            url: '/common/get_bmtcourses',
            // url: '/common/get_universitycourses',
            method: 'post',
            data: { id: $(this).val()},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.host_code == 0) {
                    $.each(result.data, function(index, data) {
                        cltStrBin += '<option value = "' + data.id + '">' + data.name + '</option>';
                    });
                }
                $('select.cls_ct_university_course').html(cltStrBin);
                $('select.cls_ct_university_course').selectpicker('refresh');
            }
        });
    });


    // get Ct/university courses specializations    
    $('.get_ct_course_specialization.selectpicker').off('changed.bs.select').on('changed.bs.select', function () {
        var cltStrBin = '<option value="">Select</option>';
        _uids = $("#p_university_id").val() ?? [];
        $.ajax({
            url: '/common/get_coursesSpecialization',
            method: 'post',
            data: { id: $(this).val(), unis : _uids},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.host_code == 0) {
                    $.each(result.data, function(index, data) {
                        cltStrBin += '<option value = "' + data.id + '">' + data.name + '</option>';
                    });
                }
                $('select.cls_ct_course_specialization').html(cltStrBin);
                $('select.cls_ct_course_specialization').selectpicker('refresh');
            }
        });
    });
    
    
    $(document).on("change",".cls_courselevel",function(){
        $('.levelempty').val('').selectpicker('refresh');
    });
    
    $(document).on("change",".clsmode",function(){
        $('.modeempty').val('').selectpicker('refresh');
    });

    $(document).off('changed.bs.select', '.get_stream.selectpicker').on('changed.bs.select', '.get_stream.selectpicker', function () {
        var cltStrBin = '<option value="">Select</option>';
        let _level = '';
        if ($(".cls_courselevel").length > 0) {
            _level = $(".cls_courselevel").val();
        }
        
        $.ajax({
            url: '/common/get_course_list',
            method: 'post',
            data: { id: $(this).val(), level:_level},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.host_code == 0) {
                    $.each(result.data, function(index, data) {
                        cltStrBin += '<option value = "' + data.id + '">' + data.name + '</option>';
                    });
                }
                $('select.cls_course').html(cltStrBin);
                $('.cls_empty').selectpicker('refresh');
                
                $('select.cls_specialization').html('<option value="">Select</option>');
                $('.clear_specialization').selectpicker('refresh');
            }
        });
    });

    // get admin specialization by course id
    $(document).on("change",".get_specialization",function(){
        if($(this).val()) {
            var cltStrBin = '<option value="">Select</option>';
            $.ajax({
                url: '/common/get_specialization_list',
                method: 'post',
                data: { id: $(this).val()},
                success: function(data) {
                    result = $.parseJSON(data);
                    if (result.host_code == 0) {
                        $.each(result.data, function(index, data) {
                            cltStrBin += '<option value = "' + index + '">' + data + '</option>';
                        });
                    }
                    $('select.cls_specialization').html(cltStrBin);
                    $('.clear_specialization').selectpicker('refresh');
                }
            });
        }
    });
    

    $(document).on("change",".get_uni_stream",function(){
		
        if($(this).val()) {
            var cltStrBin = '<option value="">Select</option>';
            $.ajax({
                url: '/common/get_stream_university_course_list',
                method: 'post',
                data: { stream_id: $(this).val(),university_id :$('#hidden_uni_id').val()},
                success: function(data) {
                    result = $.parseJSON(data);
					console.log(result);
                    if (result.host_code == 0) {
                        $.each(result.data, function(index, value) {
							
                            cltStrBin += '<option value = "' + index + '">' + value + '</option>';
                        });
                    }
                    $('select.university_course').html(cltStrBin);
                    $('.university_clear_course').selectpicker('refresh');
					
                    $('select.university_specialization').html('<option value="">Select</option>');
                    $('.university_clear_specialization').selectpicker('refresh');
                }
            });
        }
    });	


   $(document).on("change",".get_speci_uni_course",function(){
       
       if($(this).val()) {
           var cltStrBin = '<option value="">Select</option>';
           console.log($(this).val());
           $.ajax({
               url: '/common/get_mapped_specialization_byuni_course',
               method: 'post',
               data: { university_course_id: $(this).val(),university_id :$('#hidden_uni_id').val()},
               success: function(data) {
                   result = $.parseJSON(data);
                   console.log(result);
                   if (result.host_code == 0) {
                       $.each(result.data, function(index, value) {
                           
                           cltStrBin += '<option value = "' + index + '">' + value + '</option>';
                       });
                   }
                   $('select.cls_speci_uni_course').html(cltStrBin);
                   $('.cls_speci_uni_course').selectpicker('refresh');
               }
           });
       }
   });
    
    $(document).on("change",".get_city",function(){
        if($(this).val()) {
            var cltStrBin = '<option value="">Select</option>';
            $.ajax({
                url: '/common/get_city_list',
                method: 'post',
                data: { id: $(this).val()},
                success: function(data) {
                    result = $.parseJSON(data);
                    if (result.host_code == 0) {
                        $.each(result.data, function(index, data) {
                            cltStrBin += '<option value = "' + data.id + '">' + data.name + '</option>';
                        });
                    }
                    $('select.cls_city').html(cltStrBin);
                    $('.empty_city').selectpicker('refresh');
                }
            });
        }
    });
    
    $(document).on("change",".get_fields",function(){
        if($(this).val()) {
            var cltStrBin = '<option value="">Select</option>';
            $.ajax({
                url: '/common/get_fields_list',
                method: 'post',
                data: { id: $(this).val()},
                success: function(data) {
                    result = $.parseJSON(data);
                    if (result.host_code == 0) {
                        $.each(result.data, function(index, data) {
                            cltStrBin += '<option value = "' + data.id + '">' + data.name + '</option>';
                        });
                    }
                    $('select.cls_fields').html(cltStrBin);
                    $('.fields_empty').selectpicker('refresh');
                }
            });
        }
    });



    
    
    
});

$(document).ready(function () {
    $('body').on("click","#addNewRow",{'maindiv': 'addmore_wrapper','hidebtn': 'addmore_remove','maindivid': 'wrapper'}, addMoreRow);
	$('body').on("click",".addmore_remove",{'maindiv': 'addmore_wrapper','hidebtn': 'addmore_remove'}, removeAddRow);


    function addMoreRow(obj){
		// _ev = checkInputValues();
		// if(_ev == 1){
		// 	return false;
		// }
		var maindiv = obj.data.maindiv;
		var hidebtn = obj.data.hidebtn;
		var maindivid = obj.data.maindivid;
		
		_rowNo=0;
		_isempty='';
		if ($(".noempty").length > 0) {
			_isempty=1;
		}
		
        var html = $("."+maindiv).last().clone();
		if (_isempty == '') {		
			html.find('[type=text], [type=file], [type=hidden]').val('');
			html.find('select option').prop("selected", false);
		}
		
		html.find('.rm').remove();
		html.find('.rmcls-hide').removeClass('hide');
		
		$("."+hidebtn).parent("div").find("a").removeClass("hide");
		html.find('.help-block').text('');
		
		//_id = $(this).attr("data-iv");
		html.find(".btn").attr("data-iv", '');
		html.find(".btn").attr("data-action", '');
		html.find(".draftid").val('');
		
		
        _crow = '';
		html.find('select').each(function() {		
			var idstr = $(this).attr('id');
			if(idstr != undefined){
				var idarr = idstr.split('_');
				_rowNo=parseInt(idarr[1]);
				var newid = parseInt(idarr[1]) + parseInt(1);
				id = idarr[0] +'_'+ newid;
				$(this).attr('id', id);
				_crow = newid;	
				$(this).parents('span.'+maindiv).attr('id', maindivid+"_"+newid);
				$(this).parents('span.'+maindiv).find('span.srNo').text(newid);
			}            
        });
		
		html.find('input').each(function() {			
			var idstr = $(this).attr('id');
			if(idstr != undefined){
				var idarr = idstr.split('_');
				_rowNo=parseInt(idarr[1]);
				var newid = parseInt(idarr[1]) + parseInt(1);
				id = idarr[0] +'_'+ newid;
				$(this).attr('id', id);
				_crow = newid;	
				$(this).parents('span.'+maindiv).attr('id', maindivid+"_"+newid);	
				$(this).parents('span.'+maindiv).find('span.srNo').text(newid);
			}            
        });
		
		html.find('file').each(function() {			
			var idstr = $(this).attr('id');
			if(idstr != undefined){
				var idarr = idstr.split('_');
				_rowNo=parseInt(idarr[1]);
				var newid = parseInt(idarr[1]) + parseInt(1);
				id = idarr[0] +'_'+ newid;
				$(this).attr('id', id);
				_crow = newid;	
				$(this).parents('span.'+maindiv).attr('id', maindivid+"_"+newid);
				$(this).parents('span.'+maindiv).find('span.srNo').text(newid);
			}            
        });
		
		$(html).find("button").remove();
		$(html).find("."+hidebtn).removeClass("hide");
        $("span."+maindiv).last().after(html);
		
		if ( $(".autocomplete").length > 0) {
			autocomplete(document.getElementById("autocomplete_"+_crow), products_deals);	
		}
		
		if ( $(".selectpicker").length > 0) {
			$('.selectpicker').selectpicker('refresh');
		}
		
		_www=$("."+maindiv).attr("data-maxrow");
		if (_www > 0 ) {
			if ((_rowNo+1) >= _www) {
				$("#addNewRow").addClass("hide");
			} else {
				$("#addNewRow").removeClass("hide");
			}
		}
		
		// if( $(".colorpicker-default").length > 0) {
		// 	pickColor();
		// }
	}


    function removeAddRow(obj){
		var maindiv = obj.data.maindiv;
		var hidebtn = obj.data.hidebtn;
		
		_Cobj = $(this);
		_id = $(this).attr("data-id");
		_tname = $(this).attr("data-table");
		rmURL = $(this).attr("data-action");
		// console.log(_id , rmURL);
			//deleteRowDB($(this).attr("data-id"), $(this).attr("data-action"));
		if(_id && rmURL) {
			swal({
				title: "Are you sure?",
				text: "You will not be able to recover this record!",
				type: "warning",
				showCancelButton: true,
				closeOnConfirm: false,
				showLoaderOnConfirm: true
			}, function () {
				$.ajax({
					type: 'POST',
					url: base_url+'/'+rmURL,
					data: {id: _id, t:_tname},
					success: function (data) {
						result = $.parseJSON(data);
                        swal.close();
						if(result.success){
							_Cobj.parents("."+maindiv).remove();
							if($("."+maindiv).length == 1 ){
								$("."+hidebtn).parent("div").find("a").addClass("hide");
							}
                            appAlert.success(result.message);
							// swal("Done!", "It was succesfully deleted!", "success");
						} else {
                            appAlert.error(result.message);
							// swal("Error deleting!", "Please try again", "error");
						}					
					}
				});
			});
		} else {
			$(this).parents("."+maindiv).remove();
			if($("."+maindiv).length == 1 ){			
				$("."+hidebtn).parent("div").find("a").addClass("hide");
			}
		}

		var _rowNo = $('.'+maindiv).length;
		_www=$("."+maindiv).attr("data-maxrow");
		if (_www > 0 ) {
			if ((_rowNo) >= _www) {
				$("#addNewRow").addClass("hide");
			} else {
				$("#addNewRow").removeClass("hide");
			}
		}
		
	}


    $("body").on("change", "#disposition, #iddisposition", function() {
        cltStrBin='<option value="">Select</option>';
        $.ajax({
            url: '/lead/dispStatus',
            method: 'post',
            data: { cid: $(this).val() },
            success: function(data) {
                result = $.parseJSON(data);
                if (result.success) {
                    $.each(result.data, function(index, value) {
                        cltStrBin += '<option value = "' + index + '">' + value + '</option>';
                    });
                    $('select#status, select#idstatus').html(cltStrBin);
                } 
            }
        });
    });

    // Add university to NI list
    $(document).on("click",".addNI",function(){
        _obj = $(this);
        $.ajax({
            url: base_url+'admin/addlead_nilist',
            method: 'post',
            data: { uid: $(this).attr("data-uniid"), lid: $(this).attr("data-lid") },
            success: function(data) {
                result = $.parseJSON(data);
                if(result.host_code == 0) {
                    appAlert.success(result.host_description);
                    $(_obj).prop('disabled', true);
                } else {
                    appAlert.error(result.host_description);
                    $(_obj).prop('disabled', false);
                }
            }
        });
        
    });

    // remove lead from list and ni list
    $(document).on("click",".cls_mark_ni",function(){
        _obj=(this);
        $.ajax({
            url: '/common/mark_lead_ni',
            method: 'post',
            data: { id: $(this).attr("data-listid")},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.host_code == 0) {
                    appAlert.success(result.host_description);
                    $(_obj).parents("tr").remove();
                } else {
                    appAlert.error(result.host_description);
                }                
            }
        });        
    });

    $(document).on("click",".lead_recon",function(){
        _obj=(this);
        _isApi = $(this).attr("data-isapi");        
        $.ajax({
            url: '/common/lead_recon',
            method: 'post',
            data: { id: $(this).attr("data-listid"), isapi:_isApi},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.host_code == 0) {
                    appAlert.success(result.host_description);
                } else {
                    appAlert.error(result.host_description);
                }         
                if(_isApi == 1) {
                    $(_obj).parents(".college-details-table").find(".apiSts").text(result.host_description);
                }       
            }
        });        
    });

    // get university 
    $(document).on('change', '.get_ct_university_name.selectpicker', function () {
        var cltStrBin = '<option value="">Select</option>';
        $.ajax({
            url: '/common/get_ct_university',
            method: 'post',
            data: { id: $(this).val()},
            success: function(data) {
                result = $.parseJSON(data);
                if (result.host_code == 0) {
                    $.each(result.data, function(index, data) {
                        cltStrBin += '<option value = "' + index + '">' + data + '</option>';
                    });
                }
                $('select.cls_ct_university_name').html(cltStrBin);
                $('select.cls_ct_university_name').selectpicker('refresh');
            }
        });
    });

    */

});