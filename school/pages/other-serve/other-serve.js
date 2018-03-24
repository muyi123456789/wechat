// pages/other-serve/other-serve.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    items: [{ name: '相关说明', con:''  },
            { name: '学校信息查询', con: '' },
            { name: '生活工具', con: '' },
            { name: '附近活动', con: '' },
            { name: '帮忙干活', con: '' },
            { name: '学习交流社区', con: '' },
            ],
    info: [{ title: '公司在其他校区的服务情况:', value:'框架提供丰富的微信原生API，可以方便的调起微信提供的能力，如获取用户信息，本地存储，支付功能等。' },
      { title: '公司理念服务宗旨:', value: '框架提供丰富的微信原生API，可以方便的调起微信提供的能力，如获取用户信息，本地存储，支付功能等。' }
          ]
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  },
  // -------------------------自己编写------------------------------------
  scroll:function(e){
    console.log(e);
  }
})