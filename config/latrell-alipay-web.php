<?php
return [

	// 安全检验码，以数字和字母组成的32位字符。
	'key' => 'd3f6ypciyy9dqe0r6f5d92nno2cxtwv3',

	// 签名方式
	'sign_type' => 'MD5',

	// 服务器异步通知页面路径。
	'notify_url' => 'http://ceshi.zuren8.com/lessonComment/alipayAsyncCallback',

	// 页面跳转同步通知页面路径。
	'return_url' => 'http://ceshi.zuren8.com/lessonComment/alipaySyncCallback'
];
