<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016091300500888",

		//商户私钥
		'merchant_private_key' => "MIIEpQIBAAKCAQEAnDZ936RVCYoumNN/NP4P8by2f/4ZmoO15wpwST+cdGMzTsQjGMl8t4jz64uex0TXrbyQFYIGmR5dF/oJRftgJaurPu8OApXMPa5s1ciFqv4UcWtoi2SVsovBrqvcx8p0uL8bvcABgxJjEmQPyVoi+6cGOakXKXKYGNiOg6NRhUT4wssXJkGwlzO6TNAuei3vdJgE6bbznrJHTh21cvteMi2qjNkwjtDIDipQoBHBTZkhTOOGKUiCQDhSRyRTkeLPDTjqqw7YqG0o8ZfwriX0l5kzW2wQCzb1+i3g1uNoWgGzwt9qPIWcsoxVnv3Uqr6vkgPFyeRAFN2Hj2Og1fMJ2QIDAQABAoIBAHkg/+XkxVPKZGt4xFDRGIf4LwN8g1n++5QV0g/aVXUiXuyaxiQ4SI8AkTN6ZjpaNWuwpYEn+/nLQ6CN75bmXx2WygjY0WJLyb2ifR4giRWYQz3XbY95BWMXX64T8fSyFmQTPG2cbw+1uEDQejVFwY6lavxXE3C+PRY3BfspLtNI/AGB8UEkAsni3ksEfxRAg2vRLUUr218zv+fGmtkqtqPdsKZQRFQWkbgmvTONQy1OInamWS9qt7k8oizZ4hc4yWfYbRC8FgSsiIHTiPtoy5FTP549xQDsSam60KOoXTGzSfBrIg19L5/HA403d1ZbVx8LeM07o7b3O6wKXGSR6gECgYEAyOEDVhgQt+HnLZp9SuUgqNZQbWIuwaN3ZBV6sOJdoRxD8fOWZqPX3+QS9pSamCUQlSJ+YMkrSXceGhhNj+noXtmRXEwMYgDYwdpQhBNsytMa0Gcl/ABCGSRuiIEWxJKKtVmPC5g0WKsxGR3fkzcubfLD9LkPGwoK48yhK1nTG2ECgYEAxxPaqVAYwEw/OCBM5M7BIR3oracaF06bQtZFNGxRSgjIinwO9XYBT4B2naCoAGQujqkJMNUET2y4rLR0ngeoYghsPTCMFiaay2MvZBDi0a4DCqNIVDAbcpUFqvq5kCwsfV69XEqNL+8aLJ1akBbJtSRcBJEESq4Yo4XLad+iuXkCgYEAicF9NzpTpLZ/gc+TIpxUtFQcXZbaN6KxSmpMdCcYTFcE5VTFjGfJr9lJg93O0o2AfLVl81uhmsTcWMrXzdx7pSgQvjnMuByaiP38/0YO8lyo48gIzXMj4PgR8PyNr4pLIyhip3HeW/wyNGY/H0bVSUMVTKroCAPwCd4XnJIB6qECgYEAgRQJejB56+1ELGMdGFpKdOuMm1O2ohgQqXR+6K+2wVcSmGA53sPFs8OqpSeu5poOaeeGEwh7Q/hNgYV7+58heXbWn5IjspUTVv6Xkr00JBo81J/sXNHYaiHfy+3HdYh+zEcqZnXcN5FDmlvohXaREIdjtn5Elxts1FIDA0HCo5kCgYEAqaGY/Il9Kc1/cuLq28ZYYXRuAn8HgyAtyy5X2CUfY4sWrcrSokAeHteOmQIxZwS22A5omZhVvYpsmmuy3llgG3+Du1VfBHEUVDG1j1dCnhCFhHdS26EOhZ7AdD6pA5BjXBLPYZzBaRILngmh4ZLMSjwU1RRQBstF9L9Z1wu7MG0=",  //密钥 工具生成的私钥文件
		
		//异步通知地址
		'notify_url' => "http://test.lingfengveeker.com/notify_url.php",
		
		//同步跳转
		'return_url' => "http://test.lingfengveeker.com/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",
		

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//沙箱 https://openapi.alipaydev.com/gateway.do
		//正式上线 https://openapi.alipay.com/gateway.do

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3QsQZoWtNxeAF6OEEb9fl+KKN91TM6JqNVKMAeeTdL3GVwgp1sFxAFt+Wzv/C5C9pgrsLbM/ygSFHH/S4pRo0pd/TG+TpkOInlFYKR4gpUMvCTt+ToZU/0Tp3YoxNwpgN+CCCxlngm33+AgTQbFpIOX32dLVEn0pNcrFT9M1h7q34pdRmvIDdgnzBDm7v0pc8YNyJsMlPuEwHwpAu+Hmw+cwi9/QOt2u7CYsVqFbrx48qq33lI/vcI8LdaYUsu+xbj0hrJ5L+9+ztrghd50BqipqsVmebPA4qTjJYuiUAMUEVph58Ve9mFHpGwxaAZJk+hhzCM7gQumzm/ChW4jwhwIDAQAB",
		
		"show_url"=>'http://test.lingfengveeker.com',
		
	
);