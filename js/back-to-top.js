jQuery(document).ready(function($){
    var $scrollButton = $(".scroll-to-top");
    var scrollThreshold = 300;
    
    // 检查页面是否可滚动
    function isPageScrollable() {
        return $(document).height() > $(window).height() + 10;
    }
    
    // 如果页面不可滚动，直接返回
    if (!isPageScrollable()) {
        return;
    }
    
    // 统一的显示/隐藏逻辑
    function toggleScrollButton() {
        if ($(window).scrollTop() > scrollThreshold) {
            $scrollButton.fadeIn(800);
        } else {
            $scrollButton.fadeOut(350);
        }
    }
    
    // 初始检查滚动位置
    toggleScrollButton();
    
    // 监听滚动事件（使用节流优化性能）
    var scrollTimer;
    $(window).scroll(function() {
        clearTimeout(scrollTimer);
        scrollTimer = setTimeout(toggleScrollButton, 10);
    });
    
    // 点击按钮回到顶部
    $scrollButton.click(function(){
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });
});