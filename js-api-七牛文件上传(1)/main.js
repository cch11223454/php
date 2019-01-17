/*global Qiniu */
/*global plupload */
/*global FileProgress */
/*global hljs */

$(function() {
	var uploader = Qiniu.uploader({

		runtimes: 'html5,flash,html4', // 上传模式，依次退化
		browse_button: 'pickfiles', // 上传选择的点选按钮，必需
		// 在初始化时，uptoken，uptoken_url，uptoken_func三个参数中必须有一个被设置
		// 切如果提供了多个，其优先级为uptoken > uptoken_url > uptoken_func
		// 其中uptoken是直接提供上传凭证，uptoken_url是提供了获取上传凭证的地址，如果需要定制获取uptoken的过程则可以设置uptoken_func
		
		 // uptoken是上传凭证，由其他程序生成
		 uptoken: "um6IEH7mtwnwkGpjImD08JdxlvViuELhI4mFfoeL:zPVaMyRvtEgsfbKKuIPpMobH6nc=:eyJzY29wZSI6ImphdmFkZW1vIiwiZGVhZGxpbmUiOjIzNjY1NjI3MjZ9Cg==",
		 
		 //uptoken_url: '/uptoken',         // Ajax请求uptoken的Url，强烈建议设置（服务端提供）
		 

		
		
		// uptoken_func: function(){    // 在需要获取uptoken时，该方法会被调用
		//    // do something
		//    return uptoken;
		// },
		get_new_uptoken: false, // 设置上传文件的时候是否每次都重新获取新的uptoken
		// downtoken_url: '/downtoken',
		// Ajax请求downToken的Url，私有空间时使用，JS-SDK将向该地址POST文件的key和domain，服务端返回的JSON必须包含url字段，url值为该文件的下载地址
		// unique_names: true,              // 默认false，key为文件名。若开启该选项，JS-SDK会为每个文件自动生成key（文件名）
		// save_key: true,                  // 默认false。若在服务端生成uptoken的上传策略中指定了sava_key，则开启，SDK在前端将不对key进行任何处理
	
		domain: $('#domain').val(), // bucket域名，下载资源时用到，必需
		
		container: 'container', // 上传区域DOM ID，默认是browser_button的父元素
		max_file_size: '100mb', // 最大文件体积限制
		flash_swf_url: 'bower_components/plupload/js/Moxie.swf', //引入flash，相对路径
		max_retries: 3, // 上传失败最大重试次数
		dragdrop: false, // 开启可拖曳上传
		drop_element: 'container', // 拖曳上传区域元素的ID，拖曳文件或文件夹后可触发上传
		chunk_size: '4mb', // 分块上传时，每块的体积
		auto_start: true, // 选择文件后自动上传，若关闭需要自己绑定事件触发上传
		//x_vars : {
		//    查看自定义变量
		//    'time' : function(up,file) {
		//        var time = (new Date()).getTime();
		// do something with 'time'
		//        return time;
		//    },
		//    'size' : function(up,file) {
		//        var size = file.size;
		// do something with 'size'
		//        return size;
		//    }
		//},		

		

		multi_selection: !(mOxie.Env.OS.toLowerCase() === "ios"),

		filters: {
			max_file_size: '200kb',
			prevent_duplicates: true,
			//Specify what files to browse for
			mime_types: [
				// {title : "mp4 files", extensions : "mkv"}
				// {title : "flv files", extensions : "flv,jpg"}
				// {title : "Video files", extensions : "flv,mpg,mpeg,avi,wmv,mov,asf,rm,rmvb,mkv,m4v,mp4"}
				// {title : "Video files", extensions : "avi,mp4,wmv,mpg,mov,flv,mkv,mpeg"}
				// {title : "Image files", extensions : "jpg,gif,png"}
				// {title : "Zip files", extensions : "zip"}
			]
		},
		
		log_level: 5,
		init: {
			'FilesAdded': function(up, files) {
				$('table').show();
				//$('#success').hide();
				plupload.each(files, function(file) {
					var progress = new FileProgress(file, 'fsUploadProgress');
					progress.setStatus("等待...");
					progress.bindUploadCancel(up);
				});
			},
			'BeforeUpload': function(up, file) {
				var progress = new FileProgress(file, 'fsUploadProgress');
				var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
				if(up.runtime === 'html5' && chunk_size) {
					progress.setChunkProgess(chunk_size);
				}
			},
			'UploadProgress': function(up, file) {
				var progress = new FileProgress(file, 'fsUploadProgress');
				var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
				progress.setProgress(file.percent + "%", file.speed, chunk_size);
			},
			'UploadComplete': function() {
				$('#success').show();
			},
			'FileUploaded': function(up, file, info) {

				var progress = new FileProgress(file, 'fsUploadProgress');
				progress.setComplete(up, info);
			},
			'Error': function(up, err, errTip) {
				$('table').show();
				var progress = new FileProgress(err.file, 'fsUploadProgress');
				progress.setError();
				progress.setStatus(errTip);
			},
			'Key': function(up, file) {
				var key = "";
				var da = new Date();
				return da.getTime();
			}
		}
	});

	uploader.bind('FileUploaded', function() {
		console.log('hello man,a file is uploaded');
	});
	$('#container').on(
		'dragenter',
		function(e) {
			e.preventDefault();
			$('#container').addClass('draging');
			e.stopPropagation();
		}
	).on('drop', function(e) {
		e.preventDefault();
		$('#container').removeClass('draging');
		e.stopPropagation();
	}).on('dragleave', function(e) {
		e.preventDefault();
		$('#container').removeClass('draging');
		e.stopPropagation();
	}).on('dragover', function(e) {
		e.preventDefault();
		$('#container').addClass('draging');
		e.stopPropagation();
	});

	$('#show_code').on('click', function() {
		$('#myModal-code').modal();
		$('pre code').each(function(i, e) {
			hljs.highlightBlock(e);
		});
	});

	$('body').on('click', 'table button.btn', function() {
		$(this).parents('tr').next().toggle();
	});

	var getRotate = function(url) {
		if(!url) {
			return 0;
		}
		var arr = url.split('/');
		for(var i = 0, len = arr.length; i < len; i++) {
			if(arr[i] === 'rotate') {
				return parseInt(arr[i + 1], 10);
			}
		}
		return 0;
	};

	$('#myModal-img .modal-body-footer').find('a').on('click', function() {
		var img = $('#myModal-img').find('.modal-body img');
		var key = img.data('key');
		var oldUrl = img.attr('src');
		var originHeight = parseInt(img.data('h'), 10);
		var fopArr = [];
		var rotate = getRotate(oldUrl);
		if(!$(this).hasClass('no-disable-click')) {
			$(this).addClass('disabled').siblings().removeClass('disabled');
			if($(this).data('imagemogr') !== 'no-rotate') {
				fopArr.push({
					'fop': 'imageMogr2',
					'auto-orient': true,
					'strip': true,
					'rotate': rotate,
					'format': 'png'
				});
			}
		} else {
			$(this).siblings().removeClass('disabled');
			var imageMogr = $(this).data('imagemogr');
			if(imageMogr === 'left') {
				rotate = rotate - 90 < 0 ? rotate + 270 : rotate - 90;
			} else if(imageMogr === 'right') {
				rotate = rotate + 90 > 360 ? rotate - 270 : rotate + 90;
			}
			fopArr.push({
				'fop': 'imageMogr2',
				'auto-orient': true,
				'strip': true,
				'rotate': rotate,
				'format': 'png'
			});
		}

		$('#myModal-img .modal-body-footer').find('a.disabled').each(function() {

			var watermark = $(this).data('watermark');
			var imageView = $(this).data('imageview');
			var imageMogr = $(this).data('imagemogr');

			if(watermark) {
				fopArr.push({
					fop: 'watermark',
					mode: 1,
					image: 'http://www.b1.qiniudn.com/images/logo-2.png',
					dissolve: 100,
					gravity: watermark,
					dx: 100,
					dy: 100
				});
			}

			if(imageView) {
				var height;
				switch(imageView) {
					case 'large':
						height = originHeight;
						break;
					case 'middle':
						height = originHeight * 0.5;
						break;
					case 'small':
						height = originHeight * 0.1;
						break;
					default:
						height = originHeight;
						break;
				}
				fopArr.push({
					fop: 'imageView2',
					mode: 3,
					h: parseInt(height, 10),
					q: 100,
					format: 'png'
				});
			}

			if(imageMogr === 'no-rotate') {
				fopArr.push({
					'fop': 'imageMogr2',
					'auto-orient': true,
					'strip': true,
					'rotate': 0,
					'format': 'png'
				});
			}
		});

		var newUrl = Qiniu.pipeline(fopArr, key);

		var newImg = new Image();
		img.attr('src', 'images/loading.gif');
		newImg.onload = function() {
			img.attr('src', newUrl);
			img.parent('a').attr('href', newUrl);
		};
		newImg.src = newUrl;
		return false;
	});

});