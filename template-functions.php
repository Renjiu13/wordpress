/**
 * Show posts pagination.
 */
function maupassant_posts_pagination() {
	global $wp_query;
	$paged = max(1, get_query_var('paged', 1));
	$max = isset($wp_query->max_num_pages) ? (int)$wp_query->max_num_pages : 1;
	if ($max <= 1) return;
	
	// 添加分页包装器，用于更好的布局控制
	echo '<div class="pagination-wrapper">';
	echo '<nav class="maupassant-pagination-breadcrumbs" aria-label="文章导航">';
	
	// 计算下一页的URL
	$next_page_url = '';
	if ($paged < $max) {
		$next_page_url = get_pagenum_link($paged + 1);
	}
	
	// 显示分页信息，如果当前页不是最后一页，则"共Y页"可点击
	if ($paged < $max && $next_page_url) {
		echo '<span class="pagination-info">第 ' . $paged . ' 页，<a href="' . esc_url($next_page_url) . '" class="total-pages-link">共 ' . $max . ' 页</a></span>';
	} else {
		echo '<span class="pagination-info">第 ' . $paged . ' 页，共 ' . $max . ' 页</span>';
	}
	
	echo '</nav>';
	echo '</div>';
} 