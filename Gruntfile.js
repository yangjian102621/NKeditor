
module.exports = function(grunt) {

var BANNER = '/* <%= pkg.name %> <%= pkg.version %> (<%= grunt.template.today("yyyy-mm-dd") %>), Copyright (C)' +
	' r9it.com,*/\r\n';

var SRC_FILES = [
	'src/header.js',
	'src/core.js',
	'src/config.js',
	'src/event.js',
	'src/html.js',
	'src/selector.js',
	'src/node.js',
	'src/range.js',
	'src/cmd.js',
	'src/widget.js',
	'src/edit.js',
	'src/toolbar.js',
	'src/menu.js',
	'src/colorpicker.js',
	'src/uploadbutton.js',
	'src/dialog.js',
	'src/tabs.js',
	'src/ajax.js',
	'src/main.js',
	'src/footer.js',
];

var PLUGIN_FILES = [
	'plugins/anchor/anchor.js',
	'plugins/autoheight/autoheight.js',
	'plugins/baidumap/baidumap.js',
	'plugins/map/map.js',
	'plugins/clearhtml/clearhtml.js',
	'plugins/code/code.js',
	'plugins/emoticons/emoticons.js',
	'plugins/filemanager/filemanager.js',
	'plugins/flash/flash.js',
	'plugins/image/image.js',
	'plugins/insertfile/insertfile.js',
	'plugins/lineheight/lineheight.js',
	'plugins/link/link.js',
	'plugins/map/map.js',
	'plugins/media/media.js',
	'plugins/multiimage/multiimage.js',
	'plugins/graft/graft.js',
	'plugins/pagebreak/pagebreak.js',
	'plugins/plainpaste/plainpaste.js',
	'plugins/preview/preview.js',
	'plugins/quickformat/quickformat.js',
	'plugins/table/table.js',
	'plugins/template/template.js',
	'plugins/wordpaste/wordpaste.js',
	'plugins/fixtoolbar/fixtoolbar.js'
];

var pkg = grunt.file.readJSON('package.json');

var lang = grunt.option('lang') || 'zh-CN';

grunt.initConfig({
	pkg : pkg,
	concat : {
		options : {
			process : function(src, filepath) {
				src = src.replace(/\$\{VERSION\}/g, pkg.version + ' (' + grunt.template.today('yyyy-mm-dd') + ')');
				src = src.replace(/\$\{THISYEAR\}/g, grunt.template.today('yyyy'));
				src = src.replace(/\/\*\*(\r\n|\n)[\s\S]*?\*\//g, '');
				src = src.replace(/(^|\s)\/\/.*$/mg, '');
				src = src.replace(/(\r\n|\n)\/\*\*\/.*(\r\n|\n)/g, '');
				src = src.replace(/[ \t]+$/mg, '');
				src = src.replace(/(\r\n|\n){2,}/g, '$1');
				return src;
			}
		},
		build : {
			src : SRC_FILES.concat('lang/' + lang + '.js').concat(PLUGIN_FILES),
			dest : '<%= pkg.filename %>-all.js'
		}
	},

	uglify : {
		options : {
			banner : BANNER,
		},
		//压缩js
		build : {

			files: [
				{
					src : '<%= pkg.filename %>-all.js',
					dest : '<%= pkg.filename %>-all-min.js'
				},
				{
					src : 'plugins/multiimage/BUpload.js',
					dest : 'plugins/multiimage/BUpload.min.js'
				},
				{
					src : 'plugins/filemanager/FManager.js',
					dest : 'plugins/filemanager/FManager.min.js'
				}
			]

		}
	},

	//压缩css
	cssmin : {
		options: {
			banner : BANNER,
			beautify: {
				//中文ascii化
				ascii_only: true
			}
		},
		build : {
			files: [
				{
					src: 'themes/black/editor.css',
					dest: 'themes/black/editor.min.css'
				},
				{
					src: 'themes/grey/editor.css',
					dest: 'themes/grey/editor.min.css'
				},
				{
					src: 'themes/blue/editor.css',
					dest: 'themes/blue/editor.min.css'
				},
				{
					src: 'themes/primary/editor.css',
					dest: 'themes/primary/editor.min.css'
				},
				{
					src: 'themes/default/editor.css',
					dest: 'themes/default/editor.min.css'
				},
				{
					src : 'plugins/multiimage/css/upload.css',
					dest : 'plugins/multiimage/css/upload.min.css'
				},
				{
					src : 'plugins/filemanager/css/filemanager.css',
					dest : 'plugins/filemanager/css/filemanager.min.css'
				}
			]
		}
	},

	// 打包压缩文件
	compress : {
		main : {
			options: {
				archive: 'dist/<%= pkg.filename %>-<%= pkg.version %>-' + lang + '.zip',
			},
			files: [
				{src: ['asp/**'], dest: '<%= pkg.name %>/'},
				{src: ['asp.net/**'], dest: '<%= pkg.name %>/'},
				{src: ['attached'], dest: '<%= pkg.name %>/'},
				{src: ['jsp/**'], dest: '<%= pkg.name %>/'},
				{src: ['libs/**'], dest: '<%= pkg.name %>/'},
				{src: ['lang/**'], dest: '<%= pkg.name %>/'},
				{src: ['php/**'], dest: '<%= pkg.name %>/'},
				{src: ['plugins/**'], dest: '<%= pkg.name %>/'},
				{src: ['themes/**'], dest: '<%= pkg.name %>/'},
				{src: ['<%= pkg.filename %>*-all-min.js'], dest: '<%= pkg.name %>/'},
				{src: ['index.html'], dest: '<%= pkg.name %>/'},
				{src: ['old .html'], dest: '<%= pkg.name %>/'},
			]
		}
	}
});

grunt.loadNpmTasks('grunt-contrib-concat');
grunt.loadNpmTasks('grunt-contrib-uglify');
grunt.loadNpmTasks('grunt-contrib-compress');
grunt.loadNpmTasks('grunt-contrib-cssmin');

grunt.registerTask('build', ['concat', 'uglify', 'cssmin']);
grunt.registerTask('zip', ['build', 'compress']);

grunt.registerTask('default', 'build');

};
