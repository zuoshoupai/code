var detail = '../detail/detail'
Page({
  data: {
    text:"Page detail",
    url:'',
    height:0,
    width:0,
    loadingHidden:true
  },
  onLoad: function(options){
    console.log(options)
     this.setData({
        url:options.url,
        height: options.height,
        width: options.width,
        loadingHidden:false
     })
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