# Typecho 弹窗公告插件

一个功能丰富的 Typecho 弹窗公告插件，支持多种显示方式和关闭选项。
** [本人博客](https://www.uomn.cn) **

## 功能特性

### 🎯 核心功能
- ✅ 启用/禁用弹窗公告选项
- ✅ 支持HTML代码内容
- ✅ 多种关闭方式（点击关闭、点击空白、倒计时、今日仅显示一次）
- ✅ 前台调用弹出功能
- ✅ 强制显示功能（忽略"今日不再弹出"标记）
- ✅ 智能显示控制（普通显示 vs 强制显示）
- ✅ 基础样式设定
- ✅ 自动关闭时间设置
- ✅ 自适应和固定位置
- ✅ Ajax开关功能

### 🎨 样式定制
- 自定义弹窗宽度和高度
- 自定义背景颜色和文字颜色
- 自定义边框样式和圆角
- 自定义阴影效果
- 支持自定义CSS样式

### 📱 响应式设计
- 移动端自适应
- 最大宽高限制
- 优雅的动画效果

## 安装方法

1. 将 `PopupNotice` 文件夹上传到 Typecho 的 `usr/plugins/` 目录
2. 在 Typecho 后台「控制台」->「插件」中找到「弹窗公告插件」
3. 点击「启用」按钮
4. 点击「设置」进行配置

## 配置选项

### 基础设置
- **是否启用弹窗公告**: 控制插件的开启/关闭
- **弹窗标题**: 设置弹窗的标题文字
- **弹窗内容**: 设置弹窗的内容，支持HTML代码

### 关闭方式
- **点击关闭按钮**: 显示关闭按钮，点击关闭
- **点击空白区域**: 点击弹窗外的空白区域关闭
- **倒计时自动关闭**: 设定时间后自动关闭
- **今日内仅显示一次**: 每天只显示一次，使用localStorage记录

### 显示设置
- **弹窗位置**: 居中/顶部/底部/自定义位置
- **自定义位置CSS**: 当选择自定义位置时的CSS样式
- **弹窗宽度**: 支持px、%、auto等单位
- **弹窗高度**: 支持px、%、auto等单位

### 样式设置
- **背景颜色**: 弹窗背景色
- **文字颜色**: 弹窗文字颜色
- **边框样式**: 弹窗边框样式
- **圆角大小**: 弹窗圆角大小
- **阴影效果**: 弹窗阴影效果
- **自定义CSS**: 额外的自定义样式

### 高级功能
- **Ajax功能**: 启用后可通过JavaScript调用弹窗
- **自动关闭时间**: 倒计时关闭的秒数

## Ajax调用方法

当启用Ajax功能后，可以通过JavaScript调用弹窗：

```javascript
// 显示弹窗（普通显示，会检查"今日不再弹出"标记）
PopupNotice.show();

// 强制显示弹窗（忽略"今日不再弹出"标记）
PopupNotice.show(true);

// 隐藏弹窗
PopupNotice.hide();
```

### 调用说明

- `PopupNotice.show()`: 普通显示，如果用户今日已点击"今日不再弹出"，则不会显示
- `PopupNotice.show(true)`: 强制显示，无论用户是否点击过"今日不再弹出"都会显示
- `PopupNotice.hide()`: 立即隐藏当前显示的弹窗

## 使用示例

### 基础公告
```html
<h4>网站维护通知</h4>
<p>本站将于今晚22:00-24:00进行系统维护，期间可能无法正常访问，敬请谅解。</p>
```

### 富文本公告
```html
<div style="text-align: center;">
    <h3 style="color: #e74c3c;">🎉 新功能上线</h3>
    <p>我们很高兴地宣布，网站新增了以下功能：</p>
    <ul style="text-align: left; display: inline-block;">
        <li>用户评论系统</li>
        <li>文章分享功能</li>
        <li>夜间模式</li>
    </ul>
    <p><a href="/about" style="color: #3498db;">了解更多 →</a></p>
</div>
```

### 重要公告示例
```html
<div class="notice-content">
    <h4>📢 重要公告</h4>
    
    <div class="notice-section">
        <p><strong>🔒 评论系统升级通知</strong></p>
        <p>为了营造更好的交流环境，本站评论系统已接入百度内容审核服务。</p>
    </div>
    
    <div class="notice-section">
        <p><strong>⚠️ 用户行为规范</strong></p>
        <ul>
            <li>所有评论内容将经过智能审核</li>
            <li>用户IP地址和行为数据将被记录</li>
            <li>违规内容将被自动拦截或删除</li>
            <li>严重违规用户将被永久封禁</li>
        </ul>
    </div>
    
    <div class="notice-section">
        <p><strong>📝 文明评论倡议</strong></p>
        <p>请大家遵守网络文明，理性发言，共同维护良好的社区环境。</p>
    </div>
    
    <div class="contact-info">
        <p><strong>📱 联系站长</strong></p>
        <p>如有问题或建议，请联系站长微信：<code>AO-XU-OA</code></p>
    </div>
    
    <div class="notice-footer">
        <p><em>感谢您的理解与配合！</em></p>
    </div>
</div>
```

### JavaScript调用示例
```html
<!-- 在页面中添加按钮 -->
<button onclick="showNormalNotice()">显示普通公告</button>
<button onclick="showForceNotice()">强制显示公告</button>
<button onclick="hideNotice()">隐藏公告</button>

<script>
// 显示普通公告（会检查今日标记）
function showNormalNotice() {
    if (typeof PopupNotice !== 'undefined') {
        PopupNotice.show();
    }
}

// 强制显示公告（忽略今日标记）
function showForceNotice() {
    if (typeof PopupNotice !== 'undefined') {
        PopupNotice.show(true);
    }
}

// 隐藏公告
function hideNotice() {
    if (typeof PopupNotice !== 'undefined') {
        PopupNotice.hide();
    }
}
</script>
```

## 技术特性

- 纯JavaScript实现，无依赖
- 支持现代浏览器
- 优雅的CSS3动画
- 移动端友好
- 轻量级设计

## 浏览器兼容性

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- 移动端浏览器

## 更新日志

### v1.1.0
- ✨ 新增强制显示功能
- ✨ 支持 `PopupNotice.show(true)` 强制显示弹窗
- ✨ 智能显示控制：普通显示 vs 强制显示
- 🐛 修复禁用插件时的JavaScript语法错误
- 📝 更新文档和使用示例
- 🧹 清理无用的测试文件

### v1.0.0
- 初始版本发布
- 实现所有核心功能
- 支持多种关闭方式
- 支持Ajax调用
- 响应式设计

## 许可证

MIT License

## 免责声明
本插件仅供学习和研究使用，使用者需要自行承担使用风险。开发者不对因使用本插件而导致的任何损失承担责任。
