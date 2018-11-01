
   
###第一种(使用box-shadow) 
```html  
//css部分
<style>
.loader{font-size: 5px;width: 10px;height: 10px;border-radius: 50%;position: relative;text-indent: -9999em;margin: 0 auto;animation: load-effect 1s infinite linear;box-shadow: -3em 2em 0 0.3em #FFF, 0 2em 0 0 #FFF, 3em 2em 0 -0.3em #FFF;}
.load-text{position: relative;top:30px;color: #fff;font-size: 16px;}
@keyframes load-effect {
   0% { box-shadow: -3em 2em 0 0.3em #FFF, 0 2em 0 0 #FFF, 3em 2em 0 -0.3em #FFF;}
   25% {box-shadow: -3em 2em 0 0 #FFF, 0 2em 0 -.3em #FFF, 3em 2em 0 0 #FFF;}
   50% {box-shadow: -3em 2em 0 -0.3em #FFF, 0 2em 0 0 #FFF, 3em 2em 0 .3em #FFF;}
   75% {box-shadow: -3em 2em 0 0 #FFF, 0 2em 0 .3em #FFF, 3em 2em 0 0 #FFF;}
  100% {box-shadow:-3em 2em 0 0.3em #FFF, 0 2em 0 0 #FFF, 3em 2em 0 -0.3em #FFF;}
}
</style>
//html部分
<div class="z-dialog" id="z-dialog" style="position: fixed;z-index: 998;top: 0;right: 0;bottom: 0;left: 0;-webkit-transition-duration: 400ms;transition-duration: 400ms;opacity: 0;opacity: 1;background: rgba(0,0,0,0);display:none;">
    <div class="dialog" style="display: block;-webkit-transition-duration: 400ms;transition-duration: 400ms;-webkit-transform: translate3d(-50%,-50%,0) scale(1);
transform: translate3d(-50%,-50%,0) scale(1);opacity: 1;    position: fixed;z-index: 10000;top: 45%;left: 50%;overflow: hidden;width:120px;height:80px;text-align:center;background-color: rgba(0,0,0,0.4);">
        <div class="loader">Loading...</div>
        <div class="load-text">处理中</div> 
    </div>
</div>
```
![效果图](http://img2.ph.126.net/DFSP8jq73xbeWcgQvcSahQ==/6597599731263633251.png)
### 第二种(weui提取)
```html
//css部分
.weui-mask_transparent {position: fixed;z-index: 1000;top: 0;right: 0;left: 0;bottom: 0;}
.weui-toast {position: fixed;z-index: 5000;width: 121px;min-height:121px;top: 180px;left: 50%;margin-left: -3.8em;background: rgba(17, 17, 17, 0.7);text-align: center;border-radius: 5px;color: #FFFFFF;font-family: -apple-system-font,Helvetica Neue,Helvetica,sans-serif;}
.weui-icon_toast.weui-loading {margin: 30px 0 0;width: 38px;height: 38px;vertical-align:baseline;} 
.weui-loading {width: 20px;height: 20px;display: inline-block;vertical-align: middle;-webkit-animation: weuiLoading 1s steps(12, end) infinite;animation: weuiLoading 1s steps(12, end) infinite;background: transparent url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiB2aWV3Qm94PSIwIDAgMTAwIDEwMCI+PHBhdGggZmlsbD0ibm9uZSIgZD0iTTAgMGgxMDB2MTAwSDB6Ii8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjRTlFOUU5IiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDAgLTMwKSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iIzk4OTY5NyIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgzMCAxMDUuOTggNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjOUI5OTlBIiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKDYwIDc1Ljk4IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0EzQTFBMiIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSg5MCA2NSA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNBQkE5QUEiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoMTIwIDU4LjY2IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0IyQjJCMiIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgxNTAgNTQuMDIgNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjQkFCOEI5IiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKDE4MCA1MCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNDMkMwQzEiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTE1MCA0NS45OCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNDQkNCQ0IiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTEyMCA0MS4zNCA2NSkiLz48cmVjdCB3aWR0aD0iNyIgaGVpZ2h0PSIyMCIgeD0iNDYuNSIgeT0iNDAiIGZpbGw9IiNEMkQyRDIiIHJ4PSI1IiByeT0iNSIgdHJhbnNmb3JtPSJyb3RhdGUoLTkwIDM1IDY1KSIvPjxyZWN0IHdpZHRoPSI3IiBoZWlnaHQ9IjIwIiB4PSI0Ni41IiB5PSI0MCIgZmlsbD0iI0RBREFEQSIgcng9IjUiIHJ5PSI1IiB0cmFuc2Zvcm09InJvdGF0ZSgtNjAgMjQuMDIgNjUpIi8+PHJlY3Qgd2lkdGg9IjciIGhlaWdodD0iMjAiIHg9IjQ2LjUiIHk9IjQwIiBmaWxsPSIjRTJFMkUyIiByeD0iNSIgcnk9IjUiIHRyYW5zZm9ybT0icm90YXRlKC0zMCAtNS45OCA2NSkiLz48L3N2Zz4=) no-repeat;background-size: 100%;}
@-webkit-keyframes weuiLoading {
    0% {transform: rotate3d(0, 0, 1, 0deg);}
    100% {transform: rotate3d(0, 0, 1, 360deg);}
}
@keyframes weuiLoading {
    0% { transform: rotate3d(0, 0, 1, 0deg);}
    100% {transform: rotate3d(0, 0, 1, 360deg);}
}
.weui-toast__content {margin: 0 0 15px;color:#fff;}
//html部分
<div id="loadingToast" style="/* opacity: 0; *//* display: none; */">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-loading weui-icon_toast"></i>
        <p class="weui-toast__content">支付中</p>
    </div>
</div> 
```
![效果图](http://img2.ph.126.net/qGR770SK0WaOJyY-WvUY1A==/1669991036924977415.png)

### 第三种(使用gif背景动图)
```html
//css部分
.loading{
	width: 121px;
    height: 121px;
    line-height: 56px;
    color: #fff;
    padding-top: 54px;
    text-align: center;
    font-size: 15px;
    background: url(/res/manpower/loading.gif) no-repeat 42px 27%;
    opacity: 0.89;
    -moz-border-radius: 20px;
    -webkit-border-radius: 20px;
    border-radius: 20px;
    filter: progid:DXImageTransform.Microsoft.Alpha(opacity=70);
}
//html部分
<div class="z-dialog" id="z-dialog" style="position: fixed;z-index: 998;top: 0;right: 0;bottom: 0;left: 0;-webkit-transition-duration: 400ms;transition-duration: 400ms;opacity: 0;opacity: 1;background: rgba(0,0,0,0);display:block;">
	<div class="dialog" style="display: block;-webkit-transition-duration: 400ms;transition-duration: 400ms;-webkit-transform: translate3d(-50%,-50%,0) scale(1);
transform: translate3d(-50%,-50%,0) scale(1);opacity: 1;    position: fixed;z-index: 10000;top: 45%;left: 50%;overflow: hidden;width:121px;height:121px;text-align:center;background-color: rgba(0,0,0,0.4);"> 
		<div id="loading" class="loading">加载中...</div> 
	</div>
</div>
```
![效果图](http://img1.ph.126.net/2tZf9JdUBwA4o0Opkhd9dA==/3270739229478364783.png)
