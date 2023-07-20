<?php

/**
 * Template Name: アーカイブ
 * @package WordPress
 * @Template Post Type: post, page,
 * @subpackage I'LL

 */
get_header(); ?>

<?php wp_safe_redirect("$wp_blog_url") ?>
<!-- mv
<div class="mv">
	<?php
    $args = array(
        'post_type' => 'post',
        // 'paged' => $paged,

        'order' => 'DESC',
        'posts_per_page' => 3,
    );
    query_posts($args);
    if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="mv_post" style="background-image:url(<?php echo wp_get_attachment_url(get_post_thumbnail_id()); ?>);">
		<a class="mv_post_link hover_filter" href="<?php echo get_permalink(); ?>">
		</a>
		<div class="mv_post_inner">
			<?php
            $posttags = get_the_tags();
            $posttime = get_the_date("Y.m.d");
            if ($posttags) {
                echo '<ul class="mv_post_inner_tags">';
                foreach ($posttags as $tag) {
                    echo '<li class="mv_post_inner_tags_child tag_type1">' . $tag->name . '</li>';
                }
                echo '<time class="mv_post_inner_date">' . $posttime . '</time>';
                echo '</ul>';
            }
            ?>

			<h2 class="mv_post_inner_ttl"><?php the_title() ?></h2>
		</div>
	</div>
	<?php endwhile;
    endif; ?>
</div>
 end mv  -->
<!-- content メインコンテンツ
<div class="sidebarflex inner">
	<div class="postWrap">
		<h3 class="heading_type1" style="width:100%; align-self:center;">新着情報</h3>
		<div class="postWrap_inner">
			<?php
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
            $args2 = array(

                'post_type' => 'post',
                'paged' => $paged,
                'order' => 'DESC',
                'posts_per_page' => 10,
            );

            $the_query = new WP_Query($args2);
            if ($the_query->have_posts()) :
                while ($the_query->have_posts()) : $the_query->the_post(); ?>
			<a class="postWrap_inner_child" href="<?php echo get_permalink(); ?>">
				<figure class="postWrap_inner_child_thum" style="background-image:url(<?php echo wp_get_attachment_url(get_post_thumbnail_id()); ?>)"></figure>
				<article class="postWrap_inner_child_desc">
					<ul class="postWrap_inner_child_desc_tags">
						<li>
							<?php
                            $posttags1 = get_the_tags();
                            if ($posttags1) {
                                echo '<ul class="postWrap_inner_child_tags_children">';
                                foreach ($posttags1 as $tag) {
                                    echo '<li class="tag_type2">' . $tag->name . '</li>';
                                }
                                echo '</ul>';
                            }
                            ?>
						</li>
						<li>
							<time class="postWrap_inner_child_desc_time">
								<?php echo get_the_date('Y.m.d'); ?>
							</time>
						</li>
					</ul>
					<h4 class="postWrap_inner_child_desc_ttl"><?php the_title() ?></h4>
				</article>
			</a>
			<?php endwhile;
            endif; ?>
		</div>

		<div class="my_pagination">
			<?php echo paginate_links(array(
                'type' => 'list',
                'mid_size' => '1',
                'prev_text' => '←',
                'next_text' => '→'
            )); ?>
		</div>
	</div>


	<?php get_sidebar(); ?>

</div>
 end content メインコンテンツ  -->

<?php get_footer();
