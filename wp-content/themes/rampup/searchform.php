<?php

/**
 * Template for displaying search forms
 * @package WordPress
 * @subpackage I'LL
 * @since I'LL 1.8
 */
?>

<?php $unique_id = esc_attr(uniqid('search-form-')); ?>

<form class="search_form" role="search" method="GET" class="search-form" action="<?php echo home_url("/"); ?>">
       <label for="<?php echo $unique_id; ?>">
       </label>
       <input type="text" id="<?php echo $unique_id; ?>" class="search-field" value="<?php echo get_search_query(); ?>" name="s" placeholder="検索" />
       <button type="submit" class="search-submit"><i class="fal fa-search"></i></button>
</form>
