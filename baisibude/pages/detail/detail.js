var detail = '../detail/detail'
Page({
  data: {
    text:"Page detail",
    url:'',
    height:0,
    width:0,
    options:'',
    loadingHidden:true
  },
  onLoad: function(options){
    console.log(options)
     this.setData({
        options:options,
        url:options.url,
        height: options.height,
        width: options.width,
        loadingHidden:false
     })
  },
   onShareAppMessage: function () {
    return {
      title: 'i have a fun',
      desc: '自己找点乐子',
      path: 'pages/detail/detail?height=0&width=0&url='+this.options
    }
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  }
})