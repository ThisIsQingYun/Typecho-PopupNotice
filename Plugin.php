<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * 弹窗公告插件
 * 
 * @package PopupNotice
 * @author QingYun
 * @version 1.1.0
 * @link https://www.dogecloud.com/
 */
class PopupNotice_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array('PopupNotice_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('PopupNotice_Plugin', 'footer');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        // 是否启用弹窗公告
        $enable = new Typecho_Widget_Helper_Form_Element_Radio('enable', 
            array('1' => _t('启用'), '0' => _t('禁用')), 
            '1', _t('是否启用弹窗公告'), _t('选择是否启用弹窗公告功能'));
        $form->addInput($enable);
        
        // 弹窗标题
        $title = new Typecho_Widget_Helper_Form_Element_Text('title', NULL, '网站公告', 
            _t('弹窗标题'), _t('设置弹窗的标题'));
        $form->addInput($title);
        
        // 弹窗内容(支持HTML)
        $content = new Typecho_Widget_Helper_Form_Element_Textarea('content', NULL, 
            '<p>欢迎访问本站！</p>', _t('弹窗内容'), 
            _t('弹窗的内容，支持HTML代码'));
        $form->addInput($content);
        
        // 关闭方式
        $closeType = new Typecho_Widget_Helper_Form_Element_Checkbox('closeType', 
            array(
                'click' => _t('点击关闭按钮'),
                'overlay' => _t('点击空白区域'),
                'timer' => _t('倒计时自动关闭'),
                'once' => _t('今日内仅显示一次')
            ), 
            array('click', 'overlay'), _t('关闭方式'), 
            _t('选择弹窗的关闭方式，可多选'));
        $form->addInput($closeType);
        
        // 自动关闭时间
        $autoCloseTime = new Typecho_Widget_Helper_Form_Element_Text('autoCloseTime', NULL, '5', 
            _t('自动关闭时间(秒)'), _t('设置倒计时自动关闭的时间，仅在选择倒计时关闭时有效'));
        $form->addInput($autoCloseTime);
        
        // 弹窗位置
        $position = new Typecho_Widget_Helper_Form_Element_Radio('position', 
            array(
                'center' => _t('居中显示'),
                'top' => _t('顶部显示'),
                'bottom' => _t('底部显示'),
                'custom' => _t('自定义位置')
            ), 
            'center', _t('弹窗位置'), _t('选择弹窗的显示位置'));
        $form->addInput($position);
        
        // 自定义位置
        $customPosition = new Typecho_Widget_Helper_Form_Element_Text('customPosition', NULL, 
            'top: 50%; left: 50%; transform: translate(-50%, -50%);', 
            _t('自定义位置CSS'), _t('当选择自定义位置时的CSS样式'));
        $form->addInput($customPosition);
        
        // 弹窗宽度
        $width = new Typecho_Widget_Helper_Form_Element_Text('width', NULL, '400px', 
            _t('弹窗宽度'), _t('设置弹窗的宽度，可使用px、%、auto等单位'));
        $form->addInput($width);
        
        // 弹窗高度
        $height = new Typecho_Widget_Helper_Form_Element_Text('height', NULL, 'auto', 
            _t('弹窗高度'), _t('设置弹窗的高度，可使用px、%、auto等单位'));
        $form->addInput($height);
        
        // 背景颜色
        $bgColor = new Typecho_Widget_Helper_Form_Element_Text('bgColor', NULL, '#ffffff', 
            _t('背景颜色'), _t('设置弹窗的背景颜色'));
        $form->addInput($bgColor);
        
        // 文字颜色
        $textColor = new Typecho_Widget_Helper_Form_Element_Text('textColor', NULL, '#333333', 
            _t('文字颜色'), _t('设置弹窗的文字颜色'));
        $form->addInput($textColor);
        
        // 边框样式
        $border = new Typecho_Widget_Helper_Form_Element_Text('border', NULL, '1px solid #ddd', 
            _t('边框样式'), _t('设置弹窗的边框样式'));
        $form->addInput($border);
        
        // 圆角大小
        $borderRadius = new Typecho_Widget_Helper_Form_Element_Text('borderRadius', NULL, '8px', 
            _t('圆角大小'), _t('设置弹窗的圆角大小'));
        $form->addInput($borderRadius);
        
        // 阴影效果
        $boxShadow = new Typecho_Widget_Helper_Form_Element_Text('boxShadow', NULL, 
            '0 4px 20px rgba(0,0,0,0.15)', _t('阴影效果'), _t('设置弹窗的阴影效果'));
        $form->addInput($boxShadow);
        
        // Ajax开关
        $ajaxEnable = new Typecho_Widget_Helper_Form_Element_Radio('ajaxEnable', 
            array('1' => _t('启用'), '0' => _t('禁用')), 
            '1', _t('Ajax功能'), _t('是否启用Ajax功能，启用后可通过Ajax调用弹窗'));
        $form->addInput($ajaxEnable);
        
        // 自定义CSS
        $customCss = new Typecho_Widget_Helper_Form_Element_Textarea('customCss', NULL, '', 
            _t('自定义CSS'), _t('可以添加自定义的CSS样式'));
        $form->addInput($customCss);
        
        // 调用代码展示
        $callCode = new Typecho_Widget_Helper_Form_Element_Textarea('callCode', NULL, 
            "<!-- 手动调用弹窗公告代码 -->\n<!-- 注意：使用以下代码前，请确保已启用Ajax功能 -->\n<script>\n// 普通显示弹窗（会检查\"今日不再弹出\"标记）\nif (typeof PopupNotice !== 'undefined') {\n    PopupNotice.show();\n}\n\n// 强制显示弹窗（忽略\"今日不再弹出\"标记）\nif (typeof PopupNotice !== 'undefined') {\n    PopupNotice.show(true);\n}\n\n// 隐藏弹窗\nif (typeof PopupNotice !== 'undefined') {\n    PopupNotice.hide();\n}\n</script>", 
            _t('前台调用代码'), _t('复制以下代码到前台页面可手动控制弹窗显示/隐藏，支持普通显示和强制显示。注意：使用前请确保已启用Ajax功能。'));
        $callCode->input->setAttribute('readonly', 'readonly');
        $callCode->input->setAttribute('style', 'background-color: #f8f9fa; font-family: monospace; font-size: 12px; height: 180px;');
        $form->addInput($callCode);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 插件实现方法
     * 
     * @access public
     * @return void
     */
    public static function header()
    {
        $options = Helper::options();
        $plugin = $options->plugin('PopupNotice');
        
        if (!$plugin || !$plugin->enable) {
            return;
        }
        
        echo '<style id="popup-notice-style">';
        echo self::getPopupCSS();
        echo '</style>';
    }
    
    /**
     * 页面底部输出
     */
    public static function footer()
    {
        $options = Helper::options();
        $plugin = $options->plugin('PopupNotice');
        
        if (!$plugin || !$plugin->enable) {
            return;
        }
        
        echo self::getPopupHTML();
        echo self::getPopupJS();
    }
    
    /**
     * 获取弹窗CSS样式
     */
    private static function getPopupCSS()
    {
        $options = Helper::options();
        $plugin = $options->plugin('PopupNotice');
        
        $position = isset($plugin->position) ? $plugin->position : 'center';
        $customPosition = isset($plugin->customPosition) ? $plugin->customPosition : 'top: 50%; left: 50%; transform: translate(-50%, -50%);';
        $width = isset($plugin->width) ? $plugin->width : '400px';
        $height = isset($plugin->height) ? $plugin->height : 'auto';
        $bgColor = isset($plugin->bgColor) ? $plugin->bgColor : '#ffffff';
        $textColor = isset($plugin->textColor) ? $plugin->textColor : '#333333';
        $border = isset($plugin->border) ? $plugin->border : '1px solid #ddd';
        $borderRadius = isset($plugin->borderRadius) ? $plugin->borderRadius : '8px';
        $boxShadow = isset($plugin->boxShadow) ? $plugin->boxShadow : '0 4px 20px rgba(0,0,0,0.15)';
        $customCss = isset($plugin->customCss) ? $plugin->customCss : '';
        
        $positionCSS = '';
        switch ($position) {
            case 'center':
                $positionCSS = 'top: 50%; left: 50%; transform: translate(-50%, -50%);';
                break;
            case 'top':
                $positionCSS = 'top: 20px; left: 50%; transform: translateX(-50%);';
                break;
            case 'bottom':
                $positionCSS = 'bottom: 20px; left: 50%; transform: translateX(-50%);';
                break;
            case 'custom':
                $positionCSS = $customPosition;
                break;
        }
        
        $css = '
        .popup-notice-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
        }
        
        body.popup-notice-no-scroll {
            overflow: hidden;
            position: fixed;
            width: 100%;
        }
        
        .popup-notice-container {
            position: fixed;
            ' . $positionCSS . '
            width: ' . $width . ';
            height: ' . $height . ';
            background: ' . $bgColor . ';
            color: ' . $textColor . ';
            border: ' . $border . ';
            border-radius: ' . $borderRadius . ';
            box-shadow: ' . $boxShadow . ';
            z-index: 10000;
            padding: 20px;
            box-sizing: border-box;
            max-width: 90vw;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .popup-notice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .popup-notice-title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        
        .popup-notice-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .popup-notice-close:hover {
            color: #666;
        }
        
        .popup-notice-content {
            line-height: 1.6;
            max-height: 60vh;
            overflow-y: auto;
            padding-right: 5px;
        }
        
        .popup-notice-content::-webkit-scrollbar {
            width: 6px;
        }
        
        .popup-notice-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        
        .popup-notice-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        
        .popup-notice-content::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        .popup-notice-timer {
            text-align: center;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 14px;
        }
        
        .popup-notice-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
        
        .popup-notice-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            min-width: 80px;
        }
        
        .popup-notice-btn-primary {
            background: #007cba;
            color: white;
        }
        
        .popup-notice-btn-primary:hover {
            background: #005a87;
        }
        
        .popup-notice-btn-secondary {
            background: #f1f1f1;
            color: #666;
        }
        
        .popup-notice-btn-secondary:hover {
            background: #e1e1e1;
        }
        
        @media (max-width: 768px) {
            .popup-notice-container {
                width: 90vw;
                max-width: 90vw;
                padding: 15px;
            }
            
            .popup-notice-buttons {
                flex-direction: column;
                gap: 8px;
            }
            
            .popup-notice-btn {
                width: 100%;
                padding: 10px 20px;
            }
        }
        ' . $customCss;
        
        return $css;
    }
    
    /**
     * 获取弹窗HTML结构
     */
    private static function getPopupHTML()
    {
        $options = Helper::options();
        $plugin = $options->plugin('PopupNotice');
        
        $title = isset($plugin->title) ? $plugin->title : '网站公告';
        $content = isset($plugin->content) ? $plugin->content : '<p>欢迎访问本站！</p>';
        $closeType = isset($plugin->closeType) ? $plugin->closeType : array('click', 'overlay');
        $autoCloseTime = isset($plugin->autoCloseTime) ? $plugin->autoCloseTime : '5';
        
        $showTimer = is_array($closeType) && in_array('timer', $closeType);
        $showCloseBtn = is_array($closeType) && in_array('click', $closeType);
        
        $html = '
        <div id="popup-notice-overlay" class="popup-notice-overlay">
            <div id="popup-notice-container" class="popup-notice-container">
                <div class="popup-notice-header">
                    <h3 class="popup-notice-title">' . htmlspecialchars($title) . '</h3>';
        
        if ($showCloseBtn) {
            $html .= '<button id="popup-notice-close" class="popup-notice-close">&times;</button>';
        }
        
        $html .= '
                </div>
                <div class="popup-notice-content">' . $content . '</div>';
        
        if ($showTimer) {
            $html .= '<div id="popup-notice-timer" class="popup-notice-timer">窗口将在 <span id="popup-timer-count">' . $autoCloseTime . '</span> 秒后自动关闭</div>';
        }
        
        // 添加按钮区域
        $html .= '
                <div class="popup-notice-buttons">
                    <button id="popup-notice-confirm" class="popup-notice-btn popup-notice-btn-primary">确认</button>
                    <button id="popup-notice-no-today" class="popup-notice-btn popup-notice-btn-secondary">今日不再弹出</button>
                </div>';
        
        $html .= '
            </div>
        </div>';
        
        return $html;
    }
    
    /**
     * 获取弹窗JavaScript代码
     */
    private static function getPopupJS()
    {
        $options = Helper::options();
        $plugin = $options->plugin('PopupNotice');
        
        $closeType = isset($plugin->closeType) ? $plugin->closeType : array('click', 'overlay');
        $autoCloseTime = intval(isset($plugin->autoCloseTime) ? $plugin->autoCloseTime : '5');
        $ajaxEnable = isset($plugin->ajaxEnable) ? $plugin->ajaxEnable : '1';
        
        $showOnce = is_array($closeType) && in_array('once', $closeType);
        $showTimer = is_array($closeType) && in_array('timer', $closeType);
        $clickOverlay = is_array($closeType) && in_array('overlay', $closeType);
        $clickClose = is_array($closeType) && in_array('click', $closeType);
        
        $js = '
        <script>
        (function() {
            var PopupNotice = {
                overlay: null,
                container: null,
                timer: null,
                timeLeft: ' . $autoCloseTime . ',
                
                init: function() {
                    this.overlay = document.getElementById("popup-notice-overlay");
                    this.container = document.getElementById("popup-notice-container");
                    
                    if (!this.overlay || !this.container) return;
                    
                    this.bindEvents();
                    this.show();
                },
                
                bindEvents: function() {
                    // 绑定确认按钮事件
                    var confirmBtn = document.getElementById("popup-notice-confirm");
                    if (confirmBtn) {
                        confirmBtn.addEventListener("click", this.close.bind(this));
                    }
                    
                    // 绑定今日不再弹出按钮事件
                    var noTodayBtn = document.getElementById("popup-notice-no-today");
                    if (noTodayBtn) {
                        noTodayBtn.addEventListener("click", this.noTodayClose.bind(this));
                    }';
        
        if ($clickClose) {
            $js .= '
                    
                    var closeBtn = document.getElementById("popup-notice-close");
                    if (closeBtn) {
                        closeBtn.addEventListener("click", this.close.bind(this));
                    }';
        }
        
        if ($clickOverlay) {
            $js .= '
                    
                    this.overlay.addEventListener("click", function(e) {
                        if (e.target === this.overlay) {
                            this.close();
                        }
                    }.bind(this));';
        }
        
        $js .= '
                },
                
                show: function(force) {';
        
        // 如果不是强制显示，则检查今日不再弹出标记
        $js .= '
                    if (!force) {
                        var today = new Date().toDateString();
                        var lastShown = localStorage.getItem("popup-notice-last-shown");
                        if (lastShown === today) {
                            return;
                        }
                    }';
        
        // 只有启用once选项时才自动设置标记
        if ($showOnce) {
            $js .= '
                    localStorage.setItem("popup-notice-last-shown", today);';
        }
        
        $js .= '
                    // 禁止页面滚动
                    this.disableScroll();
                    
                    this.overlay.style.display = "block";
                    
                    // 添加动画效果，保持原有的定位transform
                    var computedStyle = window.getComputedStyle(this.container);
                    var originalTransform = computedStyle.transform;
                    if (originalTransform === "none") originalTransform = "";
                    
                    this.container.style.opacity = "0";
                    this.container.style.transform = originalTransform + " scale(0.8)";
                    
                    setTimeout(function() {
                        this.container.style.transition = "all 0.3s ease";
                        this.container.style.opacity = "1";
                        this.container.style.transform = originalTransform + " scale(1)";
                    }.bind(this), 10);';
        
        if ($showTimer) {
            $js .= '
                    this.startTimer();';
        }
        
        $js .= '
                },
                
                close: function() {
                    if (this.timer) {
                        clearInterval(this.timer);
                    }
                    
                    // 获取原始定位transform
                    var computedStyle = window.getComputedStyle(this.container);
                    var originalTransform = computedStyle.transform;
                    if (originalTransform === "none") originalTransform = "";
                    // 移除scale部分，保留定位部分
                    originalTransform = originalTransform.replace(/\s*scale\([^)]*\)/g, "");
                    
                    this.container.style.transition = "all 0.3s ease";
                    this.container.style.opacity = "0";
                    this.container.style.transform = originalTransform + " scale(0.8)";
                    
                    setTimeout(function() {
                        this.overlay.style.display = "none";
                        // 重置样式，避免下次显示时出现问题
                        this.container.style.transition = "";
                        this.container.style.transform = "";
                        this.container.style.opacity = "";
                        // 恢复页面滚动
                        this.enableScroll();
                    }.bind(this), 300);
                },
                
                noTodayClose: function() {
                    // 设置今日不再弹出标记
                    var today = new Date().toDateString();
                    localStorage.setItem("popup-notice-last-shown", today);
                    
                    // 关闭弹窗
                    this.close();
                },
                
                disableScroll: function() {
                    this.scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    document.body.style.top = "-" + this.scrollTop + "px";
                    document.body.classList.add("popup-notice-no-scroll");
                },
                
                enableScroll: function() {
                    document.body.classList.remove("popup-notice-no-scroll");
                    document.body.style.top = "";
                    window.scrollTo(0, this.scrollTop);
                }';
        
        // 添加条件性方法
        $additionalMethods = array();
        $hasAdditionalMethods = false;
        
        if ($showTimer) {
            $additionalMethods[] = '
                startTimer: function() {
                    var timerElement = document.getElementById("popup-timer-count");
                    if (!timerElement) return;
                    
                    this.timer = setInterval(function() {
                        this.timeLeft--;
                        timerElement.textContent = this.timeLeft;
                        
                        if (this.timeLeft <= 0) {
                            this.close();
                        }
                    }.bind(this), 1000);
                }';
            $hasAdditionalMethods = true;
        }
        
        if ($ajaxEnable) {
            $additionalMethods[] = '
                // Ajax接口
                showPopup: function() {
                    this.show();
                },
                
                hidePopup: function() {
                    this.close();
                }';
            $hasAdditionalMethods = true;
        }
        
        if ($hasAdditionalMethods) {
            $js .= ',' . implode(',', $additionalMethods);
        }
        
        $js .= '
            };
            
            // 页面加载完成后初始化
            if (document.readyState === "loading") {
                document.addEventListener("DOMContentLoaded", function() {
                    PopupNotice.init();
                });
            } else {
                PopupNotice.init();
            }';
        
        if ($ajaxEnable) {
            $js .= '
            
            // 全局暴露Ajax接口
            window.PopupNotice = {
                show: function(force) {
                    if (force) {
                        PopupNotice.show(true);
                    } else {
                        PopupNotice.showPopup();
                    }
                },
                hide: function() {
                    PopupNotice.hidePopup();
                }
            };';
        }
        
        $js .= '
        })();
        </script>';
        
        return $js;
    }
}
