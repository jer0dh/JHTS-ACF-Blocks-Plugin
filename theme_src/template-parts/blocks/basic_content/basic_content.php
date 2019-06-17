<?php
/**
 * Basic Content Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

$blockname = 'basic-content';

$content                = get_field( 'content', false );
$is_centered_vertically = get_field( 'is_centered_vertically' );
$min_height             = get_field( 'min_height' );
$middle_wrap            = get_field( 'middle_wrap' );
$inner_wrap             = get_field( 'inner_wrap' );
$background_color       = get_field( 'background_color' );


$background = '';
if ( $background_color && $background_color != 'none' ) {
	$background .= ' background--' . esc_attr( $background_color );
}


$padding = mr_blocks_section_padding();
$margin  = mr_blocks_section_margin();


//Other
// Create class attribute allowing for custom "className" and "align" values.
$className = 'section-' . $blockname . ' not-loaded lazy ' . $margin . ' ' . $background;

if ( ! empty( $block['className'] ) ) {
	$className .= ' ' . $block['className'];
}

if ( ! empty( $block['align'] ) ) {
	$className .= ' align' . $block['align'];
}

// Create id attribute allowing for custom "anchor" value.
$id = $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$id = $block['anchor'];
}



$attributes = '';
if ( $id ) {
	$attributes .= ' id="' . esc_attr( $id ) . '" ';
}

$attributes .= ' class="' . esc_attr( $className ) . ' ' . esc_attr( $margin ) . '"';

?>

    <section <?php echo $attributes; ?>>
        <div class="container <?php echo ( $inner_wrap ) ? 'container--inner' : ''; ?> <?php echo ( $middle_wrap ) ? 'container--middle' : ''; ?> ">
            <div class="row <?php echo esc_attr( $padding ) . ( ( $is_centered_vertically ) ? ' align-items-center' : '' ); ?>"
				<?php echo ( $min_height ) ? 'style="min-height:' . esc_attr( $min_height ) . '"' : ''; ?>>
                <div class="col-12 <?php echo ( $is_centered_vertically ) ? 'rm-last-element-mb' : ''; ?>">
					<?php echo do_shortcode( wpautop( wp_kses_post( $content ) ) ); ?>
                </div>
            </div>
        </div>
    </section>

<?php

