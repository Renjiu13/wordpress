document.addEventListener('DOMContentLoaded', function () {
    // 检查是否支持现代剪贴板API
    const isClipboardSupported = navigator.clipboard && navigator.clipboard.writeText;
    
    document.querySelectorAll('pre > code').forEach(function (codeBlock) {
        // 创建按钮
        var button = document.createElement('button');
        button.className = 'copy-code-btn';
        button.type = 'button';
        button.innerText = 'Copy';
        button.setAttribute('aria-label', '复制代码');

        // 按钮点击事件
        button.addEventListener('click', function () {
            var text = codeBlock.innerText || codeBlock.textContent;
            
            if (isClipboardSupported) {
                // 使用现代剪贴板API
                navigator.clipboard.writeText(text).then(function () {
                    showCopySuccess(button);
                }).catch(function (err) {
                    console.error('复制失败:', err);
                    fallbackCopyTextToClipboard(text, button);
                });
            } else {
                // 降级到传统方法
                fallbackCopyTextToClipboard(text, button);
            }
        });

        // 插入按钮到 pre
        var pre = codeBlock.parentNode;
        if (pre && pre.tagName === 'PRE') {
            pre.style.position = 'relative';
            pre.appendChild(button);
        }
    });
    
    // 显示复制成功状态
    function showCopySuccess(button) {
        var originalText = button.innerText;
        button.innerText = 'Copied!';
        button.style.background = '#28a745';
        
        setTimeout(function () {
            button.innerText = originalText;
            button.style.background = '';
        }, 1200);
    }
    
    // 显示复制失败状态
    function showCopyError(button) {
        var originalText = button.innerText;
        button.innerText = 'Failed';
        button.style.background = '#dc3545';
        
        setTimeout(function () {
            button.innerText = originalText;
            button.style.background = '';
        }, 1200);
    }
    
    // 传统复制方法（兼容性支持）
    function fallbackCopyTextToClipboard(text, button) {
        try {
            // 创建临时文本区域
            var textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            
            // 选择文本并复制
            textArea.focus();
            textArea.select();
            
            var successful = document.execCommand('copy');
            document.body.removeChild(textArea);
            
            if (successful) {
                showCopySuccess(button);
            } else {
                showCopyError(button);
            }
        } catch (err) {
            console.error('传统复制方法失败:', err);
            showCopyError(button);
        }
    }
}); 