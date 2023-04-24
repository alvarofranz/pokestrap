<?php
/**
 * The template for displaying Pokemon single posts
 *
 * @package pokestrap
 */


function my_theme_enqueue_scripts() {
	// Enqueue your JavaScript file.
	wp_enqueue_script( 'retrieve-old-index', get_stylesheet_directory_uri() . '/js/retrieve-pokemon-old-index.js', array(), '1.0.0', true );

	// Define the ajaxurl variable for your script.
	wp_localize_script( 'retrieve-old-index', 'script_data', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_scripts' );

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

	<div class="wrapper" id="single-wrapper">

		<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

			<div class="row">

				<?php
				// Do the left sidebar check and open div#primary.
				get_template_part( 'global-templates/left-sidebar-check' );
				?>

				<main class="site-main" id="main">
					<article class="pokemon-single-post" id="post-<?php the_ID(); ?>">

						<?php
						while ( have_posts() ) {
							the_post();
							$pokemon_weight    = get_post_meta( get_the_ID(), 'pokemon_weight', true );
							$pokemon_index_new = get_post_meta( get_the_ID(), 'pokemon_index_new', true );
							$pokemon_types     = get_the_terms( get_the_ID(), 'type' );
							$pokemon_attacks   = get_the_terms( get_the_ID(), 'attack' );

							echo '
						<h1>' . esc_html( get_the_title() ) . '</h1>
						<div class="image-and-description">
							<img src="' . esc_attr( get_the_post_thumbnail_url() ) . '" alt="' . esc_attr( get_the_title() ) . '">
							<div>' . wp_kses( get_the_content(), array( 'p' => array() ) ) . '</div>
						</div>
						<p>Weight: ' . esc_html( $pokemon_weight ) . '</p>
						<p>New Index: ' . esc_html( $pokemon_index_new ) . '</p>
						<div id="display-old-index"><button id="get-old-index" data-post-id="' . get_the_ID() . '">Get Old Index</button></div>';

							// Display types.
							if ( $pokemon_types ) {
								echo '<div class="pokemon-types">';
								// Loop through the types.
								foreach ( $pokemon_types as $pokemon_type ) {
									// Get the type's name.
									$pokemon_type_name = $pokemon_type->name;
									// Get the type's slug.
									$pokemon_type_slug = $pokemon_type->slug;
									// Get the type's link.
									$pokemon_type_link = get_term_link( $pokemon_type_slug, 'type' );
									// Output the type's link.
									echo '<a href="' . esc_attr( $pokemon_type_link ) . '">' . esc_html( $pokemon_type_name ) . '</a>';
								}
								echo '</div>';
							}

							// Display attacks.
							if ( $pokemon_attacks ) {
								?>
								<table class="pokemon-attacks">
									<thead>
									<tr>
										<th>Attack Name</th>
										<th>Description</th>
									</tr>
									</thead>
									<tbody>
									<?php
									// Loop through the attacks.
									foreach ( $pokemon_attacks as $pokemon_attack ) {
										// Get the attack's name.
										$pokemon_attack_name = $pokemon_attack->name;
										// Get the attack's slug.
										$pokemon_attack_slug = $pokemon_attack->slug;
										// Get the attack's link.
										$pokemon_attack_link = get_term_link( $pokemon_attack_slug, 'attack' );
										// Get the attack's description.
										$pokemon_attack_description = $pokemon_attack->description;
										// Output the attack's link and description.
										echo '<tr>';
										echo '<td><a href="' . esc_attr( $pokemon_attack_link ) . '">' . esc_html( $pokemon_attack_name ) . '</a></td>';
										echo '<td>' . esc_html( $pokemon_attack_description ) . '</td>';
										echo '</tr>';
									}
									?>
									</tbody>
								</table>
								<?php
							}
						}
						?>
					</article>
				</main>

				<?php
				// Do the right sidebar check and close div#primary.
				get_template_part( 'global-templates/right-sidebar-check' );
				?>

			</div><!-- .row -->

		</div><!-- #content -->

	</div><!-- #single-wrapper -->

<?php
get_footer();
