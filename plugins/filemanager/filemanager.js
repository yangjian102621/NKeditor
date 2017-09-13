/*******************************************************************************
* KindEditor - WYSIWYG HTML Editor for Internet
* Copyright (C) 2006-2011 kindsoft.net
*
* @author Roddy <luolonghao@gmail.com>
* @site http://www.kindsoft.net/
* @licence http://www.kindsoft.net/license.php
*******************************************************************************/

KindEditor.plugin('filemanager', function(K) {
	var self = this;
	var fileManagerJson = K.undef(self.fileManagerJson, self.basePath + 'php/file_manager_json.php');
	var lang = self.lang('filemanager.');
	if(typeof jQuery == 'undefined') {
		K.options.errorMsgHandler(lang.depJQueryError, "error");
		return;
	} else {
		K.loadScript(K.options.pluginsPath+"filemanager/FManager.js");
		K.loadStyle(K.options.pluginsPath+"filemanager/css/filemanager.css");
	}

	self.plugin.filemanagerDialog = function(options) {

		var clickFn = options.clickFn;
		new FManager({
			list_url : fileManagerJson,	//图片列表数据获取url
			lang : lang, //语言包
			callback : function(data) {
				//console.log(data);
				clickFn.call(this, data[0]);
			}
		});
		//return dialog;
	}

});
