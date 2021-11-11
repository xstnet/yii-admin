# yii-admin

#### 项目介绍
基于Yii2.0.* 的后台管理系统

#### 软件架构
1. 后台：Yii2.0
2. 后台样式：layui 2.4.3


#### 安装教程

1. git clone https://gitee.com/xstnet/yii-admin.git
2. cd yii-admin
3. composer install --ignore-platform-reqs 安装依赖
4. php init 初始化 选择对应的环境，生成配置文件
5. query build.sql 执行sql文件
6. Configure database connection 配置数据库连接

#### 使用说明

1. build.sql 只包含表。 数据库连接在common/config/main-local中配置
2. demo http://www.xstnet.com:2369  账号密码：guest 123456

#### 参与贡献

1. Fork 本项目
2. 新建 Feat_xxx 分支
3. 提交代码
4. 新建 Pull Request