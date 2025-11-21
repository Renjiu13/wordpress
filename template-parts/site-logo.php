<?php
/**
 * Template part for displaying site logo and name
 * 
 * @package Maupassant
 */

// 获取博客信息
$site_name = get_bloginfo('name');
$site_description = get_bloginfo('description');
$home_url = esc_url(home_url('/'));

// 检查是否为首页
$is_front_page = is_front_page() && is_home();
?>

<div class="site-name">
    <?php if ($is_front_page) : ?>
        <h1>
            <a id="logo" href="<?php echo $home_url; ?>" title="<?php echo esc_attr($site_name); ?>">
                <?php echo esc_html($site_name); ?>
            </a>
        </h1>
    <?php else : ?>
        <a id="logo" href="<?php echo $home_url; ?>" title="<?php echo esc_attr($site_name); ?>">
            <?php echo esc_html($site_name); ?>
        </a>
    <?php endif; ?>
    
    <?php if (!empty($site_description)) : ?>
        <p class="description"><?php echo esc_html($site_description); ?></p>
    <?php endif; ?>
</div> 