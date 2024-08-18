# IP地理位置查询服务

## 项目简介
本项目是一个基于PHP的IP地理位置查询服务，利用IP2Location数据库和Redis缓存实现高效查询。用户可以通过发送包含IP地址列表的POST请求，获取这些IP地址对应的地理位置信息，包括国家代码、国家名称、地区名称、城市名称、纬度、经度、时区和邮政编码等。 

目前简单压测 4h4G 支持500并发，6分钟0报错

## 环境要求
- PHP 7.4
- Redis 7
- IP2Location数据库 [下载链接（需要注册）](https://lite.ip2location.com/ip2location-lite)

## 安装与配置
1. 克隆项目到本地：
   ```bash
   git clone https://github.com/xjy1592922966/bilibli-ipQuery-php-redis.git
   ```

2. 安装依赖：
   ```bash
   composer install
   ```

3. 给Redis设置密码

4. 使用importredis.sh文件导入IP2Location数据库到Redis中 (需要修改IP2Location 数据库路径 和redis密码)

5. 在php项目中修改redis密码



## 使用方法

1. 通过`curl`或其他HTTP客户端发送POST请求：
   ```bash
   curl -X POST -H "Content-Type: application/json" -d '{"ips": ["8.8.8.8", "123.125.114.144"]}' https://ips.test.b2.ink:8001/
   ```

   预期响应格式（JSON）：
   ```json
   {
       "8.8.8.8": {
           "IS_China": false,
           "Country_Code": "US",
           "Country_Name": "United States",
           "Region_Name": "California",
           "City_Name": "Mountain View",
           "Latitude": "37.386",
           "Longitude": "-122.0838",
           "Time_Zone": "America/Los_Angeles",
           "ZIP_Code": "94043"
       },
       "123.125.114.144": {
           "IS_China": true,
           "Country_Code": "CN",
           "Country_Name": "China",
           "Region_Name": "Beijing",
           "City_Name": "Beijing",
           "Latitude": "39.9042",
           "Longitude": "116.4074",
           "Time_Zone": "Asia/Shanghai",
           "ZIP_Code": "100000"
       }
   }
   ```

## 注意事项
- 本服务仅支持通过POST方法接收JSON格式的IP地址列表。
- 确保Redis服务器和php正常运行，以保证服务的可用性。
- 出于性能考虑，建议使用持久化的Redis连接
- php的redis扩展

## 贡献
欢迎通过提交Pull Request或Issue来参与本项目。如果您在使用过程中遇到任何问题或有任何建议，请随时联系我们。

## 许可证
本项目采用MIT许可证，详情请查阅LICENSE文件