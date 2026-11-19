<?php
/**
 * Baggage claim and site links.
 *
 * @package Start
 */

$bags = array(
	'luggage-family-beach' => 'Beach-bound, ears packed',
	'suitcase-single-stickers' => 'Well-traveled',
	'backpack-cognac-leather' => 'Carry-on ready',
	'luggage-family-cruise' => 'Set sail',
	'suitcase-single-olive' => 'Fits in the overhead',
	'luggage-family-roadtrip' => 'Road trip loaded',
	'backpack-rolltop-tan' => 'Trail snacks inside',
	'suitcase-single-navy-fabric' => 'Cabin crew approved',
	'luggage-family-city' => 'City break',
	'luggage-family-roadtrip-2' => 'Scenic route, please',
);
?>
	</main>

	<footer class="site-footer travel-site-footer travel-night" data-stamps>
		<div class="travel-baggage">
			<div class="travel-inner travel-baggage__heading"><p class="travel-label" aria-hidden="true">Baggage claim <span>·</span> Carousel 04</p></div>
			<div class="travel-baggage__belt travel-ticker" data-marquee="35" aria-hidden="true"><div class="travel-ticker__track">
				<?php for ( $repeat = 0; $repeat < 2; ++$repeat ) : ?>
				<div class="travel-ticker__group">
					<?php foreach ( $bags as $item => $label ) : ?>
					<div class="travel-bag" data-tag="<?php echo esc_attr( $label ); ?>"><?php foursuitcases_travel_image( $item, array( 'data-cut' => '', 'data-tilt' => '8' ) ); ?></div>
					<?php endforeach; ?>
				</div>
				<?php endfor; ?>
			</div></div>
		</div>
		<div class="travel-inner travel-footer-content">
			<div class="travel-footer-heading" data-travel-protect><h2 class="travel-heading">Where to <span class="travel-script">next?</span></h2><?php foursuitcases_travel_compass( 'footer' ); ?></div>
			<div class="travel-footer-grid" data-travel-protect>
				<div><h3>Explore</h3>
					<?php if ( has_nav_menu( 'footer' ) ) {
						foursuitcases_extra_menu( 'footer', __( 'Explore', 'four-suitcases' ) );
					} else { foursuitcases_travel_footer_pages( array( 'disney', 'cruises', 'national-parks-road-trips', 'travel-journal' ) ); } ?>
				</div>
				<div><h3>Planning around your family</h3>
					<?php foursuitcases_travel_footer_pages( array( 'autistic-travel', 'food-allergy-gluten-free-travel', 'accessible-travel' ) ); ?>
				</div>
				<div><h3>Say hello</h3>
					<?php foursuitcases_travel_footer_pages( array( 'about-lindsay', 'plan-your-trip', 'contact' ) ); ?>
					<?php if ( foursuitcases_get_contact_info() ) : ?><div class="site-footer__contact"><?php foursuitcases_contact_info(); ?></div><?php endif; ?>
					<?php foursuitcases_extra_menu( 'social', __( 'Social navigation', 'four-suitcases' ) ); ?>
					<?php foursuitcases_social_links(); ?>
				</div>
			</div>
			<?php get_template_part( 'template-parts/travel/certifications', null, array( 'presentation' => 'footer' ) ); ?>
			<div class="travel-footer-bottom" data-travel-protect>
				<?php foursuitcases_extra_menu( 'legal', __( 'Legal navigation', 'four-suitcases' ) ); ?>
				<?php if ( get_theme_mod( 'start-site-search', true ) ) { get_search_form( array( 'aria_label' => __( 'Footer site search', 'four-suitcases' ) ) ); } ?>
				<p class="site-footer__copyright"><?php foursuitcases_copyright_text(); ?></p>
				<button type="button" data-motion-toggle hidden>Pause moving decorations</button>
				<div class="travel-hunt-status" data-hunt-status tabindex="-1" hidden><span data-hunt-count aria-live="polite"></span><button type="button" data-hunt-reset hidden>Reset hunt</button></div>
				<div class="travel-hunt-more">
					<?php foreach ( array( 'footer-left', 'footer-center', 'footer-right' ) as $spot ) { foursuitcases_travel_compass( $spot ); } ?>
				</div>
			</div>
		</div>
	</footer>
</div>

<?php foursuitcases_the_svg_sprite(); ?>
<?php wp_footer(); ?>
</body>
</html>
