<?php if ( ! defined( 'ABSPATH' ) ) exit( 'No direct script access allowed' );

/**
 * The template for displaying customizer search
 *
 * @version 0.1
 * @since 0.1
 */
?>

<script type="text/html" id="et_search-form">
    <div class="et_customize-search">
        <div class="et_search-wrapper empty">
            <input type="text"
                placeholder="<?php _e( 'Search for options', 'xstore' ); ?>"
                name="et_customizer-search"
                autofocus="autofocus"
                id="et_customizer-search"
                class="et_customizer-search-input">
                <span class="et_clear-search"><span class="dashicons dashicons-no-alt"></span></span>
        </div>
        <ul id="et_customizer-search-results" 
            data-length="<?php esc_html_e( 'Please, enter at least 3 symbols.', 'xstore' ); ?>"
            data-empty="<?php esc_html_e( 'No results were found.', 'xstore' ); ?>"></ul>
    </div>
</script>