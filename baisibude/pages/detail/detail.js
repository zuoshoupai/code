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
        options:options,
        url:options.url,
        height: options.height,
        width: options.width,
        loadingHidden:false
     })
  },
   onShareAppMessage: function () {
    var that =this
    var url = that.data.url
    //height = that.data.height
    //width = that.data.width
    console.log(url)
    return {
      title: 'i have a fun',
      desc: '自己找点乐子s',
      path: 'pages/detail/detail?height=0&width=0&url='+url
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