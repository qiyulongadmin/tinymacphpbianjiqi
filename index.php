<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script src='tinymce/tinymce.min.js' referrerpolicy="origin"></script>
	<script>
	tinymce.init({
		selector: '#mytextarea',
		toolbar: [
		  'undo redo | fontselect | fontsizeselect | bold italic underline | forecolor backcolor | superscript subscript charmap insertdatetime emoticons| lists image axupimgs link | bullist  numlist | preview code removeformat | alignleft aligncenter alignright alignjustify | outdent indent',
		  "table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol | fullscreen",
		],
		//禁用菜单栏
		menubar: false,
		language_url : './tinymce/langs/zh_CN.js',//语言包地址
		language:'zh_CN',//引用中文
		plugins: "lists, advlist,image,link,searchreplace,table,fullscreen,emoticons,charmap,insertdatetime,preview,code,removeformat,axupimgs",//图片上传必备
		width: 1000,
		height: 500,
	    //图片上传xml,类似于ajax
		images_upload_handler: function (blobInfo, succFun, failFun) {
			var xhr, formData;
			var file = blobInfo.blob();//转化为易于理解的file对象
			xhr = new XMLHttpRequest();
			xhr.withCredentials = false;
			xhr.open('POST', '/demo/upimg.php');
			xhr.onload = function() {
			  var json;
			  if (xhr.status != 200) {
				  failFun('HTTP Error: ' + xhr.status);
				  return;
			  }
			  json = JSON.parse(xhr.responseText);
			  if (!json || typeof json.location != 'string') {
				  failFun('Invalid JSON: ' + xhr.responseText);
				  return;
			  }
			  succFun(json.location);
			};
			formData = new FormData();
			formData.append('file', file, file.name );//此处与源文档不一样
			xhr.send(formData);
			},
			//文件上传
			   file_picker_callback: function (callback, value, meta) {
			          //文件分类
			          var filetype='.pdf, .txt, .zip, .rar, .7z, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .mp3, .mp4';
			          //后端接收上传文件的地址
			          var upurl='/demo/upfile.php';
			          //为不同插件指定文件类型及后端地址
			          switch(meta.filetype){
			              case 'image':
			                  filetype='.jpg, .jpeg, .png, .gif';
			                  upurl='upimg.php';
			                  break;
			              case 'media':
			                  filetype='.mp3, .mp4';
			                  upurl='upfile.php';
			                  break;
			              case 'file':
			              default:
			          }
			          //模拟出一个input用于添加本地文件
			          var input = document.createElement('input');
			              input.setAttribute('type', 'file');
			              input.setAttribute('accept', filetype);
			          input.click();
			          input.onchange = function() {
			              var file = this.files[0];
			  
			              var xhr, formData;
			              console.log(file.name);
			              xhr = new XMLHttpRequest();
			              xhr.withCredentials = false;
			              xhr.open('POST', upurl);
			              xhr.onload = function() {
			                  var json;
			                  if (xhr.status != 200) {
			                      failure('HTTP Error: ' + xhr.status);
			                      return;
			                  }
			                  json = JSON.parse(xhr.responseText);
			                  if (!json || typeof json.location != 'string') {
			                      failure('Invalid JSON: ' + xhr.responseText);
			                      return;
			                  }
			                  callback(json.location);
			              };
			              formData = new FormData();
			              formData.append('file', file, file.name );
			              xhr.send(formData);

			        };
			    },
				
	  });
	
	function showContent(){
		  alert(tinymce.activeEditor.getContent());
	}
	
	</script>
	<title></title>
</head>
<body>
	<h1>齐玉龙牛逼</h1>
	  <form method="post">
	    <textarea id="mytextarea" name="mytextarea">Hello, World!</textarea>
	  </form>
	  <button type="button" onclick="showContent()">获取编辑器内容</button>
	  
</body>
</html>