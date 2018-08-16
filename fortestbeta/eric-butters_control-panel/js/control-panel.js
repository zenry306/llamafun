$(function(){
	
	$(document).on('click','.report-judge',function(){
		
		var JUDGE_ID = $(this).attr('id').split('_'); 
		/*	分割JUDGE_ID的ID
				JUDGE_ID = (guilty或notguilty)_(report_id)_(member_id)_(report_member_id)
				JUDGE_ID[0] = guilty或notguilty
				JUDGE_ID[1] = report_id
				JUDGE_ID[2] = member_id //檢舉人ID
				JUDGE_ID[3] = report_member_id //被檢舉ID
		*/
		var JUDGE = JUDGE_ID[0];
		var REPORT_ID = JUDGE_ID[1];
		var REPORT_REASON = $('#report-reason-'+REPORT_ID).val();
		//var MEMBER_ID = JUDGE_ID[2];
		//var REP_MEMBER_ID = JUDGE_ID[3];
		//$(this).css('background','red')
		//$('#'+JUDGE+"_"+REPORT_ID+"_"+MEMBER_ID+"_"+REP_MEMBER_ID).css('background','red');
		//alert(JUDGE+REPORT_ID+MEMBER_ID+REP_MEMBER_ID+"~");
		
		$.ajax({
			type:'POST',
			url:'/fortestbeta/eric-butters_control-panel/report_judge-db.php',
			data:{judge : JUDGE , report_id:REPORT_ID , report_reason:REPORT_REASON},
			success:function(response){
				//alert(response);
				if(response==true){
					$('#'+JUDGE+"_"+REPORT_ID).css('background','red');
				}
				else{
					alert("送出失敗！");
				}

			},
			error: function(data){
			//if(data.success == true){alert("失敗");}
				alert(data+'失敗');
			}
		});
	});
	
	$(document).on('click','.ban-submit',function(){
		var BAN_MEMBER_ID = $("#ban-member-id").val();
		$('.ban_msg').text(BAN_MEMBER_ID);
		
		$.ajax({
			type:'POST',
			url:'/fortestbeta/eric-butters_control-panel/ban-account.php',
			data:{ban_member_id : BAN_MEMBER_ID },
			success:function(response){
				//alert(response);
				if(response==true){
					$('.ban_msg').text(BAN_MEMBER_ID+"停權成功！");
				}
				else{
					$('.ban_msg').text(BAN_MEMBER_ID+"送出停權失敗！");
				}
			},
			error: function(data){
			//if(data.success == true){alert("失敗");}
				alert(data);
			}
		});
	});
	
	$(document).on('click','.unban-submit',function(){
		var UNBAN_MEMBER_ID = $("#unban-member-id").val();
		$('.unban_msg').text(UNBAN_MEMBER_ID);

		$.ajax({
			type:'POST',
			url:'/fortestbeta/eric-butters_control-panel/unban-account.php',
			data:{unban_member_id : UNBAN_MEMBER_ID },
			success:function(response){
				//alert(response);
				if(response==true){
					$('.unban_msg').text(UNBAN_MEMBER_ID+"復權成功！");
				}
				else{
					$('.unban_msg').text(UNBAN_MEMBER_ID+"送出復權失敗！");
				}
			},
			error: function(data){
			//if(data.success == true){alert("失敗");}
				alert(data);
			}
		});
		
	});
	
	
	/*發文用*/
	var canvas = null;
	var context = null;
	var img_newwidth = null;
	var img_newheight = null;
	var image = new Image();
	var top_fontsize = 36;
	var bottom_fontsize = 36;
	var thread_imgtype = null;
	var thread_uploadtype = null;
	var thread_imgfile = null;
	var thread_imgurl = null;
	function new_post(img , img_type ,upload_type){
		//alert(img_type);
		thread_imgtype = img_type;
		thread_uploadtype = upload_type;
		if(upload_type === 'file'){
			var reader = new FileReader();
			reader.onload = function () {
				thread_imgfile = img;
				image.src = reader.result;
				if(img_type==='image/gif'){
					$(".preview-gif").attr("src",reader.result);
				}
			}
			reader.readAsDataURL(img);
		}
		else if(upload_type === 'url'){
			thread_imgurl = img;
			image.src = img;
			if(img_type==='image/gif'){
				$(".preview-gif").attr("src",img);
			}
		}
		
		image.onload = function ()	{
			//console.log('loaded!');
			//alert(image.width);
			//只要到POST頁面將輸入欄位都初始化，以及前一頁的上傳也初始化。這樣點上一步的時候只需要單純做show、hide
			top_fontsize = 36;
			bottom_fontsize = 36;
			$(".meme-inputtext").val('');
			$("textarea.thread-subject").val('');
			$(".thread_uploadimg").val('');
			$(".thread_uploadimg_url").val('');
			$('body').css('overflow','hidden'); //關閉背景的卷軸
			
			if(img_type==='image/gif'){
				//$('.makememe-div').hide(); //如果是GIF就不需要makememe功能所以隱藏然後顯示一般img做preview
				$(".meme-inputtext").prop('disabled',true);
				$('.meme-inputtext').attr('placeholder', '輸入文字限制使用JPG、PNG');
				$(".meme_fontsize").prop('disabled',true);
				$(".meme_fontsize").addClass('meme_fontsize-disabled');
				$('.meme-canvas').hide();
				$('.preview-gif').show();
				$('.newthread_popup').css('display','block'); //讓drop檔案使用，若drop後不符合格式也不會顯示出popup，或格式符合則從這邊叫出popup
				$('.newthread-upload').hide();
				$('.newthread-post').show();
			}
			else{
				$(".meme-inputtext").prop('disabled',false);
				$('#topline').attr('placeholder','上排文字');
				$('#bottomline').attr('placeholder','下排文字');
				$(".meme_fontsize").prop('disabled',false);
				$(".meme_fontsize").removeClass('meme_fontsize-disabled');
				$('.preview-gif').hide();
				$('.meme-canvas').show();
				var img_width = image.width;
				var img_height = image.height;
				//alert("~".image.src);
				img_newwidth = 540;
				img_newheight = Math.round(img_height*img_newwidth/img_width);
				
				canvas = document.getElementById("meme-canvas");
				context = canvas.getContext("2d");
				// Canvas settings:
				canvas.width = img_newwidth;
				canvas.height = img_newheight;
				context.drawImage(image, 0, 0, img_newwidth, img_newheight);
				context.textAlign = "center";
				context.font = "bold "+top_fontsize+"pt Noto Sans TC, sans-serif";
				context.fillStyle = "white";
				context.strokeStyle = "black";
				context.lineWidth = 2;
				//alert("123");
				//alert(img_newwidth+"X"+img_newheight);
				$('.newthread_popup').css('display','block'); //讓drop檔案使用，若drop後不符合格式也不會顯示出popup，或格式符合則從這邊叫出popup
				$('.newthread-upload').hide();
				$('.newthread-post').show();
			}
			//alert("456");
			//$('#blah').attr('src', e.target.result);
		}
		
		image.onerror = function() {
			console.log("error");
			$('.uploadimg_url-error').css('visibility','visible');
			$('.uploadimg_url-error').text('網址錯誤喔！');
			//alert($('.url-meme-error').length);
			/*
			if($('#url-meme').val().length<1){
				$('.url-meme-error').text('');
			}*/
		}
	}
	
	/*發文用*/
	function check_imgfile(img_file){
		if(typeof(img_file) != "undefined" && img_file != null){
			var img_type = img_file.type;
			if(img_type==="image/png" || img_type==="image/jpeg" || img_type==="image/jpg" || img_type==="image/gif"){
				var img_size = img_file.size/1024/1024; //除1024等於KB、再除1024等於MB
				//alert(img_size);
				if(img_size<=10){
					new_post(img_file , img_type , 'file'); //格式大小皆正確呼叫new_post function
				}
				else{
					alert("Daddy Too Big!!!!  最大10MB喔!!!");
					$(".thread_uploadimg").val('');
				}

			}
			else{
				alert("只能上傳圖片檔喔！JPG、PNG、GIF");
				$(".thread_uploadimg").val('');
			}
		}
	}
	
	var obj = $(window);
	obj.bind('dragover', function (e) 
	{
		e.stopPropagation();
		e.preventDefault();
		$('.is-dragover').css('display','block');
		//$(this).css('border', '2px solid #0B85A1');
	});
	
	$('.is-dragover').bind('dragleave', function (e) 
	{
		e.stopPropagation();
		e.preventDefault();
		$('.is-dragover').css('display','none');

	});
	obj.on('drop', function (e) 
	{
		$('.is-dragover').css('display','none');
		e.stopPropagation();
		e.preventDefault();
		var files = e.originalEvent.dataTransfer.files;
		check_imgfile(files[0]);
		
		//$('.newthread_popup').css('display','block');
		//alert(files[0].size);
		//We need to send dropped files to Server
		//handleFileUpload(files,obj);
	});
	
	/*發文用*/
	$(document).on('change', '.thread_uploadimg', function() {
		//alert("1");
		var img_file = $(this)[0].files[0];
		check_imgfile(img_file);
	});
	/*發文用*/
	var globalTimeout_thread = null;
	$(document).on('keyup', '.thread_uploadimg_url', function() {

		var img_url = $('.thread_uploadimg_url').val();
		if(img_url.length===0){
			$('.uploadimg_url-error').css('visibility','hidden');
			$('.uploadimg_url-error').text('URL');
		}

		if (globalTimeout_thread != null) {
			clearTimeout(globalTimeout_thread);
		}

		globalTimeout_thread = setTimeout(function() {

			globalTimeout_thread = null;  

			if(img_url.length>6){
				$('.uploadimg_url-error').css('visibility','visible');
				$('.uploadimg_url-error').text('載入中...');
				$.ajax({
					type:'POST',
					url:'/fortestbeta/eric-butters_control-panel/uploadimg-url-check.php',
					data:{image_url : img_url},
					success:function(response){
						var response = response.trim();
						alert(response);
						//alert(img_url);
						//alert(response);
						if(response==="image/png" || response==="image/jpeg" || response==="image/jpg" || response==="image/gif"){
							var img_type = response;
							$('.uploadimg_url-error').css('visibility','hidden');
							$('.uploadimg_url-error').text('URL');
							new_post(img_url , img_type , 'url');
						}
						else if(response === '網址錯誤'){
							$('.uploadimg_url-error').css('visibility','visible');
							$('.uploadimg_url-error').text('網址錯誤喔！');
						}
						else if(response === '圖片太大'){
							$('.uploadimg_url-error').css('visibility','visible');
							$('.uploadimg_url-error').text('上限為10MB喔！');
						}
						else if(response === '不支援網址'){
							$('.uploadimg_url-error').css('visibility','visible');
							$('.uploadimg_url-error').text('只能使用圖片網址喔！');
						}
					}/*,
					error: function(data){
					//if(data.success == true){alert("失敗");}
						alert(data);
					}*/
				});
			}
			//ajax code
		}, 500); 
	});
	
	//後台發文
	$(document).on('click','.newthreadbtn-cp',function(){
		$('body').css('overflow','hidden');
		$('.newthread_popup').css('display','block');
	});
	/*發文用*/
	$(document).on('click', '.newthread_popup-close', function() {
		$('body').css('overflow','auto');
		$('.newthread_popup').hide();
	});
	
	/*發文用*/
	$(document).on('click', '.newthread_post-close', function() {
		$('body').css('overflow','auto');
		$('.thread_subject_msg').css('visibility','hidden');
		$('.newthread_popup').hide();
		$('.newthread-post').hide();
		$('.newthread-upload').show();
	});
	/*發文用*/
	$(document).on('click', '.post-backtoup', function() {
		$('.thread_subject_msg').css('visibility','hidden');
		$('.newthread-post').hide();
		$('.newthread-upload').show();
	});
	
	/*發文用*/
	$(document).on('click', '.newthread-send', function() {
		var SUBJECT = $("textarea.thread-subject").val();
		var format = /(?!^\s+$)^(.|\n)+$/;//不允許全部空白無任何文字或符號，但文字之間可以有空白
		var format_correct = true;
		$('.thread_subject_msg').css('visibility','visible');
		if(SUBJECT.length < 1){
			document.getElementById("thread_subject_msg").innerHTML ="還沒有填標題喔！";
			format_correct = false;
		 }
		 else{
			 if(!SUBJECT.match(format)){
				 document.getElementById("thread_subject_msg").innerHTML ="還沒有填標題喔！";
				 format_correct = false;
			 }
		 }
		 
		 if(format_correct==true){
			 $('.thread_subject_msg').css('visibility','hidden');
			document.getElementById("thread_subject_msg").innerHTML ="標題";
			var formdata = new FormData();
			formdata.append('thread_subject', SUBJECT);
			formdata.append('thread_uploadtype', thread_uploadtype); //url或是file
			formdata.append('img_type', thread_imgtype); //若有用到畫布是jpg或png、反之是GIF
			//alert("123");
			var topline_text = $('textarea#topline').val();
			var bottomline_text = $('textarea#bottomline').val();
			
			if(topline_text.length>0 || bottomline_text.length>0){
				var dataURL = canvas.toDataURL('image/jpeg', 0.4);
				formdata.append('thread_uploadtype', 'canvas');
				formdata.append('upload_img', dataURL);
			}
			else{
				if(thread_uploadtype==="file"){
					formdata.append('upload_img', thread_imgfile);
				}
				else if(thread_uploadtype==="url"){
					formdata.append('upload_img', thread_imgurl);
				}
			}
			$('.newthread-send').text('傳送中...');
			$(".newthread-post *").prop('disabled',true); //按下送出後把newthread-post所有元素的功能都停止
			
			$.ajax({
				type:'POST',
				url:'/fortestbeta/eric-butters_control-panel/newthread-cp-db.php',
				data:formdata,
				processData: false,  // tell jQuery not to process the data
				contentType: false,
				success:function(response){
					alert(response);
					var response = response.trim();
					var threadid_format = /^[\w]{8}$/; //只接收8個英文大小寫跟數字、就是文章ID
					if(response.match(threadid_format)){
						//window.location = "/thread-show.php?thread_id="+response;
						window.open('https://llamafun.com/thread-show.php?thread_id='+response,"_blank","width=600,height=500");
						location.reload();
					}
					else{
						alert("發文失敗喔！ 請再嘗試一次");
						$('.newthread-send').text('發文');
						$(".newthread-post *").prop('disabled',false);
					}
				}
				,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert(data);
				}
			});
		 }
	});
	
	/*發文*/
	$(document).on('keyup', '.meme-inputtext', function() {
		var INPUTLINE = $(this).attr('id');
		var x = img_newwidth/2;
		var text_maxwidth = img_newwidth-20;
		var topline_text = $('textarea#topline').val();
		var bottomline_text = $('textarea#bottomline').val();
		var array_top = topline_text.split("\n");
		var array_bottom = bottomline_text.split("\n");

		context.clearRect(0, 0, img_newwidth, img_newheight); //清除整個canvas的所有文字或添加的圖案等，這樣透明PNG就不會殘留文字在背後
		context.drawImage(image, 0, 0, img_newwidth, img_newheight);

		var topline_y = top_fontsize+14;
		for (var i = 0; i < array_top.length; i++) {
			context.font = "bold "+top_fontsize+"pt Noto Sans TC, sans-serif";
			context.fillText( array_top[i], x , topline_y , text_maxwidth);
			context.strokeText(array_top[i], x, topline_y, text_maxwidth);
			topline_y += top_fontsize+14;
		}

		var bottomline_y = img_newheight-20;
		for (var i = 0; i < array_bottom.length; i++) {
			context.font = "bold "+bottom_fontsize+"pt Noto Sans TC, sans-serif";
			context.fillText( array_bottom[i], x , bottomline_y , text_maxwidth);
			context.strokeText(array_bottom[i], x, bottomline_y, text_maxwidth);
			bottomline_y -= bottom_fontsize+14;
		}
	});
	
	/*發文用*/
	$(document).on('click', '.meme_fontsize', function() {
		var meme_fontsize = $(this).attr('id'); //同個class不同ID topline_increase、topline_decrease、bottomline_increase、bottomline_decrease
		var x = img_newwidth/2;
		var text_maxwidth = img_newwidth-20;
		var topline_text = $('textarea#topline').val();
		var bottomline_text = $('#bottomline').val();
		var array_top = topline_text.split("\n");
		var array_bottom = bottomline_text.split("\n");
		
		context.clearRect(0, 0, img_newwidth, img_newheight); //清除整個canvas的所有文字或添加的圖案等，這樣透明PNG就不會殘留文字在背後
		context.drawImage(image, 0, 0, img_newwidth, img_newheight);
		
		if(meme_fontsize==='topline_increase'){
			top_fontsize +=2;	
		}
		else if(meme_fontsize==='topline_decrease'){
			if(top_fontsize >= 18){
				top_fontsize -=2;
			}	
		}
		else if(meme_fontsize==='bottomline_increase'){
			bottom_fontsize += 2;
		}
		else if(meme_fontsize==='bottomline_decrease'){
			if(bottom_fontsize >= 18){
				bottom_fontsize -=2;
			}
		}
		
		var topline_y = top_fontsize+14;
		for (var i = 0; i < array_top.length; i++) {
			context.font = "bold "+top_fontsize+"pt Noto Sans TC, sans-serif";
			context.fillText( array_top[i], x , topline_y , text_maxwidth);
			context.strokeText(array_top[i], x, topline_y, text_maxwidth);
			topline_y += top_fontsize+14;
		}
		
		var bottomline_y = img_newheight-20;
		for (var i = 0; i < array_bottom.length; i++) {
			context.font = "bold "+bottom_fontsize+"pt Noto Sans TC, sans-serif";
			context.fillText( array_bottom[i], x , bottomline_y , text_maxwidth);
			context.strokeText(array_bottom[i], x, bottomline_y, text_maxwidth);
			bottomline_y -= bottom_fontsize+14;
		}

	});
	
});