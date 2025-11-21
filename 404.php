<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Maupassant
 */

get_header();
?>

<div class="col-8" id="main" role="main">
    <div class="res-cons">
        <article class="error-page" role="article">
            <!-- 404 大标题 -->
            <div class="error-code-wrapper">
                <h1 class="error-code" aria-label="<?php esc_attr_e( 'Error 404', 'maupassant' ); ?>">404</h1>
                <div class="error-code-shadow" aria-hidden="true">404</div>
            </div>
            
            <div class="error-page-divider" role="separator"></div>
            
            <!-- 错误信息 -->
            <h2 class="error-title"><?php esc_html_e( '页面未找到', 'maupassant' ); ?></h2>
            <p class="error-description">
                <?php esc_html_e( '抱歉，您访问的页面不存在或已被移除。', 'maupassant' ); ?>
            </p>
            
            <!-- 可能的原因 -->
            <div class="error-reasons">
                <h3><?php esc_html_e( '可能的原因：', 'maupassant' ); ?></h3>
                <ul>
                    <li><?php esc_html_e( '页面地址输入错误', 'maupassant' ); ?></li>
                    <li><?php esc_html_e( '页面已被删除或移动', 'maupassant' ); ?></li>
                    <li><?php esc_html_e( '链接已过期', 'maupassant' ); ?></li>
                </ul>
            </div>
            
            <!-- 操作按钮 -->
            <div class="error-actions">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                    <span class="btn-icon" aria-hidden="true">🏠</span>
                    <?php esc_html_e( '返回首页', 'maupassant' ); ?>
                </a>
                <button type="button" class="btn btn-secondary" onclick="window.history.back();">
                    <span class="btn-icon" aria-hidden="true">←</span>
                    <?php esc_html_e( '返回上一页', 'maupassant' ); ?>
                </button>
            </div>
            
            <!-- 搜索表单 -->
            <div class="error-search">
                <h3><?php esc_html_e( '试试搜索：', 'maupassant' ); ?></h3>
                <div class="search-form-wrapper">
                    <?php get_search_form(); ?>
                </div>
            </div>
            
            <!-- 热门文章 -->
            <?php
            $popular_posts = new WP_Query( array(
                'posts_per_page'      => 5,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => 1,
                'orderby'             => 'comment_count',
                'order'               => 'DESC',
            ) );
            
            if ( $popular_posts->have_posts() ) :
            ?>
                <div class="error-popular-posts">
                    <h3><?php esc_html_e( '热门文章：', 'maupassant' ); ?></h3>
                    <ul class="popular-posts-list">
                        <?php
                        while ( $popular_posts->have_posts() ) :
                            $popular_posts->the_post();
                        ?>
                            <li>
                                <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
                                    <?php the_title(); ?>
                                </a>
                                <span class="post-date"><?php echo esc_html( get_the_date() ); ?></span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            <?php
                wp_reset_postdata();
            endif;
            ?>
            
            <!-- 分类列表 -->
            <?php
            $categories = get_categories( array(
                'orderby'    => 'count',
                'order'      => 'DESC',
                'number'     => 8,
                'hide_empty' => true,
            ) );
            
            if ( ! empty( $categories ) ) :
            ?>
                <div class="error-categories">
                    <h3><?php esc_html_e( '浏览分类：', 'maupassant' ); ?></h3>
                    <div class="categories-grid">
                        <?php foreach ( $categories as $category ) : ?>
                            <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" 
                               class="category-item"
                               title="<?php echo esc_attr( sprintf( __( 'View all posts in %s', 'maupassant' ), $category->name ) ); ?>">
                                <span class="category-name"><?php echo esc_html( $category->name ); ?></span>
                                <span class="category-count"><?php echo esc_html( $category->count ); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- 联系信息 -->
            <div class="error-contact">
                <p>
                    <?php
                    printf(
                        /* translators: %s: contact page link */
                        esc_html__( '如果您认为这是一个错误，请 %s。', 'maupassant' ),
                        '<a href="' . esc_url( home_url( '/contact' ) ) . '">' . esc_html__( '联系我们', 'maupassant' ) . '</a>'
                    );
                    ?>
                </p>
            </div>
        </article>
    </div>
</div>

<?php
get_sidebar();
get_footer();