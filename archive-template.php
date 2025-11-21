<?php
/*
Template Name: 文章归档
Description: 显示按年归档的文章列表
*/
get_header(); ?>

<div id="primary" class="content-area">
    <div class="container">
        <div class="row">
            <main id="main" class="site-main col-md-8">
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header>

                    <div class="entry-content">
                        <?php the_content(); ?>
                        
                        <!-- 添加年份快速导航 -->
                        <div class="archive-navigation">
                            <?php
                            // 使用缓存优化年份查询
                            $cache_key = 'archive_years_' . get_current_blog_id();
                            $years = wp_cache_get($cache_key);
                            
                            if (false === $years) {
                                global $wpdb;
                                $years = $wpdb->get_col("
                                    SELECT DISTINCT YEAR(post_date) 
                                    FROM $wpdb->posts 
                                    WHERE post_status = 'publish' 
                                    AND post_type = 'post' 
                                    ORDER BY post_date DESC
                                ");
                                wp_cache_set($cache_key, $years, '', 3600); // 缓存1小时
                            }
                            
                            if($years) :
                            ?>
                                <div class="year-links">
                                    <?php foreach($years as $year) : ?>
                                        <a href="#year-<?php echo esc_attr($year); ?>" class="year-link"><?php echo esc_html($year); ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="archive-content">
                            <?php
                            // 使用缓存优化文章查询
                            $cache_key = 'archive_posts_' . get_current_blog_id();
                            $all_posts = wp_cache_get($cache_key);
                            
                            if (false === $all_posts) {
                                global $wpdb;
                                // 优化查询：一次性获取文章和分类信息
                                $all_posts = $wpdb->get_results("
                                    SELECT p.ID, p.post_title, p.post_date,
                                           GROUP_CONCAT(t.name ORDER BY t.name ASC SEPARATOR ',') as categories
                                    FROM $wpdb->posts p
                                    LEFT JOIN $wpdb->term_relationships tr ON p.ID = tr.object_id
                                    LEFT JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                                    LEFT JOIN $wpdb->terms t ON tt.term_id = t.term_id AND tt.taxonomy = 'category'
                                    WHERE p.post_status = 'publish' 
                                    AND p.post_type = 'post' 
                                    GROUP BY p.ID
                                    ORDER BY p.post_date DESC
                                ");
                                wp_cache_set($cache_key, $all_posts, '', 1800); // 缓存30分钟
                            }
                            
                            // 按年份分组
                            $posts_by_year = array();
                            foreach($all_posts as $post) {
                                $year = date('Y', strtotime($post->post_date));
                                if (!isset($posts_by_year[$year])) {
                                    $posts_by_year[$year] = array();
                                }
                                $posts_by_year[$year][] = $post;
                            }
                            
                            // 遍历每一年
                            foreach($posts_by_year as $year => $year_posts) :
                                // 按月份分组
                                $posts_by_month = array();
                                foreach($year_posts as $post) {
                                    $month = date('n', strtotime($post->post_date));
                                    if (!isset($posts_by_month[$month])) {
                                        $posts_by_month[$month] = array();
                                    }
                                    $posts_by_month[$month][] = $post;
                                }
                            ?>
                                <div id="year-<?php echo esc_attr($year); ?>" class="archive-year">
                                    <h2><?php echo esc_html($year); ?> <span class="post-count">(<?php echo count($year_posts); ?>篇)</span></h2>
                                    
                                    <?php 
                                    // 按月份分组显示文章
                                    foreach($posts_by_month as $month => $month_posts) : 
                                        // 获取月份名称
                                        $month_name = date_i18n('F', mktime(0, 0, 0, $month, 1, $year));
                                    ?>
                                        <div class="archive-month">
                                            <h3><?php echo esc_html($month_name); ?></h3>
                                            <ul class="archive-posts">
                                                <?php foreach($month_posts as $post) : ?>
                                                    <li class="archive-post-item">
                                                        <span class="post-date"><?php echo date_i18n('d日', strtotime($post->post_date)); ?></span>
                                                        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="post-title">
                                                            <?php echo esc_html($post->post_title); ?>
                                                        </a>
                                                        <?php 
                                                        // 显示文章分类（从优化查询中获取）
                                                        if (!empty($post->categories)) :
                                                            $categories = explode(',', $post->categories);
                                                        ?>
                                                        <span class="post-categories">
                                                            <?php 
                                                            foreach($categories as $category_name) {
                                                                $category_name = trim($category_name);
                                                                if (!empty($category_name)) {
                                                                    echo '<a href="#" class="category-link">' . esc_html($category_name) . '</a>';
                                                                }
                                                            }
                                                            ?>
                                                        </span>
                                                        <?php endif; ?>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                            
                            <?php if (empty($all_posts)) : ?>
                                <div class="no-posts">
                                    <p>暂无文章</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            </main>

            <div id="secondary" class="widget-area col-md-4" role="complementary">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</div>

<style>
.archive-year {
    margin-bottom: 30px;
    transition: all 0.3s ease;
}
.archive-year h2 {
    font-size: 22px;
    color: #333;
    margin-bottom: 15px;
    padding-bottom: 5px;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.post-count {
    font-size: 14px;
    color: #888;
    font-weight: normal;
}
.archive-month {
    margin-left: 20px;
    margin-bottom: 20px;
}
.archive-month h3 {
    font-size: 18px;
    color: #555;
    margin-bottom: 10px;
    padding-left: 10px;
    border-left: 3px solid #ddd;
}
.archive-posts {
    list-style-type: disc;
    padding-left: 25px;
    margin-top: 10px;
}
.archive-post-item {
    margin-bottom: 12px;
    line-height: 1.5;
    display: flex;
    align-items: baseline;
    transition: transform 0.2s ease;
}
.archive-post-item:hover {
    transform: translateX(5px);
}
.archive-post-item:before {
    content: none;
}
.post-date {
    color: #333;
    margin-right: 8px;
    font-size: 14px;
    white-space: nowrap;
    font-weight: 500;
}
.post-title {
    text-decoration: none;
    color: #666;
    font-size: 15px;
    transition: color 0.3s ease;
}
.post-title:hover {
    color: #000;
    text-decoration: underline;
}
.post-categories {
    margin-left: 10px;
    font-size: 12px;
}
.category-link {
    display: inline-block;
    margin-right: 5px;
    padding: 2px 6px;
    background: #f5f5f5;
    color: #666;
    border-radius: 3px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.category-link:hover {
    background: #e0e0e0;
    color: #333;
}
/* 确保内容区域有足够的高度 */
#primary {
    min-height: 80vh;
}
/* 清除浮动 */
.row:after {
    content: "";
    display: table;
    clear: both;
}
/* 修复导航栏对齐问题 */
#logo {
    display: inline-block;
    float: none;
    vertical-align: middle;
    text-decoration: none;
    color: #333;
    font-weight: bold;
    /* 字体大小由 site-logo.css 控制 */
}
/* 年份导航样式 */
.archive-navigation {
    margin-bottom: 30px;
}
.year-links {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}
.year-link {
    display: inline-block;
    padding: 5px 12px;
    background: #f5f5f5;
    color: #333;
    text-decoration: none;
    border-radius: 3px;
    transition: all 0.3s ease;
}
.year-link:hover {
    background: #333;
    color: #fff;
}
.no-posts {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    font-style: italic;
}
@media (max-width: 768px) {
    .archive-post-item {
        flex-direction: column;
        align-items: flex-start;
    }
    .post-date {
        margin-bottom: 5px;
    }
    .archive-month {
        margin-left: 10px;
    }
    .post-categories {
        margin-left: 0;
        margin-top: 5px;
        display: block;
    }
}
</style>

<?php get_footer(); ?>
