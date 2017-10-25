# NKeditor
NKedtior是基于 kindeditor 进行二次开发的项目
kindeditor 是一款优秀的开源在线编辑器。轻量级且功能强大，代码量却不到百度的ueditor编辑器的一半。可惜已经4年没有更新了，由于业务的需求我们在kindeditor的基础上开发了 NKeditor, 主要做了一下工作：
1. 调整编辑器和弹出 dialog 的样式，美化了UI
2. 重写图片上传和批量图片上传插件，使用 html5 上传代替了 flash,实现了待上传图片预览，优化用户体验
3. 修复一些已知的bug，如 ajax 提交无法获取内容等
4. 新增涂鸦等功能

再次感谢 kindeditor 的开发者，为我们提供了如此优秀的在线编辑器，让我们能在前人的基础上继续贡献自己的微薄之力。

# 关于版本号
NKeditor 沿用了 kindeditor 最后发布的版本号 v4.1.11，所以NKeditor 发布的第一个稳定版本是 v4.2.0, 以后的版本都是在 v4.2.0 版本的基础上发布的。

# 在线演示

### http://d.r9it.com/nkeditor/

# 部署和构建
1. npm install -g grunt-cli
2. 切换到 NKeditor 根目录，执行 npm install
3. 编译 : 执行 grunt
4. 如果要打包的话，执行 grunt zip，就会把编辑器的有关的的文件全部打包放入 dist 文件夹中，解压之后你就会得到一个干净的编辑器了。直接访问 index.html 进行预览。

# java版本接入
在本人的另一个 spring-boot 开源项目 https://gitee.com/blackfox/spring-boot-demo 中做了集成，'
这里顺便介绍一下 spring-boot-demo 项目，就是用 spring-boot 结合国产前端框架 AmazeUI 做了一个后台基础开发框架，集成了mybatis Mapper3, 分页插件,
并集成了 shiro 实现了 RBAC 权限管理系统，可谓开箱即用，分分钟搭建好一个高大上的后台管理系统。

clone 下来，导入数据库，更改application.yml的数据库配置，就可以直接运行了，登录进去就可以体验了。
不过java版本目前只实现了七牛云的文件上传和管理，原生的没有做实现。

# 使用说明
1. 批量图片上传的插件依赖 jQuery-1.7 以上的版本，jquery需要自己手动引入，编辑器没有默认引入的，这样避免加载了你不需要的脚本库导致页面加载变慢
2. 文件上传实现了 php 传统方式和七牛云图片上传，默认推荐使用七牛云，使用很简单，而且免费（企业版收费）。demo 上使用的是我的个人空间，多人测试的时候上传速度和并发都有很大的限制，如果大家测试的时候觉得慢，可以改成自己的七牛空间或者使用本地上传。
5. 七牛云的 SDK 依赖 composer 构建，所以如果使用七牛云上传的话请在 php/qiniu 目录下执行 __composer install__
4. 还有就是 demo 中我的七牛存储空间仅供大家测试使用，请不要上传有违法律法规和道德规范的图片和文件资源，你懂的 O(∩_∩)O~。
3. 后端上传和文件管理代码我只是写了简单的 demo, 没有做安全处理之类，请谨慎使用，仅做参考。

NKeditor 更新记录
========
### version 5.0.3
* 修复文图片搜索和图片抓取的bug，修复通用存储模式的图片抓取时候没有更新图片列表数据库的bug。
* 更新了图片搜索程序，把抓取缩略图改成抓取原图

### version 5.0.2
* 修复字体，颜色等下拉菜单图标的位置的bug
* 修复搜索相对路径的bug issue [https://gitee.com/blackfox/kindeditor/issues/IFLFS](https://gitee
.com/blackfox/kindeditor/issues/IFLFS)，感谢 @mean2015 的反馈
* 重构了通用图片上传和图片列表的API，优化图片的存储和列表算法 issue [https://gitee.com/blackfox/kindeditor/issues/IFHXZ](https://gitee
.com/blackfox/kindeditor/issues/IFHXZ), 感谢 @快乐的langYa 的反馈。

在

### version 5.0.1
* 鉴于很多网友反馈新版的皮肤很丑，吓得宝宝赶紧修正了皮肤，更改图标尺寸和间距，使编辑器看起来不那么拥挤, 更改了样式，图标参参考了 "wysiwyg-editor"， 看起来确实好多了，感谢 @
公孙二狗 同学的推荐。
* 删除了一些非主流色调的皮肤，恢复并保留了原版的皮肤，如果还是觉得原版皮肤好看的，可以通过设置 themeType:"default" 来加载原版的皮肤。
* 新增了java 版本的接入 demo https://gitee.com/blackfox/spring-boot-demo.
* 修复了一些已知的bug

### version 5.0.0
大版本更新，使用 sass 重写了全部的 css 代码，方便维护了，重写了皮肤，而且提供了5套皮肤供自由选择，修复了 N 个bug。 
* 修复切换源代码再返回就看不到图标了的bug， 感谢开源中国用户 “吴小华” 同学的反馈
* 修复 [#IFA3P](https://gitee.com/blackfox/kindeditor/issues/IFA3P) 提出的bug，弹出框和语言包的问题，提供新的 options 参数 dialogOffset 用来设置弹出框的位置。默认为 0 ，即居中显示。
* 应广大网友的强烈要求，紧急更新了一套皮肤，默认使用了 svg 矢量图标，对于IE浏览器，使用 png 图标进行了兼容，不过清晰度没有 svg 那么高（这个是必须要的）
* 新增 tableBorderColor 配置选项，设置表格的默认边框，并把表格的默认边框颜色设置为 #cccccc


### version 4.2.2
* 修复在有滚动条的时候，批量文件上传的弹框定位到不可见区域的bug
* 给弹框新增 css3 animation 动画特效
* 优化 loadStyle(), loadScript() 方法，新增缓存，避免同样的css和js资源被多次加载
* 精简了项目，将类似的css的css，js合并复用，减少资源加载
* 修复在伪静态php框架中，获取js相对路径出错而导致资源加载失败的bug

### version 4.2.1
* 修改语言包的加载方式，默认加载中文语言包，不用再手动通过 script 去加载，现在使用 NKeditor 只需要引入一个 NKeditor-all-min.js 就可以了
* 精简资源，比较大的插件js，css 代码全部压缩，加载 min 版的静态资源。
* 新增 [YYGraft](https://gitee.com/blackfox/scrawl)在线涂鸦工具插件，可以愉快的添加涂鸦了。
* 修改图片上传类，支持 base64 图片上传

### version 4.2.0(接原来kindeditor版本)
* 调整了编辑器和弹出 dialog 的样式，美化了UI
* 重写了图片上传和批量图片上传插件，使用 html5 上传代替了 flash,实现了待上传图片预览，优化用户体验
* 添加七牛云上传支持，并封装了上传工具
* 修复了一些已知的bug，如 ajax 提交无法获取内容等

