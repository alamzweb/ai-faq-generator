<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AIFG_Admin {
	public function add_plugin_admin_menu() {
		add_menu_page(
			'AI FAQ Generator',
			'AI FAQ Generator',
			'manage_options',
			'aifg-dashboard',
			array( $this, 'render_dashboard_page' ),
			'dashicons-welcome-learn-more',
			30
		);

		add_submenu_page(
			'aifg-dashboard',
			'Dashboard',
			'Dashboard',
			'manage_options',
			'aifg-dashboard',
			array( $this, 'render_dashboard_page' )
		);

		add_submenu_page(
			'aifg-dashboard',
			'Generate FAQs',
			'Generate FAQs',
			'manage_options',
			'aifg-generate',
			array( $this, 'render_generate_page' )
		);

		add_submenu_page(
			'aifg-dashboard',
			'All FAQ Sets',
			'All FAQ Sets',
			'edit.php?post_type=aifg_faq_set'
		);

		add_submenu_page(
			'aifg-dashboard',
			'Settings',
			'Settings',
			'manage_options',
			'aifg-settings',
			array( $this, 'render_settings_page' )
		);
	}

	public function enqueue_admin_assets( $hook ) {
		if ( strpos( $hook, 'aifg' ) === false && strpos( $hook, 'edit.php' ) === false && strpos( $hook, 'post.php' ) === false ) {
			return;
		}
		wp_enqueue_style( 'aifg-admin-css', AIFG_PLUGIN_URL . 'assets/css/admin.css', array(), AIFG_VERSION );
		wp_enqueue_script( 'aifg-admin-js', AIFG_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ), AIFG_VERSION, true );
		
		wp_localize_script( 'aifg-admin-js', 'aifg_vars', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'aifg_admin_nonce' )
		));
	}

	public function render_dashboard_page() {
		global $wpdb;
		
		// Query stats cleanly using direct metadata counters and WP_Query wrappers.
		$count_sets_query = wp_count_posts( 'aifg_faq_set' );
		$total_sets = isset( $count_sets_query->publish ) ? $count_sets_query->publish : 0;
		
		// Parse internal total generated questions using safe prepared aggregation.
		$meta_query = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_aifg_faq_data'" );
		$total_questions = 0;
		
		$all_meta = $wpdb->get_col( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_aifg_faq_data'" );
		foreach ( $all_meta as $meta_string ) {
			$arr = maybe_unserialize( $meta_string );
			if ( is_array( $arr ) ) {
				$total_questions += count( $arr );
			}
		}

		$settings = get_option( 'aifg_settings', array() );
		$schema_status = ( ! empty( $settings['enable_schema'] ) && '1' === $settings['enable_schema'] ) ? 'Enabled' : 'Disabled';
		$assets_status = ( ! empty( $settings['load_assets_globally'] ) && '1' === $settings['load_assets_globally'] ) ? 'Enabled' : 'Disabled';

		?>
		<div class="wrap aifg-dashboard-wrap">
			<div class="aifg-header">
				<h1>AI FAQ Generator <span class="badge">v1.0.0</span></h1>
				<p class="subtitle">Analyze site content locally and maintain beautiful search optimized layout components instantly.</p>
			</div>
			<hr class="wp-header-end">
			
			<div class="aifg-stats-grid">
				<div class="aifg-card stat-card">
					<div class="stat-icon dashicons dashicons-portfolio"></div>
					<div class="stat-content">
						<h3>FAQ Sets</h3>
						<p class="stat-number"><?php echo esc_html( $total_sets ); ?></p>
					</div>
				</div>
				<div class="aifg-card stat-card">
					<div class="stat-icon dashicons dashicons-editor-help"></div>
					<div class="stat-content">
						<h3>Questions Generated</h3>
						<p class="stat-number"><?php echo esc_html( $total_questions ); ?></p>
					</div>
				</div>
				<div class="aifg-card stat-card">
					<div class="stat-icon dashicons dashicons-google"></div>
					<div class="stat-content">
						<h3>Schema Status</h3>
						<p class="stat-number <?php echo $schema_status === 'Enabled' ? 'active' : 'inactive'; ?>"><?php echo esc_html( $schema_status ); ?></p>
					</div>
				</div>
				<div class="aifg-card stat-card">
					<div class="stat-icon dashicons dashicons-admin-network"></div>
					<div class="stat-content">
						<h3>Global Assets</h3>
						<p class="stat-number"><?php echo esc_html( $assets_status ); ?></p>
					</div>
				</div>
			</div>

			<div class="aifg-dashboard-main">
				<div class="aifg-card content-card">
					<h2>Getting Started with AI FAQ Generator</h2>
					<p>Generate highly responsive FAQ markup nodes completely offline using your standard historical page configurations, titles, headings, and lists.</p>
					<div class="action-buttons">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=aifg-generate' ) ); ?>" class="button button-primary premium-btn">Generate New FAQs</a>
						<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=aifg_faq_set' ) ); ?>" class="button button-secondary">View Saved FAQ Sets</a>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_generate_page() {
		// Get content targets for selection drop-downs.
		$posts = get_posts( array( 'numberposts' => -1, 'post_type' => array( 'post', 'page' ) ) );
		?>
		<div class="wrap aifg-dashboard-wrap">
			<div class="aifg-header">
				<h1>Generate FAQs</h1>
				<p class="subtitle">Extract layout headings and convert local paragraph trees into structured schema segments.</p>
			</div>
			
			<div class="aifg-workplace-grid">
				<div class="aifg-card panel-controls">
					<h3>Content Configuration Engine</h3>
					<div class="form-group">
						<label for="aifg_source_post">Select Target Source Post or Page</label>
						<select id="aifg_source_post" class="widefat">
							<option value="">-- Choose Target File Node --</option>
							<?php foreach ( $posts as $post ) : ?>
								<option value="<?php echo esc_attr( $post->ID ); ?>"><?php echo esc_html( $post->post_title ); ?> (<?php echo esc_html( $post->post_type ); ?>)</option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="aifg_set_title">New FAQ Set Reference Title</label>
						<input type="text" id="aifg_set_title" class="widefat" placeholder="e.g. Speed Optimization FAQ">
					</div>
					<button type="button" id="aifg_trigger_generation" class="button button-primary premium-btn widefat-btn">Generate FAQs</button>
				</div>
				
				<div class="aifg-card panel-preview">
					<div class="preview-header-bar">
						<h3>Interactive Structural Live Preview</h3>
						<span class="schema-badge error" id="schema_badge_indicator">⚠ Missing Questions</span>
					</div>
					<div class="preview-empty-state" id="preview_empty_state_container">
						<span class="dashicons dashicons-visibility"></span>
						<p>Select your content source file and click generate to process local smart heuristic extraction arrays.</p>
					</div>
					<div id="preview_interactive_container" class="preview-interactive-container hidden"></div>
					<div id="save_actions_container" class="save-actions-container hidden">
						<hr />
						<button type="button" id="aifg_save_generated_set" class="button button-primary premium-btn">Save Changes</button>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_settings_page() {
		?>
		<div class="wrap aifg-dashboard-wrap">
			<div class="aifg-header">
				<h1>Global Configuration Engine</h1>
				<p class="subtitle">Maintain complete privacy and toggle schema parameters safely.</p>
			</div>
			
			<div class="aifg-card settings-form-card">
				<form method="post" action="options.php">
					<?php
					settings_fields( 'aifg_settings_group' );
					$options = get_option( 'aifg_settings', array(
						'enable_schema'       => '0',
						'enable_faq_styles'   => '0',
						'load_assets_globally'=> '0',
						'auto_insert'         => '0',
						'tracking_features'   => '0',
					));
					?>
					
					<div class="setting-toggle-row">
						<div class="setting-label-block">
							<label class="toggle-title">Enable Schema Generation</label>
							<p class="description">Inject programmatic fallback inline valid JSON-LD structured meta arrays inside front-end rendering engines automatically.</p>
						</div>
						<div class="setting-input-block">
							<input type="checkbox" name="aifg_settings[enable_schema]" value="1" <?php checked( '1', isset( $options['enable_schema'] ) ? $options['enable_schema'] : '0' ); ?> />
						</div>
					</div>
					
					<div class="setting-toggle-row">
						<div class="setting-label-block">
							<label class="toggle-title">Enable Frontend CSS Styling</label>
							<p class="description">Activate custom micro-layouts, variable transformations, accordions, dynamic cards, and typography patterns.</p>
						</div>
						<div class="setting-input-block">
							<input type="checkbox" name="aifg_settings[enable_faq_styles]" value="1" <?php checked( '1', isset( $options['enable_faq_styles'] ) ? $options['enable_faq_styles'] : '0' ); ?> />
						</div>
					</div>

					<div class="setting-toggle-row">
						<div class="setting-label-block">
							<label class="toggle-title">Load Assets Globally</label>
							<p class="description">Forces CSS asset script injection files globally across full site structures instead of local isolated dynamic execution blocks.</p>
						</div>
						<div class="setting-input-block">
							<input type="checkbox" name="aifg_settings[load_assets_globally]" value="1" <?php checked( '1', isset( $options['load_assets_globally'] ) ? $options['load_assets_globally'] : '0' ); ?> />
						</div>
					</div>

					<div class="setting-toggle-row">
						<div class="setting-label-block">
							<label class="toggle-title">Auto Insert Layout Nodes</label>
							<p class="description">Automatically appends generated structural pairs below single target global pages when mapping relationships are discovered.</p>
						</div>
						<div class="setting-input-block">
							<input type="checkbox" name="aifg_settings[auto_insert]" value="1" <?php checked( '1', isset( $options['auto_insert'] ) ? $options['auto_insert'] : '0' ); ?> />
						</div>
					</div>

					<div class="setting-toggle-row">
						<div class="setting-label-block">
							<label class="toggle-title">Tracking Features</label>
							<p class="description">Allow local processing engines to measure dynamic user clicks inside accordion cards for dashboard reporting metrics.</p>
						</div>
						<div class="setting-input-block">
							<input type="checkbox" name="aifg_settings[tracking_features]" value="1" <?php checked( '1', isset( $options['tracking_features'] ) ? $options['tracking_features'] : '0' ); ?> />
						</div>
					</div>
					
					<div class="settings-footer-actions">
						<?php submit_button( 'Save Changes', 'button button-primary premium-btn', 'submit', false ); ?>
					</div>
				</form>
			</div>
		</div>
		<?php
	}
}