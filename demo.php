<?php
	$htmlData = '';
	if (!empty($_POST['content1'])) {
		if (get_magic_quotes_gpc()) {
			$htmlData = stripslashes($_POST['content1']);
		} else {
			$htmlData = $_POST['content1'];
		}
	}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>KindEditorX PHP</title>
	<link rel="stylesheet" href="themes/default/default.css" />
	<script charset="utf-8" src="kindeditor-all.js"></script>
	<script charset="utf-8" src="lang/zh-CN.js"></script>
	<script charset="utf-8" src="libs/jquery.min.js"></script>
	<script>
		KindEditor.ready(function(K) {
			var editor1 = K.create('textarea[name="content1"]', {
				uploadJson : 'php/upload_json.php',
				fileManagerJson : 'php/file_manager_json.php',
				allowFileManager : true,
				allowImageUpload : true,
				//allowMediaUpload : true,
				afterCreate : function() {
					var self = this;
					K.ctrl(document, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
					K.ctrl(self.edit.doc, 13, function() {
						self.sync();
						K('form[name=example]')[0].submit();
					});
				}
			});

		});
	</script>
</head>
<body>
	<?php echo $htmlData; ?>
	<form name="example" method="post" action="demo.php">
		<textarea name="content1" id="content" style="width:700px;height:200px;visibility:hidden;"><?php echo htmlspecialchars($htmlData); ?></textarea>
		<br />
		<input type="submit" name="button" value="提交内容" /> (提交快捷键: Ctrl + Enter)
	</form>
</body>
</html>

