// pages/bill/bill.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    border_color:'#ddd1d1',
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
  /*----------------------------自己编写---------------------------------*/ 
  change_color:function(e){
    this.setData({
      border_color: '#1e99dd'
    });
  },
  formsubmit:function(res){
    console.log(res.detail.value);
    if(res.detail.value){
      res.detail.value.x = 'save';
      wx.request({
        url: 'http://localhost/Bais/form.php',
        data: res.detail.value,
        header: {
          'content-type':'application/x-www-form-urlencoded'
        },
        method: 'POST',
        dataType: 'json',
        responseType: 'text',
        success: function(res) {
          console.log(res.data.state);
          if(res.data.state){
            console.log('提交成功显示页面...');
          }
        },
        fail: function(res) {
          console.log(res);
        },
        complete: function(res) {
          console.log('com');
        },
      })
    }

  }
})