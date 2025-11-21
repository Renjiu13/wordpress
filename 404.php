<?php get_header(); ?>

<div class="col-8" id="main">
    <div class="res-cons">
        <div class="error-page">
            <h1>404</h1>
            <div class="error-page-divider"></div>
            <h2 class="post-title"><?php _e('页面未找到', 'maupassant'); ?></h2>
            <p><?php _e('抱歉，您访问的页面不存在或已被移除。', 'maupassant'); ?></p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="home-link">
                <?php _e('返回首页', 'maupassant'); ?>
            </a>
            <div class="search-form-wrapper">
                <?php get_search_form(); ?>
            </div>
        </div>
    </div>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>