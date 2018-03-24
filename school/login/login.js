const app = getApp();
function login(){
    try {
      var type_id = wx.getStorageSync('type_id');
      if (type_id) {
        wx.request({
          url: 'http://localhost/Bais/test.php?validate=true',
          data: {
            id: type_id
          },
          success(res) {
            console.log(res);
            app.globalData.sign = true;//更改登陆显示状态
          }
        })
        // 使用微信号注册过该程序，还可以添加过期时间外需要重新更新type_id及完整的登陆过程 *过期时间功能暂未添加*
      } else {
        wx.login({
          success: function (res) {
            if (res.code) {
              //发起网络请求
              wx.request({
                url: 'http://localhost/Bais/test.php?sign=true',
                data: {
                  code: res.code
                },
                success(res) {
                  console.log(res);
                  try {
                    wx.setStorageSync('type_id', res.data);
                    app.globalData.sign = true;
                  } catch (e) {
                    console.log('存储出错....');
                  }
                }
              })
            } else {
              console.log('登录失败！' + res.errMsg)
            }
          }
        });
      }
    } catch (e) {
      console.log('获取缓存出错...');
      // Do something when catch error
    }
}//登陆验证接口
module.exports = {
  login: login
}
