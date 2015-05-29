# wp_get_adjacent_custompost

This plugin provides navigation for custom post types.

It is based upon the work of "enchance" (http://stackoverflow.com/users/693642/enchance), found on stackoverflow.com: http://stackoverflow.com/questions/10376891/make-get-adjacent-post-work-across-custom-post-types

It supports only the custom post types you identify and does not look at categories anymore. This allows you to go from one custom post type to another which was not possible with the default get_adjacent_post().

To use it, enable the plugin and add this to your templates:

<?php
$prev = get_adjacent_custompost('prev', array('custompost_type'));
$next = get_adjacent_custompost('next', array('custompost_type'));
?>

<?php if($prev) : ?>
    <a href="<?php echo get_permalink($prev->ID)?>">&laquo; Previous</a>
<?php endif; ?>

<?php if($next) : ?>
    <a href="<?php echo get_permalink($next->ID)?>">Next &raquo;</a>
<?php endif; ?>
