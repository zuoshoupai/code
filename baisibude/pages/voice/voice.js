 Page({
  data: {
    list: [],
    maxtime: '',
    loadingHidden: true,
    showapi_appid:33294,
    showapi_sign:'efdc342a7d8848e09c9fc6ad945a0c10',
    page :1
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    //加载最新
    this.requestData(this.data.page);
  },
  /**
   * 上拉刷新
   */
  onPullDownRefresh: function () {
    this.loadingHidden = true;
    //加载最新
    // this.requestData('newlist');
    console.log('上拉刷新')
    
    this.requestData('newlist');
  },

   /**
   * 加载更多
   */
  onReachBottom: function () {
    console.log('到底部')
    //加载更多
    this.hasMore = true;
    this.data.page = this.data.page+1;
    this.requestData(this.data.page);
  },

  /**
   * 请求数据
   */
  requestData: function (a) {
    var that = this;
    var showapi_appid= that.data.showapi_appid;
    var showapi_sign = that.data.showapi_sign;
    console.log(that.data.maxtime)
    console.log('第'+a+'页') 
    wx.request({ 
     url:'https://route.showapi.com/255-1',
      data: {
        showapi_appid: showapi_appid,
        showapi_sign: showapi_sign,
        page: a,
        type: 31, 
      },
      method: 'GET',
      success: function (res) {
        console.log(res) 
        that.setData({
          // 拼接数组
          list: that.data.list.concat(res.data.showapi_res_body.pagebean.contentlist),
          loadingHidden: false,
          hasMore:false, 
        })

      }
    })
  }, 
  playVoice: function (e) {
    console.log(e);
    wx.playBackgroundAudio({
      dataUrl: e.currentTarget.dataset.voiceuri
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