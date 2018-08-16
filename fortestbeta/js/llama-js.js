
	// Load the SDK asynchronously
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1895947000621168',
      xfbml      : true,
      version    : 'v2.9'
    });
    FB.AppEvents.logPageView();
  };
	// Load the SDK asynchronously
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/zh_TW/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

   
function login_pressenter(e) {
	if (e.keyCode == 13) {
		$('.popup_loginbtn').click();
	 }
}

//在註冊的欄位按下enter可以觸發登入按鈕
function register_pressenter(e) {
	if (e.keyCode == 13) {
		$('.popup_registerbtn').click();
	 }
}

function check_iamgesrc_exist (image_src){
	var img = new Image(); 
	img.src = image_src;
	if (img.width == 0) {
		return false;
	}
	else{
		return true;
	}
}

$(function(){
	
	
	$(window).on('click',function(event) {
		if (event.target == report_popup) { //點檢舉的任何地方都可以關閉檢舉視窗
			$('body').css('overflow','auto');
			$('.report_popup').hide();
			$('.popup_report-next-unchecked').removeClass( "popup_report-next" );
			$('.report-radio-other').val('');
			$(".report-radio").find("input:radio").prop("checked", false).end().buttonset("refresh");
		}
		
		else if (event.target == contact_popup) { //點contact_popup的任何地方都可以關閉檢舉視窗
			$('body').css('overflow','auto');
			$('.contact_popup').css('display','none');
		}
		
		else if (event.target == register_popup) { //點擊regiter_popup的任何地方都可以關閉註冊視窗
			//$('#nav').show();
			$('body').css('overflow','auto');
			$('.register_popup').css('display','none');
		}
		
		else if (event.target == login_popup) { //點擊login_popup的任何地方都可以關閉登入視窗
			//$('#nav').show();
			$('body').css('overflow','auto');
			$('.login_popup').css('display','none');
		}
		
		else if (event.target == comment_img_popup) { //點擊comment_img_popup的任何地方都可以關閉圖片視窗
			$('body').css('overflow','auto');
			$('.comment_img_popup').css('display','none');
		}
		
		else if (event.target == leaderboard_popup) { //點擊leaderboard_popup的任何地方都可以關閉排行榜
			$('body').css('overflow','auto');
			$('.leaderboard_popup').css('display','none');
		}
		
	});
	
	$(window).resize(function() {
		//$('#side-nav').hide();
		$('#side-nav').width('0');
	});
	$(document).on('click','.nav-close',function(){
		//$('#side-nav').hide();
		$('#side-nav').width('0');
	});
	$(document).on('click','.toggle-nav',function() {
		//$('#side-nav').show();
		//$('#side-nav').fadeToggle(300); 
		$('#side-nav').width('250');
		//$('#nav').show('slide', {direction: 'left'}, 5000);
		//$('#nav').css('display','block');
		//$('#nav').show();
		//$('#nav').width('250');
	});
	
	//當畫面上顯示video時才會播放，畫面上看不到的時候會暫停
	var tolerancePixel = 40;
	function checkMedia(){
		var media = $('video').not("[autoplay='autoplay']");
		// Get current browser top and bottom
		var scrollTop = $(window).scrollTop() + tolerancePixel;
		var scrollBottom = $(window).scrollTop() + $(window).height() - tolerancePixel;

		media.each(function(index, el) {
			var yTopMedia = $(this).offset().top;
			var yBottomMedia = $(this).height() + yTopMedia;

			if(scrollTop < yBottomMedia && scrollBottom > yTopMedia){ //view explaination in `In brief` section above
				$(this).get(0).play();
				//alert("123");
				$(this).next(".playpause").hide(); //next是下一個元素
			} else {
				$(this).get(0).pause();
				$(this).next(".playpause").show();
			}
		});

		//}
	}
	$(document).on('scroll', checkMedia); //滾動卷軸執行checkMedia function
	
	var THREADLOAD_FINISH = true;
	$(window).scroll(function() {
		if($(window).scrollTop() + $(window).height() >= Math.round($(document).height()*0.9)) {
			if($('.thread-loding').length){
				var THREAD_LODING_ID = $(".thread-loding").attr('id').split('_');
				/*	分割thread-loding的
						THREAD_LODING_ID = (thread_type)_date_Hotness(post_member_id)
						THREAD_LODING_ID[0] = thread_type
						THREAD_LODING_ID[1] = date
						THREAD_LODING_ID[2] = Hotness
						THREAD_LODING_ID[3] = post_member_id
				*/
				var THREAD_TYPE = THREAD_LODING_ID[0];
				var DATE = THREAD_LODING_ID[1];
				var HOTNESS = THREAD_LODING_ID[2];
				var POST_MEMBER_ID = THREAD_LODING_ID[3];
				//alert(POST_MEMBER_ID);
				//var AJAX_FINISH = true;
				$('.thread-loding').show();
				
				//alert(THREAD_TYPE+DATE+HOTNESS);
				if(THREADLOAD_FINISH===true){
					THREADLOAD_FINISH = false;
					$.ajax({
						type:'POST',
						url:'/llamafun/thread-show-more.php',
						data:{thread_type:THREAD_TYPE , date:DATE , hotness:HOTNESS , post_member_id:POST_MEMBER_ID},
						success:function(html){
							//alert(html);
							$('.thread-loding').hide();
							$('.thread-loding').replaceWith(html);
							THREADLOAD_FINISH = true;
						}
					});
				}
		
			}
		}
	});
	
	//排行榜popup
	$(document).on('click','.leaderboard_btn',function(){
		$('.leaderboard_popup').css('display','block');
		$('body').css('overflow','hidden');
	});
	$(document).on('click','.leaderboard_popup-close',function(){
		$('body').css('overflow','auto');
		$('.leaderboard_popup').hide();
	});
	
	//聯絡我們popup
	$(document).on('click','.contact-us',function(){
		$('.contact_popup').css('display','block');
		$('body').css('overflow','hidden');
	});
	$(document).on('click','.contact_popup-close',function(){
		$('body').css('overflow','auto');
		$('.contact_popup').hide();
	});
	
	
	
	
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
	
	if( $('.loggedin_username').length ){
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
			var LOGGEDIN_CHECK = $('.newthreadbtn').attr('id');

			if(LOGGEDIN_CHECK!=true){
				$('body').css('overflow','hidden');
				$('.login_popup').css('display','block');
			}
			else{
				check_imgfile(files[0]);
			}
			
			//$('.newthread_popup').css('display','block');
			//alert(files[0].size);
			//We need to send dropped files to Server
			//handleFileUpload(files,obj);
		});
	}
	
	$(document).on('change', '.thread_uploadimg', function() {
		//alert("1");
		var img_file = $(this)[0].files[0];
		check_imgfile(img_file);
	});

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
					url:'/llamafun/uploadimg-url-check.php',
					data:{image_url : img_url},
					success:function(response){
						var response = response.trim();
						//alert(response);
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
	
	//發文跳出popup發文畫面
	$(document).on('click','.newthreadbtn',function(){
		var LOGGEDIN_CHECK = $('.newthreadbtn').attr('id');
		if(LOGGEDIN_CHECK!=true){ //若沒登入跳出登入視窗
			$('body').css('overflow','hidden');
			$('.login_popup').css('display','block');
		}
		else{
			$('body').css('overflow','hidden');
			$('.newthread_popup').css('display','block');
		}
	});

	$(document).on('click', '.newthread_popup-close', function() {
		$('body').css('overflow','auto');
		$('.newthread_popup').hide();
	});
	
	$(document).on('click', '.newthread_post-close', function() {
		$('body').css('overflow','auto');
		$('.thread_subject_msg').css('visibility','hidden');
		$('.newthread_popup').hide();
		$('.newthread-post').hide();
		$('.newthread-upload').show();
	});
	
	$(document).on('click', '.post-backtoup', function() {
		$('.thread_subject_msg').css('visibility','hidden');
		$('.newthread-post').hide();
		$('.newthread-upload').show();
	});
	
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
				url:'/llamafun/newthread-db.php',
				data:formdata,
				processData: false,  // tell jQuery not to process the data
				contentType: false,
				success:function(response){
					//alert(response);
					var response = response.trim();
					var threadid_format = /^[\w]{8}$/; //只接收8個英文大小寫跟數字、就是文章ID
					if(response.match(threadid_format)){
						window.location = "/llamafun/thread-show.php?thread_id="+response;
					}
					else{
						alert("發文失敗喔！ 請再嘗試一次");
						$('.newthread-send').text('發文');
						$(".newthread-post *").prop('disabled',false);
					}
				}
				/*,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert(data);
				}*/
			});
		 }
	});

	$(document).on('click', '.thread_image', function() {
	//$('.gif-video').parent().click(function () {
		if($(this).children(".gif-video").get(0).paused){
			$(this).children(".gif-video").get(0).play();
			$(this).children(".playpause").hide();
			$(this).children(".rwd-playpause").hide(); //RWD用
			
		}else{
			$(this).children(".gif-video").get(0).currentTime = 0;
			$(this).children(".gif-video").get(0).play();
			$(this).children(".rwd-playpause").hide(); //RWD用
		  // $(this).children(".gif-video").get(0).pause();
			//$(this).children(".playpause").show();
		}
	});
	
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
	
	/*
	$(document).on('keyup', '.meme-bottomline', function() {
		//var INPUTLINE = $(this).attr('id');
		var x = img_newwidth/2;
		var y = img_newheight-20;
		
		if(INPUTLINE==='topline'){
			y = 50;
		}
		else if(INPUTLINE==='bottomline'){
			y = img_newheight-20;
		}
		context.drawImage(image, 0, 0, img_newwidth, img_newheight);
		context.fillText($(this).val() , x ,y);
	});*/
	
	$(document).on('click','.facebook_reg',function(){
		FB.login(function(response){
			if (response.status === 'connected') {
				// Logged into your app and Facebook.
				FB.api('/me', {fields: 'name,email'}, function(response) {
					var FB_NAME = response.name.trim();
					var FB_ID = response.id;
					
					$.ajax({
						type:'POST',
						url:'/llamafun/register-fb-to-db.php',
						data:{fb_name : FB_NAME , fb_id : FB_ID},
						success:function(response){
							var response = response.trim();
							//alert(response);
							if(response==='success_reg'){
								window.location = "/llamafun/profile.php";
							}
							else if(response==='already_reg'){
								window.location = "/llamafun/index.php";
							}
							else if(response==='banned'){
								//alert("您的帳號因違反規定已被停權！");
								//因為註冊跟登入都有FB按鈕，所以將兩個都顯示
								$('#login_message').css('visibility','visible');
								document.getElementById("login_message").innerHTML = "您的帳號因違反規定已被停權！";
								$('#register_message').css('visibility','visible');
								document.getElementById("register_message").innerHTML = "您的帳號因違反規定已被停權！";
							}

						}/*,
						error: function(data){
						//if(data.success == true){alert("失敗");}
							alert(data);
						}*/
					});
					//alert(FB_NAME+"~"+FB_ID);
				  /*console.log('Successful login for: ' + response.name);
				  document.getElementById('status').innerHTML =
					'Thanks for logging in, ' + response.name + '!'+JSON.stringify(response);*/
				});
			} 
			else{
				// The person is not logged into this app or we are unable to tell. 
			}
		}, {scope: 'public_profile'});
	});
	
	
	var messageTimer = '';
	var messageInterval = 1;
	var newmsg = true;
	function messageCount(){
		$.ajax({
			type:'GET',
			url:'/llamafun/message-count.php',
			//data:{username:USERNAME},
			success:function(response){
				//var response = data;
				var response = response.trim();
				var count_format = /^[0-9]+$/; //只接收數字
				if(response==='logout'){
					//alert("~~~~");
					//newmsg = false;
					clearInterval(messageTimer);
				}
				
				else if(response == false){
					//newmsg = false;
					$("#msg_count").hide();
					//alert("2");
				}
				else if(response.match(count_format)){
					newmsg = true;
					$("#msg_count").show();
					$("#msg_count").text(response);
					//alert("3");
				}	
			}/*,
			error: function(data){
			//if(data.success == true){alert("失敗");}
				alert(data);
			}*/
		});
	}

	//var nft_body = new SimpleBar($('#notificationsBody')[0]);
	//var testdiv = new SimpleBar($('.testdiv')[0], { autoHide: true });
	//var testdiv = new SimpleBar(document.getElementById('testdiv'));
	//testdiv.getScrollElement();
	//testdiv.SimpleBar.getScrollElement().addEventListener('scroll', function( alert("123"); ));
	//$('.testdiv').perfectScrollbar({wheelSpeed:0.5});
	//
	
	//$('#notificationsBody').optiscroll();
//	$(".testdiv").nanoScroller();
	//$('#notificationsBody').nanoScroller();
	//$('.testdiv').optiscroll({ autoUpdate: false });
	//$('#notificationsBody').optiscroll({ autoUpdate: true });
	//$(".nano").nanoScroller();
	//$('.testdiv').optiscroll('update');
	//$('.testdiv').destroy();
	//var scroller = $('.antiscroll-wrap').antiscroll().data('antiscroll');
	//$('.testdiv').antiscroll();
	//alert('123');
	/*
	$('.testdiv').on('scroll', function(){
		//if(Math.round($(this).scrollTop() + $(this).innerHeight()) >= Math.round($(this)[0].scrollHeight*0.9) ){
			//if(ev.detail.scrollbarV.percent>90){
			//	alert(ev.detail.scrollbarV.percent);
			//}
	//	}
	});*/
	
	$(".testdiv").perfectScrollbar();
	$('#notificationsBody').perfectScrollbar();
	$(document).on('click','.testdiv',function() // onclick function for notification
	{
		$('.testdiv').prepend("<div class=content><img src=https://pbs.twimg.com/profile_images/624483066795290624/U_Qn1xLH_bigger.jpg width=50px height=50px alt=profpic> 123Developerdesks shared about something</div>");

		//$('.testdiv').optiscroll('destroy');
		//$('.testdiv').optiscroll({ wrapContent: true });
	});
	$(document).on('click','#notifylink',function() // onclick function for notification
	{
		$("#notificationContainer").fadeToggle(300);   // show notification div
		$("#msg_count").fadeOut("slow");
		
		//alert(";");
		//$('#notificationsBody').prepend("<div class=content><img src=https://pbs.twimg.com/profile_images/624483066795290624/U_Qn1xLH_bigger.jpg width=50px height=50px alt=profpic> 123Developerdesks shared about something</div>");

		//$('#notificationsBody').optiscroll('update');
		//alert("~~");
		/*var listmsg = $('#notificationsBody .nft-list');
		listmsg.each(function() {
			alert($(listmsg).attr('id'));
		});*/
		
		if(newmsg==true){
			var NFT_DATE = '0';
			if( $( "#notificationsBody .nft-list" ).length ){
				var NFT_LIST_ID = $("#notificationsBody .nft-list").first().attr('id').split('_'); 
				/*	分割NFT_ID的ID
						NFT_LIST_ID = nft-list_(nft_id)_(nft_date)
						NFT_LIST_ID[0] = nft-list
						NFT_LIST_ID[1] = nft_id
						NFT_LIST_ID[2] = nft_date
				*/
				NFT_DATE = NFT_LIST_ID[2];
			}
			newmsg=false;
			$.ajax({
				type:'POST',
				url:'/llamafun/message-show.php',
				data:{nft_date:NFT_DATE},
				success:function(response){
					//alert(response);
					$('#notificationsBody').prepend(response);
					$('#notificationsBody').perfectScrollbar('update');
					
					
					var listmsg = $('#notificationsBody .nft-list-msg');//將所有nft-list-msg存入變數
					listmsg.each(function() { //each將每個讀取出來
					// don't know why but must get scrollHeight by jquery for anchors
						if (($(this).innerHeight())+10 < $(this).prop('scrollHeight')) { //若內容高度大於限制的高度、設定+10是因為不同瀏覽器高度有所差異，所以設定一個緩衝
							//alert($(this).innerHeight()+"~~~~"+$(this).prop('scrollHeight'));
							//alert("~");
							//$(listmsg).addClass('manual-ellipsis');
							$(this).addClass('manual-ellipsis'); //加入manual-ellipsis的class，將省略號...加入
						}
					});
				}/*,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert(data);
				}*/
			});
		}
		return false;
	});
	
	//RWD側邊選單通知按鈕
	$(document).on('click','.side-nft-msg',function(){
		$.ajax({
			url:'/llamafun/message-side-nft.php',
			success:function(response){
				//alert(response);
			}/*,
			error: function(data){
			//if(data.success == true){alert("失敗");}
				alert(data);
			}*/
		});
		
	});
	//通知全部已讀
	$(document).on('click','.nft-readall',function(){
			$.ajax({
				url:'/llamafun/message-read-all.php',
				success:function(response){
					$(".nft-list").removeClass('nft-unread');
					//alert(response);
					//$('#notificationsBody').prepend(response);

				}/*,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert(data);
				}*/
			});
	});
	$(document).on('click','.nft-list',function(){
		var NFT_LIST_ID = $(this).attr('id').split('_'); 
		/*	分割NFT_ID的ID
				NFT_LIST_ID = nft-list_(nft_id)_(nft_date)_(read_nft)
				NFT_LIST_ID[0] = nft-list
				NFT_LIST_ID[1] = nft_id
				NFT_LIST_ID[2] = nft_date
				NFT_LIST_ID[3] = read_nft 0等於還沒讀過 1等於讀過
		*/
		var NFT_ID = NFT_LIST_ID[1];
		var READ_NFT = NFT_LIST_ID[3];
		if(READ_NFT=='0'){
			$(this).removeClass('nft-unread');
			//alert("~~~");
			$.ajax({
				type:'POST',
				url:'/llamafun/message-read.php',
				data:{nft_id : NFT_ID},
				success:function(response){
					//alert(response);
					//$('#notificationsBody').prepend(response);

				}/*,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert(data);
				}*/
			});
		}
	});

	

	//點擊comments-dropdown-div(包含children)以外會關閉dropdown_content
	$(document).mouseup(function(e) {
		var comments_dropdown = $(".comments-dropdown");
		var dropdown_content = $(".dropdown-content");
		// if the target of the click isn't the container nor a descendant of the container
		if (!comments_dropdown.is(e.target) && !dropdown_content.is(e.target) && dropdown_content.has(e.target).length === 0) 
		{
			dropdown_content.hide();
		}
	});
	
	//關閉留言通知
	$(document).on('click','.comment-nft-onoff',function(){
		var COM_NFTONOFF_ID = $(this).attr('id').split('_');
			/*	分割comment-nft-off的ID
					COM_NFTONOFF_ID = comment-nft-off(on)_threadid
					COM_NFTONOFF_ID[0] = comment-nft-off或comment-nft-on
					COM_NFTONOFF_ID[1] = threadid
			*/
		var NFT_ONOFF = COM_NFTONOFF_ID[0];
		var THREAD_ID = COM_NFTONOFF_ID[1];
		//$('#comment-nft-off_'+THREAD_ID).replaceWith("<a id='comment-nft-on_"+THREAD_ID+" ' class='comment-nft-onoff' href='javascript: void(0)' >&#128276;開啟通知</a>");
		//$('#comment-nft-on_'+THREAD_ID).replaceWith("ABC")
		//$('.comment-nft-off'+THREAD_ID).replaceWith("<a id='comment-nft-on_"+THREAD_ID+" ' class='comment-nft-on' href='javascript: void(0)' >&#128276;開啟通知</a>");
		//alert($(this).attr('id'));
		$.ajax({
			type:'POST',
			url:'/llamafun/comment-nft-onoff.php',
			data:{  thread_id : THREAD_ID , nft_onoff : NFT_ONOFF},
			success:function(response){
				//alert(response);
				if(response==true){
					if(NFT_ONOFF==='comment-nft-off'){
						$('#comment-nft-off_'+THREAD_ID).replaceWith("<a id='comment-nft-on_"+THREAD_ID+"' class='comment-nft-onoff' href='javascript: void(0)' >開啟通知</a>");
					}
					else if(NFT_ONOFF==='comment-nft-on'){
						//alert('!!');
						//$("#comment-nft-on_"+THREAD_ID).replaceWith('123');
						$('#comment-nft-on_'+THREAD_ID).replaceWith("<a id='comment-nft-off_"+THREAD_ID+"' class='comment-nft-onoff' href='javascript: void(0)' >關閉這個文章的通知</a>");
					}
				}
			}
		});
		//alert('123');
	});
	
	//點擊comments-dropdown可以toggle dropdown-content 並關閉其他的dropdown-content
	$(document).on('click','.comments-dropdown',function(e){
		
		e.preventDefault();
		// hide all span
		var $this = $(this).parent().find('.dropdown-content');
		$(".dropdown-content").not($this).hide();
		
		// here is what I want to do
		$this.toggle();
		
	});
	/*
	$('.comments-dropdown').click(function(e){
		
		e.preventDefault();
		// hide all span
		var $this = $(this).parent().find('.dropdown-content');
		$(".dropdown-content").not($this).hide();
		
		// here is what I want to do
		$this.toggle();
		
	});*/

	//Document Click //關閉通知欄跟comments-dropdown
	$(document).click(function(){
		$("#notificationContainer").hide();     // hide notification div
		//$('.dropdown-content').hide(); 
	});

	/*
$("body").click(function(){
    var scrollPost = $(document).scrollTop();
    //alert(scrollPost);  ($(window).scrollTop() >= ($(document).height() - $(window).height())*0.8)
	//$(document).height() - win.height() == win.scrollTop()
	//alert($(document).height()+"-"+$(".notifications_page_body").height()+"="+$(window).scrollTop());
	alert($(window).scrollTop()+" >= "+$(document).height()+"-"+$(window).height());
});*/
	
	var NFTLOAD_FINISH = true;
	if( $( ".notifications_page_body .nft-list" ).length ){
		$(window).scroll(function() {
			if($(window).scrollTop() + $(window).height() >= Math.round($(document).height()*0.9)) {
				//alert("1");
				if( $('.notifications_page_body .nft_loding').length ){
					var NFT_LIST_ID = $(".notifications_page_body .nft-list").last().attr('id').split('_'); 
					/*	分割NFT_ID的ID
							NFT_LIST_ID = nft-list_(nft_id)_(nft_date)
							NFT_LIST_ID[0] = nft-list
							NFT_LIST_ID[1] = nft_id
							NFT_LIST_ID[2] = nft_date
					*/
					var NFT_DATE = NFT_LIST_ID[2];
					//alert(NFT_DATE);
					//alert(NFT_DATE);
					$('.notifications_page_body .nft_loding').show();
					if(NFTLOAD_FINISH===true){
						NFTLOAD_FINISH = false;
						$.ajax({
							type:'POST',
							url:'/llamafun/message-show-more.php',
							data:{nft_date:NFT_DATE},
							success:function(html){
								$('.notifications_page_body .nft_loding').hide();
								$('.notifications_page_body .nft_loding').replaceWith(html);
								NFTLOAD_FINISH = true;
							}
						});
					}
				}
			}
		});
	}
	
	$('#notificationsBody').bind('scroll', function(){
		 // Math.round 小數點四捨五入。*0.9表示到達90%時就開始動作
		 if(Math.round($(this).scrollTop() + $(this).innerHeight()) >= Math.round($(this)[0].scrollHeight*0.90) ){
			//$('#notificationsBody').append(" <b>Hello world!</b>");
			//alert("123");
			if( $('#notificationsBody .nft_loding').length ){
				var NFT_LIST_ID = $("#notificationsBody .nft-list").last().attr('id').split('_'); 
				/*	分割NFT_ID的ID
						NFT_LIST_ID = nft-list_(nft_id)_(nft_date)
						NFT_LIST_ID[0] = nft-list
						NFT_LIST_ID[1] = nft_id
						NFT_LIST_ID[2] = nft_date
				*/
				var NFT_DATE = NFT_LIST_ID[2];
				//alert(NFT_DATE);
				$('#notificationsBody .nft_loding').show();
				
				if(NFTLOAD_FINISH===true){
					NFTLOAD_FINISH = false;
					$.ajax({
						type:'POST',
						url:'/llamafun/message-show-more.php',
						data:{nft_date:NFT_DATE},
						success:function(html){
							
							$('#notificationsBody .nft_loding').hide();
							$('#notificationsBody .nft_loding').replaceWith(html);
							
							$('#notificationsBody').perfectScrollbar('update');
							
							var listmsg = $('#notificationsBody .nft-list-msg');//將所有nft-list-msg存入變數
							listmsg.each(function() { //each將每個讀取出來
							// don't know why but must get scrollHeight by jquery for anchors
								if (($(this).innerHeight())+10 < $(this).prop('scrollHeight')) { //若內容高度大於限制的高度、設定+10是因為不同瀏覽器高度有所差異，所以設定一個緩衝
									//$(listmsg).addClass('manual-ellipsis');
									$(this).addClass('manual-ellipsis'); //加入manual-ellipsis的class，將省略號...加入
								}
							});
							
							NFTLOAD_FINISH = true;
							//$('#notificationsBody').optiscroll('destroy');
							//$('#notificationsBody').optiscroll();
						}
					});
				}
			}
		}
	});
	
	//Popup Click
	$(document).on('click','#notificationContainer',function(){ //點通知欄的任何位置都沒動作，只有點以外的位置可以關閉通知欄
		//alert('123');
		return false;
	});
	$(document).on('click','#notificationsBody a',function(){  //這個function要放在上面那個return false之下才會有作用，點通知欄的連結可以執行超連結動作
		var url = this;
		setTimeout(function() { //因為firefox有問題點了nft-list會直接跳頁面不會執行將點過的通知設為已讀的AJAX。所以設定延遲等AJAX執行完成再跳頁面
			window.location = url;
		}, 100); 
		//window.location.assign(this);
		//return true;
	});
	/*
	$("#notificationContainer").click(function(){
		return false;
	});*/
	/*
	$(document).on('click','#notificationContainer',function(event){
		if (event.target == notificationContainer) {
			alert("123");
			//$('.register_popup').css('display','none');
		}
	});*/
	
	

	
	$("#notificationFooter").click(function(){
		window.location = "/llamafun/member_notifications.php";
	});
	

	
	//若msg_count存在則執行setInterval
	if ( $( "#msg_count" ).length ) {
		messageTimer = setInterval(function(){messageCount();}, messageInterval*1000);
		//alert($( "#msg_count" ).length);
	}
	
	//登出清除Interval
	$(document).on('click','.logout_btn',function(){
		clearInterval(messageTimer);
		//alert("登出了");
	});

	//LINE分享按鈕
	$(document).on('click','.linebtn',function(){
		var url = $(this).attr('alt');
		//alert(url);
		window.open('https://timeline.line.me/social-plugin/share?url='+encodeURIComponent(url),"_blank","toolbar=yes,location=yes,directories=no,status=no, menubar=yes,scrollbars=yes,resizable=no, copyhistory=yes,width=600,height=500");
		//document.querySelectorAll(".line-it-button");
		//$('.register_popup').css('display','block');
	});
	
	$(document).on('click','.facebook_share',function(){
		var url = $(this).attr('alt');
		FB.ui({
			method: 'share',
			display: 'popup',
			href: url,
		}, function(response){});
		
	});
	
	//註冊按鈕跳出popup註冊畫面
	$(document).on('click','.registerbtn',function(){
		//$('#nav').hide();
		$('body').css('overflow','hidden');
		$('.register_popup').css('display','block');
	});
	
	//註冊的X按鈕
	$(document).on('click','.register_popup-close',function(){
		//$('#nav').show();
		$('body').css('overflow','auto');
		$('.register_popup').css('display','none');
	});
	
	//註冊畫面的登入按鈕跳出popup登入畫面
	$(document).on('click','.register-to-login',function(){
		//$('#nav').hide();
		$('.register_popup').css('display','none');
		$('.login_popup').css('display','block');
	});
	
	//確認註冊資料所有格式都正確
	var reg_username_correct = false;
	var reg_email_correct = false;
	var reg_pw_correct = false;
	var reg_pw_recheck_correct = false;
	$(document).on('keyup change', '#register_username', function() {
		$('#error_username').css('visibility','visible');
		document.getElementById("error_username").innerHTML = "　";
		
		var username_format = /^[\u4e00-\u9fa5\w-]+$/;
		var USERNAME = document.getElementById("register_username").value;

		if(USERNAME.length < 1){
			document.getElementById("error_username").innerHTML = "暱稱不能為空喔！";
			reg_username_correct = false;
		}
		else {
			if(USERNAME.length > 12){
				document.getElementById("error_username").innerHTML = "暱稱只能小於12個字！";
				reg_username_correct = false;
			}
			else{
				
				if(!USERNAME.match(username_format)){
					document.getElementById("error_username").innerHTML = "不能使用注音　符號只能使用-或_";
					reg_username_correct = false;
				}
				else{
					$.ajax({
						type:'POST',
						url:'/llamafun/register-check-username.php',
						data:{username:USERNAME},
						success:function(response){
							if(response==true){
								reg_username_correct = true;
								document.getElementById("error_username").innerHTML = "　";
							}
							else{
								reg_username_correct = false;
								document.getElementById("error_username").innerHTML = "暱稱有人用過了喔！";
							}	
						}/*,
						error: function(data){
						//if(data.success == true){alert("失敗");}
							alert(data);
						}*/
					});
				}
			}
		}
	});
	
	$(document).on('keyup change', '#register_email', function() {
		$('#error_email').css('visibility','visible');
		document.getElementById("error_email").innerHTML = "　";
		
		var mail_format = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		var EMAIL = document.getElementById("register_email").value;
		if(EMAIL.length < 1){
			document.getElementById("error_email").innerHTML = "信箱不能為空";
			reg_email_correct = false;
		}
		else {
			if(!EMAIL.match(mail_format)){
				document.getElementById("error_email").innerHTML = "信箱格式不正確";
				reg_email_correct = false;
			}
			else{
				$.ajax({
					type:'POST',
					url:'/llamafun/register-check-email.php',
					data:{email:EMAIL},
					success:function(response){
						if(response==true){
							reg_email_correct = true;
							document.getElementById("error_email").innerHTML = "　";
						}
						else{
							reg_email_correct = false;
							document.getElementById("error_email").innerHTML = "信箱有人用過了喔！";
						}	
					}/*,
					error: function(data){
					//if(data.success == true){alert("失敗");}
						alert(data);
					}*/
				});
			}
		}
	});

	$(document).on('keyup change', '#register_password', function() {
		$('#error_password').css('visibility','visible');
		document.getElementById("error_password").innerHTML = "　";
		document.getElementById("error_password_check").innerHTML = "　";
		var PASSWORD = document.getElementById("register_password").value;
		
		$("#register_password_check").val('');
		if(PASSWORD.length < 1){
			document.getElementById("error_password").innerHTML = "密碼不能為空";
			reg_pw_correct = false;
		}
		else {
			if(PASSWORD.length < 8 || PASSWORD.length > 20){
				document.getElementById("error_password").innerHTML = "密碼長度為8~20個字喔";
				reg_pw_correct = false;
			}
			else{
				document.getElementById("error_password").innerHTML = "　";
				reg_pw_correct = true;
			}
		}
	});
	
	$(document).on('keyup change', '#register_password_check', function() {
		$('#error_password_check').css('visibility','visible');
		document.getElementById("error_password_check").innerHTML = "　";
		var PASSWORD = document.getElementById("register_password").value;
		var PASSWORD_CHECK = document.getElementById("register_password_check").value;
		if(PASSWORD != PASSWORD_CHECK){
			document.getElementById("error_password_check").innerHTML = "密碼不相符喔！";
			reg_pw_recheck_correct = false;
		}
		else{
			document.getElementById("error_password_check").innerHTML = "　";
			reg_pw_recheck_correct = true;
		}
	});
	

	//點註冊按鈕執行此function
	$(document).on('click','.popup_registerbtn',function(){
		//所有格式正確才進入傳送資料至DB
		if(reg_username_correct == true && reg_email_correct == true && reg_pw_correct == true && reg_pw_recheck_correct == true){

			var USERNAME = document.getElementById("register_username").value;
			var EMAIL = document.getElementById("register_email").value;
			var PASSWORD = document.getElementById("register_password").value;
			$.ajax({
				type:'POST',
				url:'/llamafun/register-to-db.php', //來自url.php
				data:{  username : USERNAME , email : EMAIL , password : PASSWORD },
				//dataType: "json",
				success:function(response){
					if(response==true){
						//document.getElementById("error_message").innerHTML = "註冊成功";
						window.location = "/llamafun/index.php";
					}
					else{
						//alert("註冊失敗！請再試一次~");
						$('#register_message').css('visibility','visible');
						document.getElementById("register_message").innerHTML = "註冊失敗！請再試一次~";
					}
					
				}/*,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert("失敗");
				}*/
			});
		}
	});
	
	
	//登入按鈕跳出popup登入畫面
	$(document).on('click','.loginbtn',function(){
		//$('#nav').hide();
		$('body').css('overflow','hidden');
		$('.login_popup').css('display','block');
	});
	
	//登入畫面的註冊按鈕跳出popup註冊畫面
	$(document).on('click','.login-to-register',function(){
		//$('#nav').hide();
		$('.login_popup').css('display','none');
		$('.register_popup').css('display','block');
	});

	$(document).on('click','.login_popup-close',function(){
		//$('#nav').show();
		$('body').css('overflow','auto');
		$('.login_popup').css('display','none');
	});
	
	
	
	//登入帳號
	$(document).on('click','.popup_loginbtn',function(){
		var mail_format = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		var account_format = /^[0-9a-zA-Z]+$/;

		var EMAIL = document.getElementById("login_email").value;
		var PASSWORD = document.getElementById("login_password").value;
		var all_format_correct = true;

		if(EMAIL.length < 1){
			all_format_correct = false;
		}
		
		if(PASSWORD.length < 1){
			all_format_correct = false;
		}
		
		if(all_format_correct == true){

				$.ajax({
				type:'POST',
				url:'/llamafun/login-db.php',  //來自url.php
				data:{  email : EMAIL , password : PASSWORD },
				//dataType: "json",
				success:function(response){
					if(response==true){
						window.location = "/llamafun/index.php";  //來自url.php
						//document.getElementById("error_message").innerHTML = "登入成功";
					}
					else{
						$('#login_message').css('visibility','visible');
						document.getElementById("login_message").innerHTML = response;
					}
					
				}/*,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert("失敗");
				}*/
			});
			
		}
	});

	
	//點擊newthread_popup的任何地方都可以關閉發文視窗
	/*
	$(window).on('click',function(event) {
		if (event.target == newthread_popup) {
			$('.newthread_popup').css('display','none');
		}
	});*/
	
	/*
  var $form = $(window);
  $form.on('drag dragstart dragend dragover dragenter dragleave drop', function(e) {
	  //alert("1");
    e.preventDefault();
    e.stopPropagation();
  })
  .on('dragover dragenter', function() {
	  //alert("2");
	  $('.is-dragover').css('display','block');
    //$form.addClass('is-dragover');
  })
  .on('dragleave dragend drop', function() {
	   //alert("3");
	   $('.is-dragover').css('display','none');
    //$form.removeClass('is-dragover');
  })
  .on('drop', function(e) {
	  
    droppedFiles = e.originalEvent.dataTransfer.files;
	$('.newthread_popup').css('display','block');
	//alert("DROP");
  });*/
	
	
	//按讚跟取消讚function
	$(document).on('click','.likebtn',function(){
		
		var LIKEBTN_ID = $(this).attr('id').split('_'); 
			/*	分割likebtn的ID
					LIKEBTN_ID = likebtn_threadid_member_id_loggedin_check
					LIKEBTN_ID[0] = likebtn或unlikebtn 
					LIKEBTN_ID[1] = threadid
					LIKEBTN_ID[2] = member_id
					LIKEBTN_ID[3] = loggedin_check (true或false)
			*/
		var LIKE_UNLILE = LIKEBTN_ID[0];
		var THREAD_ID = LIKEBTN_ID[1];
		var MEMBER_ID = LIKEBTN_ID[2];
		var LOGGEDIN_CHECK =LIKEBTN_ID[3];
		//alert(LOGGEDIN_CHECK);
		if(LIKE_UNLILE === "likebtn"){
			if(LOGGEDIN_CHECK!=true){
				$('body').css('overflow','hidden');
				$('.login_popup').css('display','block');
				//document.getElementById("like-com-message_"+THREAD_ID).innerHTML ="要先登入才能送拉馬喔！";
			}
			else{
				
				//document.getElementById("likebtn_"+THREAD_ID+"_"+ACCOUNT).innerHTML ="拉馬";
				document.getElementById("likebtn_"+THREAD_ID+"_"+MEMBER_ID+"_"+LOGGEDIN_CHECK).setAttribute("id","unlikebtn_"+THREAD_ID+"_"+MEMBER_ID+"_"+LOGGEDIN_CHECK);
				$("#unlikebtn_"+THREAD_ID+"_"+MEMBER_ID+"_"+LOGGEDIN_CHECK).removeClass("like-gray").addClass("unlike-blue");
				
				$.ajax({
					type:'POST',
					url:'/llamafun/thread-like.php',
					data:{  thread_id : THREAD_ID , member_id : MEMBER_ID},
					success:function(html){
						$('#likecount_'+THREAD_ID).replaceWith(html);
					}
				});
			}
		}
		else{
			document.getElementById("unlikebtn_"+THREAD_ID+"_"+MEMBER_ID+"_"+LOGGEDIN_CHECK).setAttribute("id","likebtn_"+THREAD_ID+"_"+MEMBER_ID+"_"+LOGGEDIN_CHECK);
			$("#likebtn_"+THREAD_ID+"_"+MEMBER_ID+"_"+LOGGEDIN_CHECK).removeClass("unlike-blue").addClass("like-gray");
			$.ajax({
				type:'POST',
				url:'/llamafun/thread-unlike.php',
				data:{  thread_id : THREAD_ID , member_id : MEMBER_ID },
				success:function(html){
					$('#likecount_'+THREAD_ID).replaceWith(html);
				}
			});
		}
	});
	
	//檢舉按鈕跳出popup畫面
	var report_title = null;
	var reprot_comment = null;
	var report_type = null;
	var report_member_id = null;
	var report_thread_id = null;
	var report_comment_id = null;
	$(document).on('click', '.thread-com-report', function() {
		
		var REPORT_ID = $(this).attr('id').split('_'); 
		/*	分割REPORT_ID的ID
				REPORT_ID = (thread-report或comments-report)_thread_id_loggedin_check
				REPORT_ID[0] = thread-report或comments-report
				REPORT_ID[1] = thread_id
				REPORT_ID[2] = loggedin_check
				REPORT_ID[3] = member_id
				REPORT_ID[4] = reply_comments_id (留言的檢舉才有)
		*/
		//alert($(this).attr('id'));
		var LOGGEDIN_CHECK = REPORT_ID[2];
		if(LOGGEDIN_CHECK!=true){	//若沒登入跳出登入視窗
			$('body').css('overflow','hidden');
			$('.login_popup').css('display','block');
		}
		else{
			var TYPE = REPORT_ID[0];
			//report_member_id = REPORT_ID[3];
			if(TYPE==='thread-report'){
				report_thread_id = REPORT_ID[1];
				report_type = "檢舉-文章";
				//report_title = $('#thread-title_'+report_thread_id).text();
			}
			else if(TYPE==='comments-report'){
				report_comment_id = REPORT_ID[4];
				report_type = "檢舉-留言";
				//reprot_comment = $('#thread-title_'+THREAD_ID).text();
			}
			$('.report_finished').hide(); //因為送出後會出現這個div，所以每次按下都先隱藏
			$('.report-card').css('display','block');
			$('body').css('overflow','hidden');
			$('.report_popup').css('display','block');
			
		}
	});

	$(document).on('click', '.report_popup-close', function() {
		$('body').css('overflow','auto');
		$('.report_popup').hide();
		$('.popup_report-next-unchecked').removeClass( "popup_report-next" );
		$('.report-radio-other').val('');
		$(".report-radio").find("input:radio").prop("checked", false).end().buttonset("refresh");
	});

	$(document).on('click', '.popup_report-next', function() {
		var formdata = new FormData();
		formdata.append('report_type', report_type);
		//formdata.append('report_title', 'none');
		if(report_type=='檢舉-文章'){
			//formdata.append('report_title', report_title);
			formdata.append('report_com_thread_id', report_thread_id);
		}
		else if(report_type=='檢舉-留言'){
			formdata.append('report_com_thread_id', report_comment_id);
		}
		var report_reason = $('input[name=report-radio]:checked').val();
		if($('input[name=report-radio]:checked').val()==="其他"){
			report_reason = "其他："+$('.report-radio-other').val();
		}
		//alert("~"+report_reason+report_title);
		formdata.append('report_reason', report_reason);
		
		$.ajax({
			type:'POST',
			url:'/llamafun/send-report.php',
			data:formdata,
			processData: false,  // tell jQuery not to process the data
			contentType: false,
			success:function(response){
				//alert(response);
				if(response==true){
					$('.report-card').hide();
					$('.report_finished').css('display','block');
					$('body').css('overflow','auto');
					$(".report_popup").fadeOut(4000);
					$('.popup_report-next-unchecked').removeClass( "popup_report-next" );
					$('.report-radio-other').val('');
					$(".report-radio").find("input:radio").prop("checked", false).end().buttonset("refresh");
				}
				else{
					alert('送出失敗，請再試一次');
				}
				/*
				var response = response.trim();
				if(response==="image/png" || response==="image/jpeg" || response==="image/jpg" || response==="image/gif"){
					var img_type = response;
					comment_image_check = true;
					comment_imgtype = img_type;
					comment_uploadtype = "url";
					comment_imgurl = comment_image_url;
					$('#comment-msg_'+THREAD_ID).text("");
					//$('#comment-msg_'+THREAD_ID).text("網址正確");
					$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).removeClass( "submit-comment-disabled" );
					$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',false);
				}
				else if(response === '網址錯誤'){
					$('#comment-msg_'+THREAD_ID).text('網址錯誤喔！');
				}
				else if(response === '圖片太大'){
					$('#comment-msg_'+THREAD_ID).text('上限為10MB喔！');
				}
				else if(response === '不支援網址'){
					$('#comment-msg_'+THREAD_ID).text('只能使用圖片網址喔！');
				}
				*/
			}/*,
			error: function(data){
			//if(data.success == true){alert("失敗");}
				alert(data);
			}*/
		});
		
	});
	
	$(document).on('click', 'input[name=report-radio]', function() {
		$('.popup_report-next-unchecked').addClass( "popup_report-next" );
		//$('.popup_report-next-unchecked').removeClass( "popup_report-next" );
	});
	
	
	
	var comment_uploadtype = null;
	var comment_imgfile = null;
	var comment_imgurl = null;
	var comment_imgtype = null;
	var comment_image_check = false; //確認留言有無圖片
	$(document).on('change', '.comment-image-file', function() {
		comment_image_check = false;//一開始都先設定為false、若格式接正確才會是true
		//alert("1");
		var img_file = $(this)[0].files[0];
		var img_filename = img_file.name;
		var COM_IMGFILE_ID = $(this).attr('id').split('_');
		/*	分割submit-comment的ID
			COM_IMGFILE_ID = comment-image-file_threadid_loggedin_check
			COM_IMGFILE_ID[0] = comment-image-file
			COM_IMGFILE_ID[1] = threadid
			COM_IMGFILE_ID[2] = loggedin_check (true或false)
		*/
		var THREAD_ID = COM_IMGFILE_ID[1];
		var LOGGEDIN_CHECK = COM_IMGFILE_ID[2];
		
		if(typeof(img_file) != "undefined" && img_file != null){
			var img_type = img_file.type;
			if(img_type==="image/png" || img_type==="image/jpeg" || img_type==="image/jpg" || img_type==="image/gif"){
				var img_size = img_file.size/1024/1024; //除1024等於KB、再除1024等於MB
				//alert(img_size);
				if(img_size<=10){
					comment_image_check = true;
					comment_imgtype = img_type;
					comment_uploadtype = "file";
					comment_imgfile = img_file;
					$('#comment-msg_'+THREAD_ID).text(img_filename);
					$('#comment-upload-file_'+THREAD_ID).hide();
					$('#comment-upload-cancel_'+THREAD_ID+'_'+LOGGEDIN_CHECK).css('display','inline-block');
					$('#comment-image-url_'+THREAD_ID+'_'+LOGGEDIN_CHECK).prop('disabled',true);
				}
				else{
					alert("Daddy Too Big!!!!  最大10MB喔!!!");
					$("#comment-image-file_"+THREAD_ID+'_'+LOGGEDIN_CHECK).val('');
				}

			}
			else{
				alert("只能上傳圖片檔喔！JPG、PNG、GIF");
				$("#comment-image-file_"+THREAD_ID+'_'+LOGGEDIN_CHECK).val('');
			}
		}
	});
	
	$(document).on('click', '.comment-upload-cancel', function() {
		comment_image_check = false; //取消上傳圖片將確認圖片改為false
		var COM_CANCEL_ID = $(this).attr('id').split('_');
		/*	分割submit-comment的ID
			COM_CANCEL_ID = comment-upload-cancel_threadid_loggedin_check
			COM_CANCEL_ID[0] = comment-upload-cancel
			COM_CANCEL_ID[1] = threadid
			COM_CANCEL_ID[2] = loggedin_check (true或false)
		*/
		var THREAD_ID = COM_CANCEL_ID[1];
		var LOGGEDIN_CHECK = COM_CANCEL_ID[2];
		
		$("#comment-image-file_"+THREAD_ID+'_'+LOGGEDIN_CHECK).val('');
		$('#comment-upload-cancel_'+THREAD_ID+'_'+LOGGEDIN_CHECK).hide();
		$('#comment-upload-file_'+THREAD_ID).css('display','inline-block');
		$('#comment-image-url_'+THREAD_ID+'_'+LOGGEDIN_CHECK).prop('disabled',false);
		$('#comment-msg_'+THREAD_ID).text("");
	});

	var globalTimeout_comment_img = null;
	$(document).on('keyup', '.comment-image-url', function() {
		comment_image_check = false;//一開始都先設定為false、若格式接正確才會是true
		var COM_IMGURL_ID = $(this).attr('id').split('_');
		/*	分割submit-comment的ID
			COM_IMGURL_ID = comment-image-url_threadid_loggedin_check
			COM_IMGURL_ID[0] = comment-image-url
			COM_IMGURL_ID[1] = threadid
			COM_IMGURL_ID[2] = loggedin_check (true或false)
		*/
		var THREAD_ID = COM_IMGURL_ID[1];
		var LOGGEDIN_CHECK = COM_IMGURL_ID[2];

		if(LOGGEDIN_CHECK==true){
			if (globalTimeout_comment_img != null) {
				clearTimeout(globalTimeout_comment_img);
			}
			
			globalTimeout_comment_img = setTimeout(function() {
				//alert("~");
				globalTimeout_comment_img = null;  

				var comment_image_url = $("#comment-image-url_"+THREAD_ID+"_"+LOGGEDIN_CHECK).val();
				//$('.comment-msg_'+THREAD_ID).css('visibility','visible');
				
				if(comment_image_url.length === 0){ //若長度為0是將關閉的按鈕都開啟
					$('#comment-upload-file_'+THREAD_ID).removeClass( "comment-upload-disabled" );
					$('#comment-image-file_'+THREAD_ID+'_'+LOGGEDIN_CHECK).prop('disabled',false);
					
					$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).removeClass( "submit-comment-disabled" );
					$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',false);
					
					$('#comment-msg_'+THREAD_ID).text('');
				}
				else{
					//ajax完成前先將所有按鈕關閉
					$('#comment-upload-file_'+THREAD_ID).addClass( "comment-upload-disabled" );
					$('#comment-image-file_'+THREAD_ID+'_'+LOGGEDIN_CHECK).prop('disabled',true);
					
					$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).addClass( "submit-comment-disabled" );
					$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',true);
					
					$('#comment-msg_'+THREAD_ID).text('載入中...');
					$.ajax({
						type:'POST',
						url:'/llamafun/uploadimg-url-check.php',
						data:{image_url : comment_image_url},
						success:function(response){
							var response = response.trim();
							if(response==="image/png" || response==="image/jpeg" || response==="image/jpg" || response==="image/gif"){
								var img_type = response;
								comment_image_check = true;
								comment_imgtype = img_type;
								comment_uploadtype = "url";
								comment_imgurl = comment_image_url;
								$('#comment-msg_'+THREAD_ID).text("");
								//$('#comment-msg_'+THREAD_ID).text("網址正確");
								$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).removeClass( "submit-comment-disabled" );
								$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',false);
							}
							else if(response === '網址錯誤'){
								$('#comment-msg_'+THREAD_ID).text('網址錯誤喔！');
							}
							else if(response === '圖片太大'){
								$('#comment-msg_'+THREAD_ID).text('上限為10MB喔！');
							}
							else if(response === '不支援網址'){
								$('#comment-msg_'+THREAD_ID).text('只能使用圖片網址喔！');
							}
							
						}/*,
						error: function(data){
						//if(data.success == true){alert("失敗");}
							alert(data);
						}*/
					});
					//ajax code
				}
			}, 500); 
		}
		else{
			$('body').css('overflow','hidden');
			$('.login_popup').css('display','block');
		}
	});

	/*$(document).on('keyup','.textarea-comment',function(){
		var CommentHeight=$(this).height();
		var Com_ScrollHeight = $(this).prop('scrollHeight');
		Com_ScrollHeight -= 4;
		//CommentHeight=Math.max($(this).scrollHeight,CommentHeight);
		alert(Com_ScrollHeight+"~~"+CommentHeight);
		//if(Com_ScrollHeight>$(this).innerHeight()){
			$(this).css('height','auto');
			$(this).height(Com_ScrollHeight);
			//$(this).style.height=CommentHeight+'px';
		//}
		//else if(){
			
			
		//}
	});*/
	
	$(document).on('keyup','.textarea-comment',function(){
		$(this).css('height','auto');
		$(this).height($(this).prop('scrollHeight')-4+'px'); //-4是因為原本高度設定為25、而scrollHeight最低是29所以-4回復原本大小25
		//alert($(this).prop('scrollHeight')-4);
	});
	
	$(document).on('click','.textarea-comment',function(){
		//COMMENT = $(this).val();
		//alert(COMMENT.length);

		//$(this).css( "height", '25px' );

	});
	
	//傳送留言function
	 $(document).on('click','.submit-comment',function(){
		var SEND_COM_ID = $(this).attr('id').split('_');
		/*	分割submit-comment的ID
			SEND_COM_ID = submit-comment_threadid_loggedin_check
			SEND_COM_ID[0] = submit-comment
			SEND_COM_ID[1] = threadid
			SEND_COM_ID[2] = loggedin_check (true或false)

		*/
		var LOGGEDIN_CHECK = SEND_COM_ID[2];

		if(LOGGEDIN_CHECK != true){
			//var THREAD_ID = SEND_COM_ID[0];
			$('body').css('overflow','hidden');
			$('.login_popup').css('display','block');
			//document.getElementById("like-com-message_"+THREAD_ID).innerHTML ="要先登入才能回覆喔！";
		}
		else{
			var THREAD_ID = SEND_COM_ID[1];
			var COMMENT = $("textarea#textarea-comment_"+THREAD_ID).val();
			var format = /(?!^\s+$)^(.|\n)+$/;//不允許全部空白無任何文字或符號，但文字之間可以有空白
			var format_correct = true;
			var formdata = new FormData();
			
			if(COMMENT.length < 1){
				//document.getElementById("thread_subject_msg").innerHTML ="還沒有填標題喔！";
				format_correct = false;
			}
			else{
				//alert('123');
				if(!COMMENT.match(format)){
					//document.getElementById("thread_subject_msg").innerHTML ="還沒有填標題喔！";
					format_correct = false;
					COMMENT = "";
				}
				if(COMMENT.length>250){
					format_correct = false;
					alert('文字限制為250字喔！');
				}
			}
			
			formdata.append('comment_uploadtype', 'none');
			formdata.append('comment_imgtype','none');
			if(comment_image_check === true){
				format_correct = true;
				formdata.append('comment_uploadtype', comment_uploadtype); //url或是file
				formdata.append('comment_imgtype', comment_imgtype);
				if(comment_uploadtype==="file"){
					formdata.append('comment_uploadimg', comment_imgfile);
					//alert(comment_imgfile.name);
				}
				else if(comment_uploadtype==="url"){
					formdata.append('comment_uploadimg', comment_imgurl);
					//alert(comment_imgurl);
				}
			}

			if(format_correct === true){
				//alert("可以送出留言");
				//將圖片放入formdata中，格式為formdata.append('name', 'value')，相當於POST的name
				formdata.append('thread_id', THREAD_ID);
				formdata.append('comment', COMMENT);
				$("textarea#textarea-comment_"+THREAD_ID).prop('disabled',true); //關閉留言輸入框
				$('#comment-image-url_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',true); //關閉圖片URL輸入框
				$('#comment-image-file_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',true);//關閉上傳圖片
				$('#comment-upload-file_'+THREAD_ID).addClass( "comment-upload-disabled" );
				$('#comment-upload-cancel_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',true);//關閉取消上傳圖片按鈕
				$('#comment-upload-cancel_'+THREAD_ID+"_"+LOGGEDIN_CHECK).addClass( "comment-upload-disabled" );
				$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).addClass( "submit-comment-disabled" );
				$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',true);//關閉送出按鈕
				
				$('#comment-msg_'+THREAD_ID).hide();
				$('#send-comment-msg_'+THREAD_ID).show();

				$.ajax({
					type:'POST',
					url:'/llamafun/send-comment.php',
					data:formdata,
					processData: false,  // tell jQuery not to process the data
					contentType: false,
					success:function(response){
						//alert(response);
						if(response!=false){
							comment_image_check = false; //傳送完成後要設定為false，不然再次在同個頁面送出回覆會一直有圖片
							comment_imgtype = null;	//將類別清除
							comment_imgfile = null;	//將圖片檔案清除
							comment_imgurl = null; //將圖片URL清除

							$("#comment-image-file_"+THREAD_ID+'_'+LOGGEDIN_CHECK).val(''); //清空上傳檔案
							$('#comment-upload-cancel_'+THREAD_ID+'_'+LOGGEDIN_CHECK).hide(); //隱藏取消檔案按鈕
							$('#comment-upload-file_'+THREAD_ID).css('display','inline-block'); //顯示上傳按鈕
							$("#comment-image-url_"+THREAD_ID+"_"+LOGGEDIN_CHECK).val(''); //清空圖片URL
							$("#textarea-comment_"+THREAD_ID).val(''); //清空留言
							$("#textarea-comment_"+THREAD_ID).height('25px');//將留言框回復為預設高度
							$('#comment-msg_'+THREAD_ID).text("");//清空URL下方訊息
							
							$('#div-comments_'+THREAD_ID).prepend(response); //顯示留言

							$('#comment-image-file_'+THREAD_ID+'_'+LOGGEDIN_CHECK).prop('disabled',false);
							$('#comment-upload-file_'+THREAD_ID).removeClass( "comment-upload-disabled" );
							$('#comment-upload-cancel_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',false);//開啟取消上傳圖片按鈕
							$('#comment-upload-cancel_'+THREAD_ID+"_"+LOGGEDIN_CHECK).removeClass( "comment-upload-disabled" );
							$('#comment-image-url_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',false); //開啟圖片URL輸入框
							
						}
						else{
							alert("送出留言失敗！ 請再試一次");
							if(comment_uploadtype==="file"){ //若使用檔案，則開啟跟檔案相關的
								$('#comment-image-file_'+THREAD_ID+'_'+LOGGEDIN_CHECK).prop('disabled',false);
								$('#comment-upload-file_'+THREAD_ID).removeClass( "comment-upload-disabled" );
								$('#comment-upload-cancel_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',false);//開啟取消上傳圖片按鈕
								$('#comment-upload-cancel_'+THREAD_ID+"_"+LOGGEDIN_CHECK).removeClass( "comment-upload-disabled" );
							}
							else if(comment_uploadtype==="url"){
								$('#comment-image-url_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',false); //開啟圖片URL輸入框
							}
						}
						$("textarea#textarea-comment_"+THREAD_ID).prop('disabled',false);
						$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).removeClass( "submit-comment-disabled" ); //開啟送出按鈕
						$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).prop('disabled',false);
						$('#comment-msg_'+THREAD_ID).show(); 
						$('#send-comment-msg_'+THREAD_ID).hide(); //隱藏傳送中訊息
					}
					/*,
					error: function(data){
					//if(data.success == true){alert("失敗");}
						alert("失敗");
					}*/
				});
			}
		}
    });

	//留言GIF暫停播放
/*	$('.comment-gif-video').parent().click(function () {
		if($(this).children(".comment-gif-video").get(0).paused){
			$(this).children(".comment-gif-video").get(0).play();
			$(this).children(".comment-playpause").hide();
		}else{
		   $(this).children(".comment-gif-video").get(0).pause();
			$(this).children(".comment-playpause").show();
		}
	});*/
	
	//顯示更多留言function
	$(document).on('click','.show_more',function(){
		var THREAD_ID_COMMENTS_DATE = $(this).attr('id').split('_'); 
		/*	分割show_more的ID
			THREAD_ID_COMMENTS_DATE = show_more-(thread_id)-(comments_date)_(divreply)_reply_com_id)_(nft_rcid)_(hot_comments_arr)
			THREAD_ID_COMMENTS_DATE[0] = show_more 
			THREAD_ID_COMMENTS_DATE[1] = thread_id
			THREAD_ID_COMMENTS_DATE[2] = comments_date
			THREAD_ID_COMMENTS_DATE[3] = divreply_reply_com_id
			THREAD_ID_COMMENTS_DATE[4] = nft_rcid
			THREAD_ID_COMMENTS_DATE[5] = hot_comments_arr
			
		*/
		var THREAD_ID = THREAD_ID_COMMENTS_DATE[1];
		var COMMENTS_DATE = THREAD_ID_COMMENTS_DATE[2];
		var DIVREPLY_COM_ID = THREAD_ID_COMMENTS_DATE[3];
		var NFT_RCID = THREAD_ID_COMMENTS_DATE[4];
		var HOT_COM_ID = THREAD_ID_COMMENTS_DATE[5];
		//alert(THREAD_ID_COMMENTS_DATE[5]);
		//$('#show-more_'+THREAD_ID+'_'+COMMENTS_DATE+"_"+DIVREPLY_COM_ID+"_"+NFT_RCID).hide();
		$(this).hide();
		//$('.show_more').hide(); .css('display','block');
		//alert(THREAD_ID+'_'+COMMENTS_DATE+"_"+DIVREPLY_COM_ID+"_"+NFT_RCID);
		$('#loding-'+THREAD_ID).show();
		//$('.loding').show();
		
		$.ajax({
			type:'POST',
			url:'/llamafun/show-more-comments.php',
			data:{  thread_id : THREAD_ID , comments_date: COMMENTS_DATE , divreply_reply_com_id:DIVREPLY_COM_ID , nft_rcid:NFT_RCID , hot_com_id:HOT_COM_ID},
			success:function(html){
				//alert(html);
				$('#show_more_main'+THREAD_ID+"_"+DIVREPLY_COM_ID).replaceWith(html)
				//$('#show_more_main'+THREAD_ID+"_"+DIVREPLY_COM_ID).remove();
				//alert(DIVREPLY_COM_ID);
				//$('#comments-list_'+DIVREPLY_COM_ID).append(html);
			}
		});
	});

	//顯示更多回覆function
	$(document).on('click','.reply_showmore',function(){
		
		var reply_showmore_id = $(this).attr('id').split('_'); 
		/*	分割show_more的ID
			reply_showmore_id = reply-showmore_-thread_id-comments_date-divreply_reply_com_id-reply_comments_id_reply_rcid_nft_target_rcid
			reply_showmore_id[0] = reply_showmore_main
			reply_showmore_id[1] = thread_id
			reply_showmore_id[2] = comments_date
			reply_showmore_id[3] = divreply_reply_com_id
			reply_showmore_id[4] = reply_comments_id
			reply_showmore_id[5] = reply_rcid
			reply_showmore_id[6] = nft_target_rcid

		*/

		var THREAD_ID = reply_showmore_id[1];
		var COMMENTS_DATE = reply_showmore_id[2];
		var DIVREPLY_COM_ID = reply_showmore_id[3];
		var REPLY_COM_ID = reply_showmore_id[4];
		var REPLY_RCID = reply_showmore_id[5];
		var NFT_TARGET_RCID = reply_showmore_id[6];
		//alert(THREAD_ID+"~"+COMMENTS_DATE+"~"+DIVREPLY_COM_ID+"~"+REPLY_COM_ID);
		//$('#reply-showmore_'+THREAD_ID+"_"+COMMENTS_DATE+"_"+DIVREPLY_COM_ID+"_"+REPLY_COM_ID+"_"+REPLY_RCID+"_"+NFT_TARGET_RCID).hide();
		$('.reply-showmore_'+REPLY_COM_ID).hide();
		$('#loding-'+REPLY_COM_ID).show();
		
		$.ajax({
			type:'POST',
			url:'/llamafun/show-more-reply.php',
			data:{  thread_id : THREAD_ID , comments_date: COMMENTS_DATE , divreply_reply_com_id : DIVREPLY_COM_ID, reply_com_id : REPLY_COM_ID , reply_rcid:REPLY_RCID , nft_target_rcid:NFT_TARGET_RCID },
			success:function(html){
				$('#reply_showmore_main'+REPLY_COM_ID).replaceWith(html);
				//$('#reply_showmore_main'+REPLY_COM_ID).remove();
				//$('#divreply_'+DIVREPLY_COM_ID).remove();
				//$('#reply-list_'+REPLY_COM_ID).append(html);
				//('#div-reply_'+REPLY_COM_ID).append(html);
				
				
				//$('#comments-list_'+DIVREPLY_COM_ID).append(html);
			}
		});
	});
	
	$(document).on('click','.comment-gif-video',function(){
		var video_src = $(this).find('source').attr("src");
		$('body').css('overflow','hidden');
		$('.comment_img_popup').css('display','block');
		$('.com_popup_video').first().attr('src',video_src);
		$('.com_popup_img-div').hide();
		$('.com_popup_video-div').show();
		//var video = $(this).first().find('src');
		//var video_src  = $('.source_src').attr("src");
		//alert(video_src);
	});
	$(document).on('click','.comment-image',function(){
		var img_src = $(this).attr("src");
		$('body').css('overflow','hidden');
		$('.comment_img_popup').css('display','block');
		$('.com_popup_img').attr('src',img_src);
		$('.com_popup_img-div').show();
		$('.com_popup_video-div').hide();
		//alert(img_src);
	});
	$(document).on('click','.comment_img_popup-close',function(){
		$('body').css('overflow','auto');
		$('.comment_img_popup').css('display','none');
	});
	$(document).on('click','.rwd-comment_img_popup-close',function(){ //RWD使用
		$('body').css('overflow','auto');
		$('.comment_img_popup').css('display','none');
	});
	
	
	var AT_MEMBER_ID;
	var NFT_REPLY_RCID;
	//點回覆按鈕跳出輸入框的function
	$(document).on('click','.replybtn',function(){
		$('.divreply-form').hide();
		
		var REPLYBTN_ID = $(this).attr('id').split('_'); 
		/*	分割replybtn的ID
			REPLYBTN_ID = reply_(reply_comments_id)_(comments_member_id)_username_nft_reply_rcid 
			REPLYBTN_ID[0] = reply 
			REPLYBTN_ID[1] = reply_comments_id
			REPLYBTN_ID[2] = comments_member_id
			REPLYBTN_ID[3] = username
			REPLYBTN_ID[4] = nft_reply_rcid 
		*/
		var REPLY_COM_ID = REPLYBTN_ID[1];
		AT_MEMBER_ID = REPLYBTN_ID[2];
		var USERNAME = REPLYBTN_ID[3];
		NFT_REPLY_RCID = REPLYBTN_ID[4];
		$('#divreply-form_'+REPLY_COM_ID).show();
		document.getElementById("replyto_"+REPLY_COM_ID).innerHTML ="回覆至："+USERNAME;

	});
	
	var reply_uploadtype = null;
	var reply_imgfile = null;
	var reply_imgurl = null;
	var reply_imgtype = null;
	var reply_image_check = false; //確認留言有無圖片
	$(document).on('change', '.reply-image-file', function() {
		reply_image_check = false;//一開始都先設定為false、若格式接正確才會是true
		//alert("1");
		var img_file = $(this)[0].files[0];
		var img_filename = img_file.name;
		var REPLY_IMGFILE_ID = $(this).attr('id').split('_');
		/*	分割submit-comment的ID
			REPLY_IMGFILE_ID = reply-image-file_reply_comments_id_loggedin_check
			REPLY_IMGFILE_ID[0] = reply-image-file
			REPLY_IMGFILE_ID[1] = reply_comments_id
			REPLY_IMGFILE_ID[2] = loggedin_check (true或false)
		*/
		var REPLY_COM_ID = REPLY_IMGFILE_ID[1];
		var LOGGEDIN_CHECK = REPLY_IMGFILE_ID[2];
		
		if(typeof(img_file) != "undefined" && img_file != null){
			var img_type = img_file.type;
			if(img_type==="image/png" || img_type==="image/jpeg" || img_type==="image/jpg" || img_type==="image/gif"){
				var img_size = img_file.size/1024/1024; //除1024等於KB、再除1024等於MB
				//alert(img_size);
				if(img_size<=10){
					reply_image_check = true;
					reply_imgtype = img_type;
					reply_uploadtype = "file";
					reply_imgfile = img_file;
					$('#comment-reply-msg_'+REPLY_COM_ID).text(img_filename);
					$('#reply-upload-file_'+REPLY_COM_ID).hide();
					$('#reply-upload-cancel_'+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).css('display','inline-block');
					$('#reply-image-url_'+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).prop('disabled',true);
				}
				else{
					alert("Daddy Too Big!!!!  最大10MB喔!!!");
					$("#reply-image-file_"+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).val('');
				}

			}
			else{
				alert("只能上傳圖片檔喔！JPG、PNG、GIF");
				$("#reply-image-file_"+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).val('');
			}
		}
	});
	
	$(document).on('click', '.reply-upload-cancel', function() {
		reply_image_check = false; //取消上傳圖片將確認圖片改為false
		var REPLY_CANCEL_ID = $(this).attr('id').split('_');
		/*	分割submit-comment的ID
			REPLY_CANCEL_ID = reply-upload-cancel_reply_comments_id_loggedin_check
			REPLY_CANCEL_ID[0] = reply-upload-cancel
			REPLY_CANCEL_ID[1] = reply_comments_id
			REPLY_CANCEL_ID[2] = loggedin_check (true或false)
		*/
		var REPLY_COM_ID = REPLY_CANCEL_ID[1];
		var LOGGEDIN_CHECK = REPLY_CANCEL_ID[2];
		
		$("#reply-image-file_"+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).val('');
		$('#reply-upload-cancel_'+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).hide();
		$('#reply-upload-file_'+REPLY_COM_ID).css('display','inline-block');;
		$('#reply-image-url_'+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).prop('disabled',false);
		$('#comment-reply-msg_'+REPLY_COM_ID).text('');
	});
	
	var globalTimeout_reply_img = null;
	$(document).on('keyup', '.reply-image-url', function() {
		reply_image_check = false;//一開始都先設定為false、若格式接正確才會是true
		var REPLY_IMGURL_ID = $(this).attr('id').split('_');
		/*	分割submit-comment的ID
			REPLY_IMGURL_ID = reply-image-url_reply_comments_id_loggedin_check
			REPLY_IMGURL_ID[0] = reply-image-url
			REPLY_IMGURL_ID[1] = reply_comments_id
			REPLY_IMGURL_ID[2] = loggedin_check (true或false)
		*/
		var REPLY_COM_ID = REPLY_IMGURL_ID[1];
		var LOGGEDIN_CHECK = REPLY_IMGURL_ID[2];

		if(LOGGEDIN_CHECK==true){
			if (globalTimeout_reply_img != null) {
				clearTimeout(globalTimeout_reply_img);
			}
			
			globalTimeout_reply_img = setTimeout(function() {
				//alert("~");
				globalTimeout_reply_img = null;  

				var reply_image_url = $("#reply-image-url_"+REPLY_COM_ID+"_"+LOGGEDIN_CHECK).val();
				
				if(reply_image_url.length === 0){ //若長度為0是將關閉的按鈕都開啟
					$('#reply-upload-file_'+REPLY_COM_ID).removeClass( "reply-upload-disabled" );
					$('#reply-image-file_'+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).prop('disabled',false);
					
					$('.submit-reply').removeClass( "submit-reply-disabled" );
					$('.submit-reply').prop('disabled',false);
					
					//$('#submit-reply_'+REPLY_COM_ID+"_"+LOGGEDIN_CHECK).removeClass( "submit-reply-disabled" );
					//$('#submit-reply_'+REPLY_COM_ID+"_"+LOGGEDIN_CHECK).prop('disabled',false);
					
					$('#comment-reply-msg_'+REPLY_COM_ID).text('');
				}
				else{
					//ajax完成前先將所有按鈕關閉
					$('#reply-upload-file_'+REPLY_COM_ID).addClass( "reply-upload-disabled" );
					$('#reply-image-file_'+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).prop('disabled',true);
					
					$('.submit-reply').addClass( "submit-reply-disabled" );
					$('.submit-reply').prop('disabled',true);
					
					$('#comment-reply-msg_'+REPLY_COM_ID).text('載入中...');
					$.ajax({
						type:'POST',
						url:'/llamafun/uploadimg-url-check.php',
						data:{image_url : reply_image_url},
						success:function(response){
							var response = response.trim();
							if(response==="image/png" || response==="image/jpeg" || response==="image/jpg" || response==="image/gif"){
								var img_type = response;
								reply_image_check = true;
								reply_imgtype = img_type;
								reply_uploadtype = "url";
								reply_imgurl = reply_image_url;
								$('#comment-reply-msg_'+REPLY_COM_ID).text('');
								//$('#comment-msg_'+THREAD_ID).text("網址正確");
								$('.submit-reply').removeClass( "submit-reply-disabled" );
								$('.submit-reply').prop('disabled',false);
							}
							else if(response === '網址錯誤'){
								$('#comment-reply-msg_'+REPLY_COM_ID).text('網址錯誤喔！');
							}
							else if(response === '圖片太大'){
								$('#comment-reply-msg_'+REPLY_COM_ID).text('上限為10MB喔！');
							}
							else if(response === '不支援網址'){
								$('#comment-reply-msg_'+REPLY_COM_ID).text('只能使用圖片網址喔！');
							}
							
						}/*,
						error: function(data){
						//if(data.success == true){alert("失敗");}
							alert(data);
						}*/
					});
					//ajax code
				}
			}, 500); 
		}
		else{
			$('body').css('overflow','hidden');
			$('.login_popup').css('display','block');
		}
	});
	
	
	$(document).on('keyup','.textarea-reply',function(){
		$(this).css('height','auto');
		$(this).height($(this).prop('scrollHeight')-4+'px'); //-4是因為原本高度設定為25、而scrollHeight最低是29所以-4回復原本大小25
		//alert($(this).prop('scrollHeight')-4);
	});
	$(document).on('keyup','.textarea-reply',function(){
		//('123');
		//alert($(this).prop('scrollHeight')-4);
	});
	//傳送留言的回覆function
	 $(document).on('click','.submit-reply',function(){
		 //alert(AT_MEMBER_ID);
		var REPLYBTN_ID = $(this).attr('id').split('_'); 
			/*	分割likebtn的ID
				REPLYBTN_ID = reply_(reply_comments_id)_threadid_loggedin_check
				REPLYBTN_ID[0] = reply 
				REPLYBTN_ID[1] = reply_comments_id
				REPLYBTN_ID[2] = threadid
				REPLYBTN_ID[3] = loggedin_check (true或false)
			*/
		var LOGGEDIN_CHECK = REPLYBTN_ID[3];;

		if(LOGGEDIN_CHECK != true){
			$('body').css('overflow','hidden');
			$('.login_popup').css('display','block');
			//document.getElementById("like-reply-message_"+THREAD_ID+"_"+COMMENTS_ID+"_"+COM_ACCOUNT).innerHTML ="要先登入才能回覆喔！";
		}
		else{
			var REPLY_COM_ID = REPLYBTN_ID[1];
			var THREAD_ID = REPLYBTN_ID[2];
			var COMMENT_REPLY = $("textarea#textarea-reply_"+REPLY_COM_ID).val();
			var reply_format = /(?!^\s+$)^(.|\n)+$/;//不允許全部使用空格沒文字，若空格後面有文字則可，但字之間也可以有空白
			var reply_format_correct = true;
			var formdata = new FormData();

			if(COMMENT_REPLY.length < 1){
				//document.getElementById("thread_subject_msg").innerHTML ="還沒有填標題喔！";
				reply_format_correct = false;
			}
			else{
				if(!COMMENT_REPLY.match(reply_format)){
					//document.getElementById("thread_subject_msg").innerHTML ="還沒有填標題喔！";
					reply_format_correct = false;
					COMMENT_REPLY = "";
				}
				if(COMMENT_REPLY.length>250){
					reply_format_correct = false;
					alert('文字限制為250字喔！');
				}
			}
	
			formdata.append('reply_uploadtype', 'none');
			formdata.append('reply_imgtype','none');
			//alert(reply_uploadtype);
			//alert(reply_imgtype);
			if(reply_image_check === true){
				reply_format_correct = true;
				formdata.append('reply_uploadtype', reply_uploadtype); //url或是file
				formdata.append('reply_imgtype', reply_imgtype);
				if(reply_uploadtype==="file"){
					formdata.append('reply_uploadimg', reply_imgfile);
					//alert(reply_imgfile.name);
				}
				else if(reply_uploadtype==="url"){
					formdata.append('reply_uploadimg', reply_imgurl);
					//alert(reply_imgurl);
				}
			}
	
			if(reply_format_correct===true){
				//alert("可以送出");
				//將圖片放入formdata中，格式為formdata.append('name', 'value')，相當於POST的name
				formdata.append('reply_com_id', REPLY_COM_ID);
				formdata.append('thread_id', THREAD_ID);
				formdata.append('at_member_id', AT_MEMBER_ID);
				formdata.append('comment_reply', COMMENT_REPLY);
				formdata.append('nft_reply_rcid', NFT_REPLY_RCID);
				
				$(".divreply-form *").prop('disabled',true);
				$('#reply-upload-file_'+REPLY_COM_ID).addClass( "reply-upload-disabled" );
				$('#reply-upload-cancel_'+REPLY_COM_ID+"_"+LOGGEDIN_CHECK).addClass( "reply-upload-disabled" );
				$('.submit-reply').addClass( "submit-reply-disabled" );
				
				$('#comment-reply-msg_'+REPLY_COM_ID).hide();
				$('#send-reply-msg_'+REPLY_COM_ID).show();
				
				
				/*

				//將圖片放入formdata中，格式為formdata.append('name', 'value')，相當於POST的name
				formdata.append('reply_com_id', REPLY_COM_ID);
				formdata.append('thread_id', THREAD_ID);
				formdata.append('at_member_id', AT_MEMBER_ID);
				formdata.append('comment_reply', COMMENT_REPLY);
				formdata.append('reply_image', reply_image);
				formdata.append('nft_reply_rcid', NFT_REPLY_RCID);
				*/
				
				$.ajax({
					type:'POST',
					url:'/llamafun/comment-reply.php',
					data:formdata,
					processData: false,  // tell jQuery not to process the data
					contentType: false,
					success:function(response){		
						//alert(response);
						if(response!=false){
							reply_image_check = false;//傳送完成後要設定為false，不然再次在同個頁面送出回覆會一直有圖片
							reply_uploadtype = null;  //將類別清除
							reply_imgfile = null;	//將圖片檔案清除
							reply_imgurl = null; //將圖片URL清除
							
							$("#reply-image-file_"+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).val(''); //清空上傳檔案
							$('#reply-upload-cancel_'+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).hide(); //隱藏取消檔案按鈕
							$('#reply-upload-file_'+REPLY_COM_ID).css('display','inline-block'); //顯示上傳按鈕
							$("#reply-image-url_"+REPLY_COM_ID+"_"+LOGGEDIN_CHECK).val(''); //清空圖片URL
							$("textarea#textarea-reply_"+REPLY_COM_ID).val('');　//清空留言
							$("textarea#textarea-reply_"+REPLY_COM_ID).height('25px');
							$('#comment-reply-msg_'+REPLY_COM_ID).text('');　//清空URL下方訊息訊息
							
							$(".divreply-form *").prop('disabled',false); //開啟全部功能
							$('#reply-upload-file_'+REPLY_COM_ID).removeClass( "reply-upload-disabled" );
							$('#reply-upload-cancel_'+REPLY_COM_ID+"_"+LOGGEDIN_CHECK).removeClass( "reply-upload-disabled" );
							
							
							$('.divreply-form').hide(); //送出後關閉回覆表單
							$('#div-reply_'+REPLY_COM_ID).append(response);
						}
						else{
							alert("送出留言失敗！ 請再試一次");
							$("textarea#textarea-reply_"+REPLY_COM_ID).prop('disabled',false);
							if(reply_uploadtype==="file"){
								$("#reply-image-file_"+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).prop('disabled',false);
								$('#reply-upload-file_'+REPLY_COM_ID).removeClass( "reply-upload-disabled" );
								$('#reply-upload-cancel_'+REPLY_COM_ID+'_'+LOGGEDIN_CHECK).prop('disabled',false);//開啟取消上傳圖片按鈕
								$('#reply-upload-cancel_'+REPLY_COM_ID+"_"+LOGGEDIN_CHECK).removeClass( "reply-upload-disabled" );
							}
							else if(reply_uploadtype==="url"){
								$("#reply-image-url_"+REPLY_COM_ID+"_"+LOGGEDIN_CHECK).prop('disabled',false); //開啟圖片URL輸入框
							}
						}
						
						$('.submit-reply').removeClass( "submit-reply-disabled" ); //開啟送出按鈕
						$('.submit-reply').prop('disabled',false);
						$('#comment-reply-msg_'+REPLY_COM_ID).show();
						$('#send-reply-msg_'+REPLY_COM_ID).hide();//隱藏傳送中訊息
					}
					/*,
					error: function(data){
					//if(data.success == true){alert("失敗");}
						alert("失敗");
					}*/
				});
			}
		}
    });
	
	//留言按讚跟取消讚function
	$(document).on('click','.comments-likebtn',function(){
		
		var COMLIKEBTN_ID = $(this).attr('id').split('_');
		/*	分割likebtn的ID
			COMLIKEBTN_ID = comments-likebtn_(reply_comments_id)_(member_id)_(loggedin_check)
			COMLIKEBTN_ID[0] = comments-likebtn或comments-unlikebtn
			COMLIKEBTN_ID[1] = reply_comments_id
			COMLIKEBTN_ID[2] = comments_member_id
			COMLIKEBTN_ID[3] = loggedin_check (true或false)
		*/
			
		var COMLIKE_UNLILE = COMLIKEBTN_ID[0];
		var REPLY_COM_ID = COMLIKEBTN_ID[1];
		var COM_MEMBER_ID = COMLIKEBTN_ID[2];
		var LOGGEDIN_CHECK =COMLIKEBTN_ID[3];

		if(COMLIKE_UNLILE==="comments-likebtn"){
			if(LOGGEDIN_CHECK != true){
				$('body').css('overflow','hidden');
				$('.login_popup').css('display','block');
				//document.getElementById("like-reply-message_"+THREAD_ID+"_"+COMMENTS_ID+"_"+COM_ACCOUNT).innerHTML ="要先登入才能送拉馬喔！";
			}
			else{
				
				//document.getElementById("comments-likebtn_"+THREAD_ID+"_"+COMMENTS_ID+"_"+COM_ACCOUNT).innerHTML ="取消讚";
				document.getElementById("comments-likebtn_"+REPLY_COM_ID+"_"+COM_MEMBER_ID+"_"+LOGGEDIN_CHECK).setAttribute("id","comments-unlikebtn_"+REPLY_COM_ID+"_"+COM_MEMBER_ID+"_"+LOGGEDIN_CHECK);
				$("#comments-unlikebtn_"+REPLY_COM_ID+"_"+COM_MEMBER_ID+"_"+LOGGEDIN_CHECK).removeClass("comlike_gray").addClass("comunlike_blue");

				$.ajax({
					type:'POST',
					url:'/llamafun/comments-like.php',
					data:{  reply_com_id : REPLY_COM_ID , comments_member_id:COM_MEMBER_ID},
					success:function(html){
						$('#commentslikecount_'+REPLY_COM_ID).replaceWith(html);
					}
				});
			}
		}
		else{
			//document.getElementById("comments-unlikebtn_"+THREAD_ID+"_"+COMMENTS_ID+"_"+COM_ACCOUNT).innerHTML ="讚";
			document.getElementById("comments-unlikebtn_"+REPLY_COM_ID+"_"+COM_MEMBER_ID+"_"+LOGGEDIN_CHECK).setAttribute("id","comments-likebtn_"+REPLY_COM_ID+"_"+COM_MEMBER_ID+"_"+LOGGEDIN_CHECK);
			$("#comments-likebtn_"+REPLY_COM_ID+"_"+COM_MEMBER_ID+"_"+LOGGEDIN_CHECK).removeClass("comunlike_blue").addClass("comlike_gray");
			
			$.ajax({
				type:'POST',
				url:'/llamafun/comments-unlike.php',
				data:{  reply_com_id : REPLY_COM_ID  , comments_member_id:COM_MEMBER_ID},
				success:function(html){
					$('#commentslikecount_'+REPLY_COM_ID).replaceWith(html);
				}
			});
		}
	});

	//關閉文章通知
	$(document).on('click','.thread-nft-onoff',function(){

		var THREAD_NFTONOFF_ID = $(this).attr('id').split('_');
			/*	分割thread-nft-off的ID
				THREAD_NFTONOFF_ID = thread-nft-off(on)_threadid
				THREAD_NFTONOFF_ID[0] = thread-nft-off或thread-nft-on
				THREAD_NFTONOFF_ID[1] = threadid
			*/
		var NFT_ONOFF = THREAD_NFTONOFF_ID[0];
		var THREAD_ID = THREAD_NFTONOFF_ID[1];

		$.ajax({
			type:'POST',
			url:'/llamafun/thread-nft-onoff.php',
			data:{  thread_id : THREAD_ID , nft_onoff : NFT_ONOFF},
			success:function(response){
				//alert(response);
				if(response==true){
					if(NFT_ONOFF==='thread-nft-off'){
						$('#thread-nft-off_'+THREAD_ID).replaceWith("<a id='thread-nft-on_"+THREAD_ID+"' class='thread-nft-onoff nft-off' href='javascript: void(0)' >通知</a>");
					}
					else if(NFT_ONOFF==='thread-nft-on'){
						//alert('!!');
						//$("#comment-nft-on_"+THREAD_ID).replaceWith('123');
						$('#thread-nft-on_'+THREAD_ID).replaceWith("<a id='thread-nft-off_"+THREAD_ID+"' class='thread-nft-onoff' href='javascript: void(0)' >通知</a>");
					}
				}
			}
		});
		
	});
	
	var EDIT_SUBJECT = null;
	var SUBJECT_URL = null;
	var EDIT_THREAD_ID = null;
	//編輯文章
	$(document).on('click','.thread-edit',function(){
		
		if( !$( ".thread_show" ).length ){
			//$('.textarea_subject_edit').replaceWith("<div id='thread-title_"+EDIT_THREAD_ID+"' class='div-row thread_title'><a href='"+SUBJECT_URL+"' target=_blank>"+EDIT_SUBJECT+"</a></div>");
			//<div id="thread-title_<?php echo $thread_id; ?>" class="div-row thread_title"><?php echo $subject; ?></div>
			$('.thread-edit-submit').remove();
			$('.textarea_subject_edit').replaceWith("<div id='thread-title_"+EDIT_THREAD_ID+"' class='div-row thread_title'><a href='"+SUBJECT_URL+"' target=_blank>"+EDIT_SUBJECT+"</a></div>");
		}
		
		//alert($('.textarea_subject_edit').attr('id'));
		var thread_edit_ID = $(this).attr('id').split('_'); 
		/*	分割thread-del_ID的ID
			thread_edit_ID = thread-edit_threadid
			thread_edit_ID[0] = thread-edit
			thread_edit_ID[1] = threadid
		*/
		EDIT_THREAD_ID = thread_edit_ID[1];
		EDIT_SUBJECT = $("#thread-title_"+EDIT_THREAD_ID).text();
		SUBJECT_URL = $("#thread-title_"+EDIT_THREAD_ID).find('a:first').attr('href');
		//alert(SUBJECT_URL+"~");
		$("#thread-title_"+EDIT_THREAD_ID).replaceWith(
		"<textarea maxlength='60' class='textarea_subject_edit' id="+EDIT_THREAD_ID+" name='textarea_subject'></textarea> <a class='thread-edit-submit' href=javascript: void(0) >送出</a>"
		);
		
		$("textarea.textarea_subject_edit").val(EDIT_SUBJECT);
		
	});
	
	//編輯文章確定按鈕
	$(document).on('click','.thread-edit-submit',function(){
		
		var SUBJECT = $("textarea.textarea_subject_edit").val();
		var format = /(?!^\s+$)^(.|\n)+$/;//不允許全部空白無任何文字或符號，但文字之間可以有空白
		var format_correct = true;
		
		var THREAD_ID = $('.textarea_subject_edit').attr('id');
		
		if(SUBJECT.length < 1){
			format_correct = false;
		 }
		 else{
			 if(!SUBJECT.match(format)){
				 format_correct = false;
			 }
		 }
		
		if(format_correct == true){
			$("textarea.textarea_subject_edit").val('');
			$.ajax({
				type:'POST',
				url:'/llamafun/thread-edit.php',
				data:{  thread_id : THREAD_ID , subject : SUBJECT},
				success:function(response){
					if(response != false){
						$('.thread-edit-submit').remove();
						
						//$('.textarea_subject_edit').replaceWith(response);
						if(!$( ".thread_show" ).length){
							$('.textarea_subject_edit').replaceWith("<div id='thread-title_"+THREAD_ID+"' class='div-row thread_title'><a href='"+SUBJECT_URL+"' target=_blank>"+SUBJECT+"</a></div>");
							$(".thread-info-image #thread-title_"+EDIT_THREAD_ID+" a").text(SUBJECT);
						}
						else{
							$('.textarea_subject_edit').replaceWith("<div id='thread-title_"+THREAD_ID+"' class='div-row thread_title'>"+SUBJECT+"</div>");
							$(".thread-info-image #thread-title_"+EDIT_THREAD_ID).text(SUBJECT);
						}
					}
				}
				/*,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert("失敗");
				}*/
			});
		}
	});
	
	//刪除文章
	$(document).on('click','.thread-delete',function(){
			
		if (confirm("確定要刪除文章嗎？")) {
			
			var thread_del_ID = $(this).attr('id').split('_'); 
			/*	分割thread-del_ID的ID
				thread_del_ID = thread-del_threadid
				thread_del_ID[0] = thread-del
				thread_del_ID[1] = threadid
			*/
			var THREAD_ID = thread_del_ID[1];
			//alert(THREAD_ID);
			
			$.ajax({
				type:'POST',
				url:'/llamafun/thread-delete.php',
				data:{  thread_id : THREAD_ID},
				success:function(response){
					//alert(response);
					if(response == true){
						alert("文章刪除成功！");
						window.location = "/llamafun/index.php";  //來自url.php
					}
					else{
						alert("文章刪除失敗，請再試一次");
					}
				}
				/*,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert("失敗");
				}*/
			});
		}
	});
	
	//刪除留言
	$(document).on('click','.comments-delete',function(){
			
		if (confirm("確定要刪除留言嗎？")) {
			
			var comments_del_ID = $(this).attr('id').split('_'); 
			/*	分割comments-del_ID的ID
				comments-del_ID = thread-del_(reply_comments_id)
				comments-del_ID[0] = thread-del
				comments-del_ID[1] = reply_comments_id
			*/
			var REPLY_COM_ID = comments_del_ID[1];
	
			$.ajax({
				type:'POST',
				url:'/llamafun/comments-delete.php',
				data:{  reply_com_id : REPLY_COM_ID },
				success:function(response){
					//alert(response);
					if(response == true){
						$('#comments-list_'+REPLY_COM_ID).remove();
						//alert("文章刪除成功！");
						//window.location = "<?php echo index_url ?>";  //來自url.php
					}
				}
				/*,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert("失敗");
				}*/
			});
		}
	});
	
	//刪除回覆
	$(document).on('click','.reply-delete',function(){
			
		if (confirm("確定要刪除留言嗎？")) {
			
			var reply_del_ID = $(this).attr('id').split('_'); 
			/*	分割reply-del__ID的ID
				分割reply-del_ID = reply-del_(reply_comments_id)
				分割reply-del_ID[0] = reply-del
				分割reply-del_ID[1] = reply_comments_id
			*/
			var REPLY_COM_ID = reply_del_ID[1];
	
			$.ajax({
				type:'POST',
				url:'/llamafun/comments-delete.php',
				data:{  reply_com_id : REPLY_COM_ID },
				success:function(response){
					//alert(response);
					if(response == true){
						$('#reply-list_'+REPLY_COM_ID).remove();
						//alert("文章刪除成功！");
						//window.location = "<?php echo index_url ?>";  //來自url.php
					}
				}
				/*,
				error: function(data){
				//if(data.success == true){alert("失敗");}
					alert("失敗");
				}*/
			});
		}
	});

	
	
	//確認編輯個人資料所有格式都正確
	var edit_username_correct = true; //一開始設定true 這樣若使用者沒更改信箱跟暱稱可是有上傳大頭貼一樣可以上傳
	var edit_email_correct = true;
	//var edti_email_pwcheck = true;
	var edit_pw_original_correct = false;
	var edit_pw_new_correct = false;
	var edit_pw_newcheck_correct = false;
	var edit_member_pic = false;//確認有無更改大頭貼
	var edit_uploadtype = null;
	var edit_picfile = null;
	var edit_picurl = null;
	//會員資料上傳大頭貼按鈕
	$(document).on('change','.profile-uploadpic',function(){
		edit_member_pic = false;//一開始都先設定為false、若格式接正確才會是true
		
		var member_pic = $(".profile-uploadpic")[0].files[0];
		
		var img_type = member_pic.type;
		var img_filename = member_pic.name;
		//alert(img_type);
		if(typeof(member_pic) != "undefined" && member_pic != null){
			if(img_type==="image/png" || img_type==="image/jpeg" || img_type==="image/jpg" || img_type==="image/gif"){
				var img_size = member_pic.size/1024/1024; //除1024等於KB、再除1024等於MB
				//2048KB等於2MB
				if(img_size<=10){
					edit_member_pic = true;
					edit_uploadtype = "file";
					edit_picfile = member_pic;
					$('.profile-pic-msg').css('visibility','visible');
					$('.profile-pic-msg').text(img_filename);
					$('.profile-upload-file').hide();
					$('.profile-upload-cancel').css('display','inline-block');
					$('.profile-uploadpic-url').prop('disabled',true);
				}
				else{
					alert("Daddy Too Big!!!!  最大10MB喔!!!");
					$(".profile-uploadpic").val('');
				}
			}
			else{
				alert("只能上傳圖片檔喔！JPG、PNG、GIF");
				$(".profile-uploadpic").val('');
			}
		}
	});
	
	$(document).on('click', '.profile-upload-cancel', function() {
		edit_member_pic = false; //取消上傳圖片將確認圖片改為false
		$(".profile-uploadpic").val('');
		$('.profile-upload-cancel').hide();
		$('.profile-upload-file').css('display','inline-block');
		$('.profile-uploadpic-url').prop('disabled',false);
		$('.profile-pic-msg').css('visibility','hidden');
		$('.profile-pic-msg').text('URL');
	});
	
	var globalTimeout_profile_pic = null;
	$(document).on('keyup', '.profile-uploadpic-url', function() {
		edit_member_pic = false;//一開始都先設定為false、若格式接正確才會是true

			if (globalTimeout_comment_img != null) {
				clearTimeout(globalTimeout_profile_pic);
			}
			
			globalTimeout_profile_pic = setTimeout(function() {
				//alert("~");
				globalTimeout_profile_pic = null;  

				var uploadpic_url = $(".profile-uploadpic-url").val();
				//$('.comment-msg_'+THREAD_ID).css('visibility','visible');
				
				if(uploadpic_url.length === 0){ //若長度為0是將關閉的按鈕都開啟
					$('.profile-upload-file').removeClass( "profile-upload-disabled" );
					$(".profile-uploadpic").prop('disabled',false);
					
					//$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).removeClass( "submit-comment-disabled" );
					$('.submit-edit-profile').prop('disabled',false);
					$('.profile-pic-msg').css('visibility','hidden');
					$('.profile-pic-msg').text('URL');
				}
				else{
					//ajax完成前先將所有按鈕關閉
					$('.profile-upload-file').addClass( "profile-upload-disabled" );
					$(".profile-uploadpic").prop('disabled',true);
					
					//$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).addClass( "submit-comment-disabled" );
					$('.submit-edit-profile').prop('disabled',true);
					$('.profile-pic-msg').css('visibility','visible');
					$('.profile-pic-msg').text('載入中...');
					
					$.ajax({
						type:'POST',
						url:'/llamafun/uploadimg-url-check.php',
						data:{image_url : uploadpic_url},
						success:function(response){
							var response = response.trim();
							if(response==="image/png" || response==="image/jpeg" || response==="image/jpg" || response==="image/gif"){
								edit_member_pic = true;
								edit_uploadtype = "url";
								edit_picurl = uploadpic_url;
								$('.profile-pic-msg').css('visibility','hidden');
								$('.profile-pic-msg').text("URL");
								//$('#comment-msg_'+THREAD_ID).text("網址正確");
								//$('#submit-comment_'+THREAD_ID+"_"+LOGGEDIN_CHECK).removeClass( "submit-comment-disabled" );
								$('.submit-edit-profile').prop('disabled',false);
							}
							else if(response === '網址錯誤'){
								$('.profile-pic-msg').css('visibility','visible');
								$('.profile-pic-msg').text('網址錯誤喔！');
							}
							else if(response === '圖片太大'){
								$('.profile-pic-msg').css('visibility','visible');
								$('.profile-pic-msg').text('上限為10MB喔！');
							}
							else if(response === '不支援網址'){
								$('.profile-pic-msg').css('visibility','visible');
								$('.profile-pic-msg').text('只能使用圖片網址喔！');
							}
						}/*,
						error: function(data){
						//if(data.success == true){alert("失敗");}
							alert(data);
						}*/
					});
					//ajax code
				}
			}, 500); 
	});
	
	$(document).on('keyup change', '#profile-edit-username', function() {
		//$('#error_username').css('visibility','visible');
		
		//alert("123");
		var username_format = /^[\u4e00-\u9fa5\w-]+$/;
		var EDIT_USERNAME = $(".profile-edit-username").val();
		edit_username_correct = false;
		//alert(EDIT_USERNAME.length);
		if(EDIT_USERNAME.length < 1){
			$('#profile_error_username').css('visibility','visible');
			document.getElementById("profile_error_username").innerHTML = "暱稱不能為空喔！";
		}
		else {
			if(EDIT_USERNAME.length > 12){
				$('#profile_error_username').css('visibility','visible');
				document.getElementById("profile_error_username").innerHTML = "暱稱只能小於12個字！";
			}
			else{
				if(!EDIT_USERNAME.match(username_format)){
					$('#profile_error_username').css('visibility','visible');
					document.getElementById("profile_error_username").innerHTML = "不能使用注音及空白喔！ 符號只能使用-或_";
				}
				else{
					$.ajax({
						type:'POST',
						url:'/llamafun/profile-db.php',
						data:{edit_username:EDIT_USERNAME , edit_type : "edit_check_username" },
						success:function(response){
							//alert(response);
							if(response==true){
								edit_username_correct = true;
								$('#profile_error_username').css('visibility','hidden');
								document.getElementById("profile_error_username").innerHTML = "MSG";
							}
							else{
								$('.uploadimg_url-error').css('visibility','visible');
								document.getElementById("profile_error_username").innerHTML = "暱稱有人用過了喔！";
							}
						}/*,
						error: function(data){
						//if(data.success == true){alert("失敗");}
							alert(data);
						}*/
					});
				}
			}
		}
	});
	
	
	$(document).on('keyup change', '#profile-edit-email', function() {
		//$('#profile-edit-email').css('visibility','visible');
		//alert("~~~~~~");
		//document.getElementById("profile_error_email").innerHTML = "　";
		edit_email_correct = false;
		var mail_format = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		var EDIT_EMAIL = $(".profile-edit-email").val();
		//var EDIT_EMAIL	= document.getElementById("profile-edit-username").value;
		//alert(EDIT_EMAIL);
		if(EDIT_EMAIL.length < 1){
			if( $(".fb_hide_pw").length ){
				$('#profile_error_email').css('visibility','hidden');
				document.getElementById("profile_error_email").innerHTML = "MSG";
				edit_email_correct = true;
			}
			else{
				$('#profile_error_email').css('visibility','visible');
				document.getElementById("profile_error_email").innerHTML = "信箱不能為空";
			}
		}
		else {
			if(!EDIT_EMAIL.match(mail_format)){
				$('#profile_error_email').css('visibility','visible');
				document.getElementById("profile_error_email").innerHTML = "信箱格式不正確";
			}
			else{
				$.ajax({
					type:'POST',
					url:'/llamafun/profile-db.php',
					data:{ edit_email : EDIT_EMAIL , edit_type : "edit_check_email" },
					success:function(response){
						if(response==true){
							edit_email_correct = true;
							$('#profile_error_email').css('visibility','hidden');
							document.getElementById("profile_error_email").innerHTML = "MSG";
						}
						else{
							$('#profile_error_email').css('visibility','visible');
							document.getElementById("profile_error_email").innerHTML = "信箱有人用過了喔！";
						}	
					}/*,
					error: function(data){
					//if(data.success == true){alert("失敗");}
						alert(data);
					}*/
				});
			}
		}
	});
	
	$(document).on('keyup change', '#profile-edit-pw-original', function() {
		//$('#error_email').css('visibility','visible');
		//alert("~~~~~~");
		//document.getElementById("profile_error_pw_original").innerHTML = "　";
		var PW_ORIGINAL = $(".profile-edit-pw-original").val();
		
		if(PW_ORIGINAL.length < 1){
			edit_pw_original_correct = false;
			$('#profile_error_pw_original').css('visibility','hidden');
			document.getElementById("profile_error_pw_original").innerHTML = "MSG";
		}
		else {
			if(PW_ORIGINAL.length < 8 || PW_ORIGINAL.length > 20){
				$('#profile_error_pw_original').css('visibility','visible');
				document.getElementById("profile_error_pw_original").innerHTML = "舊密碼錯誤喔！";
				edit_pw_original_correct = false;
			}
			else{
				$.ajax({
					type:'POST',
					url:'/llamafun/profile-db.php',
					data:{ pw_original : PW_ORIGINAL , edit_type : "check_pw_original" },
					success:function(response){
						//alert(response);
						if(response==true){
							edit_pw_original_correct = true;
							$('#profile_error_pw_original').css('visibility','hidden');
							document.getElementById("profile_error_email").innerHTML = "　";
						}
						else{
							edit_pw_original_correct = false;
							$('#profile_error_pw_original').css('visibility','visible');
							document.getElementById("profile_error_pw_original").innerHTML = "舊密碼錯誤喔！";
						}	
					}/*,
					error: function(data){
					//if(data.success == true){alert("失敗");}
						alert(data);
					}*/
				});
			}
		}
	});

	///var edit_pw_new_correct = false;
	///var edit_pw_newcheck_correct = false;
	$(document).on('keyup change', '#profile-edit-pw-new', function() {
		//$('#error_password').css('visibility','visible');
		//document.getElementById("profile_error_pw_new").innerHTML = "　";
		$('#profile_error_pw_newcheck').css('visibility','hidden');
		document.getElementById("profile_error_pw_newcheck").innerHTML = "MSG";
		var PW_NEW = $(".profile-edit-pw-new").val();
		var PW_ORIGINAL = $(".profile-edit-pw-original").val();
		edit_pw_new_correct = false;
		$(".profile-edit-pw-newcheck").val('');

		if(PW_ORIGINAL.length>0){
			if(PW_NEW.length < 1){
				$('#profile_error_pw_new').css('visibility','hidden');
				document.getElementById("profile_error_pw_new").innerHTML = "MSG";
				edit_pw_new_correct = false;
			}
			else {
				if(PW_NEW.length < 8 || PW_NEW.length > 20){
					$('#profile_error_pw_new').css('visibility','visible');
					document.getElementById("profile_error_pw_new").innerHTML = "密碼長度為8~20個字喔";
					edit_pw_new_correct = false;
				}
				else{
					$('#profile_error_pw_new').css('visibility','visible');
					document.getElementById("profile_error_pw_new").innerHTML = "　";
					edit_pw_new_correct = true;
				}
			}
		}
		else{
			$('#profile_error_pw_new').css('visibility','visible');
			document.getElementById("profile_error_pw_new").innerHTML = "還沒輸入舊密碼喔！";
		}
		if(PW_NEW.length==0){
			$('#profile_error_pw_new').css('visibility','hidden');
			document.getElementById("profile_error_pw_new").innerHTML = "MSG";
		}
	});
	
	$(document).on('keyup change', '#profile-edit-pw-newcheck', function() {
		//$('#error_password_check').css('visibility','visible');
		//document.getElementById("profile_error_pw_newcheck").innerHTML = "MSG";
		var PW_NEW = $(".profile-edit-pw-new").val();
		var PW_NEW_CHECK = $(".profile-edit-pw-newcheck").val();
		var PW_ORIGINAL = $(".profile-edit-pw-original").val();
		edit_pw_newcheck_correct = false;
		if(PW_ORIGINAL.length>0){
			if(PW_NEW != PW_NEW_CHECK){
				$('#profile_error_pw_newcheck').css('visibility','visible');
				document.getElementById("profile_error_pw_newcheck").innerHTML = "密碼不相符喔！";
				edit_pw_newcheck_correct = false;
			}
			else{
				$('#profile_error_pw_newcheck').css('visibility','hidden');
				document.getElementById("profile_error_pw_newcheck").innerHTML = "MSG";
				edit_pw_newcheck_correct = true;
			}
		}
		else{
			$('#profile_error_pw_newcheck').css('visibility','visible');
			document.getElementById("profile_error_pw_newcheck").innerHTML = "還沒輸入舊密碼喔！";
		}
		if(PW_NEW_CHECK.length==0){
			$('#profile_error_pw_newcheck').css('visibility','hidden');
			document.getElementById("profile_error_pw_newcheck").innerHTML = "MSG";
		}
	});
	

	//點送出修改按鈕執行此function
	$(document).on('click','.submit-edit-profile',function(){
		//alert("~~");
		var PW_ORIGINAL = $(".profile-edit-pw-original").val();
		var PW_NEW = $(".profile-edit-pw-new").val();
		var PW_NEW_CHECK = $(".profile-edit-pw-newcheck").val();
		if(PW_ORIGINAL.length < 1 && PW_NEW.length < 1 && PW_NEW_CHECK.length < 1){
			
			edit_pw_original_correct = true;
			edit_pw_new_correct = true;
			edit_pw_newcheck_correct = true;
		}

		if( $(".fb_hide_pw").length ){
			//edit_username_correct = true;
		}
		
		
		//所有格式正確才進入傳送資料至DB
		if( edit_username_correct == true && edit_email_correct == true && 
			edit_pw_original_correct == true && edit_pw_new_correct == true &&  edit_pw_newcheck_correct == true ){
			//alert("可以修改");
			
			var formdata = new FormData();

			formdata.append('profile_uploadtype', 'none');
			
			if(edit_member_pic===true){
				formdata.append('profile_uploadtype', edit_uploadtype); //url或是file
				if(edit_uploadtype==="file"){
					formdata.append('profile_uploadpic', edit_picfile);
					//alert(edit_picfile.name);
				}
				else if(edit_uploadtype==="url"){
					formdata.append('profile_uploadpic', edit_picurl);
					//alert(edit_picurl);
				}
			}
			
			var EDIT_USERNAME = $(".profile-edit-username").val();
			var EDIT_EMAIL = $(".profile-edit-email").val();
			
			formdata.append('edit_username', EDIT_USERNAME);
			formdata.append('edit_email', EDIT_EMAIL);
			//formdata.append('pw_original', PW_ORIGINAL);
			formdata.append('pw_new', PW_NEW);
			formdata.append('edit_type', "submit_edit_profile");
			
			$(".edit-profile-div *").prop('disabled',true);//按下確認修改將所有元素關閉功能
			$('.submit-profile-msg').text('傳送中...');
			$.ajax({
					type:'POST',
					url:'/llamafun/profile-db.php',
					data:formdata,
					processData: false,  // tell jQuery not to process the data
					contentType: false,
					success:function(response){
						//alert(response);
						if(response==true){
							location.reload();
						}
						else{
							$('.submit-profile-msg').text('');
							alert('修改失敗！請再試一次');
						}
						$(".edit-profile-div *").prop('disabled',false);
						//document.getElementById("div1-1-1").innerHTML =html;
					}
					/*,
					error: function(html){
					//if(data.success == true){alert("失敗");}
						alert("失敗");
					}*/
			});
		}
	});
	
	
	scrollTop: $(window.location.hash).offset().top;
	
	
});