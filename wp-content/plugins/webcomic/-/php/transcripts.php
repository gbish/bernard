<?php
/** Contains the WebcomicTranscripts class.
 * 
 * @package Webcomic
 */

/** Handle transcript-related tasks.
 * 
 * @package Webcomic
 */
class WebcomicTranscripts extends Webcomic {
	/** Register hooks.
	 * 
	 * @uses WebcomicTranscripts::delete_post()
	 * @uses WebcomicTranscripts::admin_footer()
	 * @uses WebcomicTranscripts::add_meta_boxes()
	 * @uses WebcomicTranscripts::post_updated()
	 * @uses WebcomicTranscripts::restrict_manage_posts()
	 * @uses WebcomicTranscripts::admin_enqueue_scripts()
	 * @uses WebcomicTranscripts::wp_insert_post_data()
	 * @uses WebcomicTranscripts::manage_custom_column()
	 * @uses WebcomicTranscripts::request()
	 * @uses WebcomicTranscripts::posts_where()
	 * @uses WebcomicTranscripts::view_orphans()
	 * @uses WebcomicTranscripts::manage_columns()
	 * @uses WebcomicTranscripts::manage_language_columns()
	 * @uses WebcomicTranscripts::manage_sortable_columns()
	 */
	public function __construct() {
		add_action( 'delete_post', array( $this, 'delete_post' ) );
		add_action( 'admin_footer', array( $this, 'admin_footer' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'post_updated', array( $this, 'post_updated' ), 10, 3 );
		add_action( 'restrict_manage_posts', array( $this, 'restrict_manage_posts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'wp_insert_post', array( $this, 'save_webcomic_authors' ), 10, 2 );
		add_action( 'wp_insert_post_data', array( $this, 'wp_insert_post_data' ), 10, 2 );
		add_action( 'manage_webcomic_transcript_posts_custom_column', array( $this, 'manage_custom_column' ), 10, 2 );
		
		add_filter( 'request', array( $this, 'request' ), 10, 1 );
		add_filter( 'posts_where', array( $this, 'posts_where' ), 10, 1 );
		add_filter( 'views_edit-webcomic_transcript', array( $this, 'view_orphans' ), 10, 1 );
		add_filter( 'manage_edit-webcomic_transcript_columns', array( $this, 'manage_columns' ), 10, 1 );
		add_filter( 'manage_edit-webcomic_language_columns', array( $this, 'manage_language_columns' ), 10, 3 );
		add_filter( 'manage_edit-webcomic_transcript_sortable_columns', array( $this, 'manage_sortable_columns' ), 10, 1 );
	}
	
	/** Remove parent from transcripts.
	 * 
	 * @param integer $id The deleted post ID.
	 * @uses Webcomic::$config
	 * @hook delete_post
	 */
	public function delete_post( $id ) {
		global $wpdb;
		
		if ( isset( self::$config[ 'collections' ][ get_post_type( $id ) ] ) ) {
			$wpdb->update( $wpdb->posts, array( 'post_parent' => 0 ), array( 'post_type' => 'webcomic_transcript', 'post_parent' => $id ) );
		}
	}
	
	/** Render javascript for the webcomic meta boxes.
	 * 
	 * @hook admin_footer
	 */
	public function admin_footer() {
		$screen = get_current_screen();
		
		if ( 'webcomic_transcript' === $screen->id ) {
			printf( "<script>webcomic_transcript_meta('%s');</script>", admin_url() );
		}
	}
	
	/** Add transcript meta boxes.
	 * 
	 * @uses WebcomicTranscripts::parent()
	 * @hook add_meta_boxes
	 */
	public function add_meta_boxes() {
		add_meta_box( 'webcomic-parent', __( 'Parent Webcomic', 'webcomic' ), array( $this, 'parent' ), 'webcomic_transcript', 'normal', 'high' );
		add_meta_box( 'webcomic-authors', __( 'Transcript Authors', 'webcomic' ), array( $this, 'authors' ), 'webcomic_transcript', 'normal', 'high' );
	}
	
	/** Update transcripts when their parent webcomics are updated.
	 * 
	 * Updates the transcript post type and languages when a parent
	 * webcomic is converted, and the transcript name when the parent
	 * webcomic is renamed.
	 * 
	 * @param integer $id The post ID.
	 * @param object $after The updated post object.
	 * @param object $before The post object prior to update.
	 * @uses Webcomic::$config
	 * @hook post_updated
	 */
	public function post_updated( $id, $after, $before ) {
		global $wpdb;
		
		if ( isset( self::$config[ 'collections' ][ $before->post_type ] ) ) {
			if ( 'post' === $after->post_type) {
				$transcripts = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'webcomic_transcript' AND post_parent = %d", $id ) );
				
				$wpdb->update( $wpdb->posts, array( 'post_type' => 'post', 'post_parent' => 0 ), array( 'post_type' => 'webcomic_transcript', 'post_parent' => $id ) );
				
				foreach ( $transcripts as $transcript ) {
					$tags = array();
					
					foreach ( wp_get_object_terms( $transcript, 'webcomic_language' ) as $language ) {
						if ( term_exists( ( integer ) $language->term_id, 'post_tag' ) ) {
							$tags[] = ( integer ) $language->term_id;
						} else {
							$tag    = wp_insert_term( $language->name, 'post_tag' );
							$tags[] = ( integer ) $tag[ 'term_id' ];
						}
					}
					
					wp_set_object_terms( $transcript, $tags, 'post_tag'  );
					wp_set_object_terms( $transcript, null, 'webcomic_language' );
				}
			} else if ( $before->post_title !== $after->post_title ) {
				$title = sprintf( __( '%s Transcript', 'webcomic' ), $after->post_title );
				
				foreach ( $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'webcomic_transcript' AND post_parent = %d", $id ) ) as $transcript ) {
					$wpdb->update( $wpdb->posts, array( 'post_title' => $title, 'post_name' => wp_unique_post_slug( sanitize_title( $title ), $transcript, get_post_status( $transcript ), 'webcomic_transcript', $id ) ), array( 'ID' => $transcript ) );
				}
			}
		}
	}
	
	/** Render additional post filtering options.
	 * 
	 * The wp_dropdown_categories() function works well enough, but we
	 * need to swap out the term ID values for term slug values to get
	 * it to work correctly with administrative pages.
	 * 
	 * @uses Webcomic::$config
	 * @hook restrict_manage_posts
	 */
	public function restrict_manage_posts() {
		global $post_type;
		
		if ( 'webcomic_transcript' === $post_type ) {
			$collection = isset( $_GET[ 'webcomic_collection' ] ) ? $_GET[ 'webcomic_collection' ] : '';
			
			printf( '<select name="webcomic_collection"><option value="">%s</option>', __( 'View all collections', 'webcomic' ) );
			
			foreach ( self::$config[ 'collections' ] as $k => $v ) {
				printf( '<option value="%s"%s>%s</option>', $k, selected( $k, $collection, false ), esc_html( $v[ 'name' ] ) );
			}
			
			echo '</select>';
			
			if ( isset( $_GET[ 'webcomic_orphaned' ] ) ) {
				echo '<input type="hidden" name="webcomic_orphaned" value="1">';
			}
		}
	}
	
	/** Register and enqueue meta box scripts.
	 * 
	 * @uses Webcomic::$url
	 * @hook admin_enqueue_scripts
	 */
	public function admin_enqueue_scripts() {
		$screen = get_current_screen();
		
		if ( 'webcomic_transcript' === $screen->id ) {
			wp_register_script( 'webcomic-admin-transcripts', self::$url . '-/js/admin-transcripts.js', array( 'jquery' ) );
			
			wp_enqueue_script( 'webcomic-admin-transcripts' );
		}
	}
	
	/** Update transcript authors when saving.
	 * 
	 * @param integer $id The page ID to update.
	 * @param object $post Post object to update.
	 * @hook wp_insert_post
	 */
	public function save_webcomic_authors( $id, $post ) {
		if (
			'webcomic_transcript' === $post->post_type
			and ( !defined( 'DOING_AUTOSAVE' ) or !DOING_AUTOSAVE )
			and isset( $_POST[ 'webcomic_meta_authors' ] )
			and wp_verify_nonce( $_POST[ 'webcomic_meta_authors' ], 'webcomic_meta_authors' )
			and current_user_can( 'edit_post', $id )
		) {
			if ( isset( $_POST[ 'webcomic_author' ] ) ) {
				foreach ( $_POST[ 'webcomic_author' ] as $author ) {
					$author   = array_map( 'stripslashes', $author );
					$original = unserialize( $author[ 'original' ] );
					
					$author[ 'time' ] = empty( $author[ 'date' ] ) ? get_the_time( 'U', $post->ID ) : strtotime( $author[ 'date' ] . ' ' . $author[ 'time' ] );
					
					unset( $author[ 'date' ], $author[ 'original' ] );
					
					if ( isset( $author[ 'delete' ] ) ) {
						delete_post_meta( $id, 'webcomic_author', $original );
					} else {
						update_post_meta( $id, 'webcomic_author', $author, $original );
					}
				}
			}
			
			if ( isset( $_POST[ 'webcomic_author_new' ] ) ) {
				foreach ( $_POST[ 'webcomic_author_new' ] as $author ) {
					if ( empty( $author[ 'name' ] ) ) {
						continue;
					} else {
						$author[ 'time' ] = empty( $author[ 'time' ] ) ? get_the_time( 'U', $post->ID ) : strtotime( $author[ 'time' ] );
						
						add_post_meta( $id, 'webcomic_author', $author );
					}
				}
			}
		}
	}
	
	/** Update transcript parent, title, and slug prior to saving.
	 * 
	 * @param array $data An array of post data.
	 * @param array $raw An array of raw post data.
	 * @return array
	 * @hook wp_insert_post_data
	 */
	public function wp_insert_post_data( $data, $raw ) {
		if (
			'webcomic_transcript' === $raw[ 'post_type' ]
			and !empty( $raw[ 'ID' ] )
			and current_user_can( 'edit_post', $raw[ 'ID' ] )
		) {
			if ( !empty( $_POST[ 'webcomic_post' ] ) and wp_verify_nonce( $_POST[ 'webcomic_meta_parent' ], 'webcomic_meta_parent' ) ) {
				$data[ 'post_parent' ] = $_POST[ 'webcomic_post' ];
				$data[ 'post_title' ]  = sprintf( __( '%s Transcript', 'webcomic' ), get_the_title( $_POST[ 'webcomic_post' ] ) );
				$data[ 'post_name' ]   = wp_unique_post_slug( sanitize_title( $data[ 'post_title' ] ), $raw[ 'ID' ], $raw[ 'post_status' ], $raw[ 'post_type' ], $raw[ 'post_parent' ] );
				
				update_post_meta( $_POST[ 'webcomic_post' ], 'webcomic_transcripts', isset( $_POST[ 'webcomic_transcripts' ] ) );
			} else {
				$data[ 'post_parent' ] = $raw[ 'post_parent' ];
				$data[ 'post_title' ]  = $raw[ 'post_parent' ] ? sprintf( __( '%s Transcript', 'webcomic' ), get_the_title( $raw[ 'post_parent' ] ) ) : __( 'Unattached Transcript', 'webcomic' );
				$data[ 'post_name' ]   = wp_unique_post_slug( sanitize_title( $data[ 'post_title' ] ), $raw[ 'ID' ], $raw[ 'post_status' ], $raw[ 'post_type' ], $raw[ 'post_parent' ] );
			}
		}
		
		return $data;
	}
	
	/** Render custom transcript columns.
	 * 
	 * @param string $column Name of the current column.
	 * @param integer $id Current post ID.
	 * @hook manage_webcomic_transcript_posts_custom_column
	 */
	public function manage_custom_column( $column, $id ) {
		global $post;
		
		if ( 'webcomic_author' === $column ) {
			$authors = array( sprintf( '<a href="%s">%s</a>',
				esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'author' => get_the_author_meta( 'ID' ) ), 'edit.php' ) ),
				get_the_author()
			) );
			
			foreach ( get_post_meta( $id, 'webcomic_author' ) as $author ) {
				$authors[] = esc_html( $author[ 'name' ] );
			}
			
			echo join( ', ', $authors );
		} else if ( 'webcomic_languages' === $column ) {
			if ( $languages = wp_get_object_terms( $id, 'webcomic_language' ) ) {
				$terms = array();
				
				foreach ( $languages as $language ) {
					$terms[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'webcomic_language' => $language->slug ), admin_url( 'edit.php' ) ) ),
						esc_html( sanitize_term_field( 'name', $language->name, $language->term_id, 'webcomic_language', 'display' ) )
					);
				}
				
				echo join( ', ', $terms );
			} else {
				_e( 'No Languages', 'webcomic' );
			}
		} else if ( 'webcomic_parent' === $column ) {
			echo $post->post_parent ? sprintf( '<strong><a href="%s">%s</a> - %s</strong>, %s',
				esc_url( add_query_arg( array( 'post_type' => get_post_type( $post->post_parent ) ), admin_url( 'edit.php' ) ) ),
				esc_html( get_post_type_object( get_post_type( $post->post_parent ) )->labels->name ),
				( current_user_can( 'edit_post', $post->post_parent ) and 'trash' !== get_post_status( $post->post_parent ) ) ? sprintf( '<a href="%s">%s</a>', get_edit_post_link( $post->post_parent ), esc_html( get_the_title( $post->post_parent ) ) ) : esc_html( get_the_title( $post->post_parent ) ),
				get_the_time( 'Y/m/d', $post->post_parent )
			) : __( 'No Webcomic', 'webcomic' );
		}
	}
	
	/** Handle sorting by webcomic parent.
	 * 
	 * @param array $request An array of request parameters.
	 * @return array
	 * @hook request
	 */
	public function request( $request ) {
		if ( isset( $request[ 'post_type' ], $request[ 'orderby' ] ) and 'webcomic_transcript' === $request[ 'post_type' ] and 'webcomic_parent' === $request[ 'orderby' ] ) {
			$request = array_merge( $request, array(
				'orderby' => 'parent'
			) );
		}
		
		return $request;
	}
	
	/** Handle additional post list filters.
	 * 
	 * Adds additional WHERE conditions to display transcripts without a
	 * parent (orphans) or belonging to a particular collection.
	 * 
	 * @param string $where The WHERE query string.
	 * @return string
	 * @hook posts_where
	 */
	public function posts_where( $where ) {
		global $wpdb;
		
		if ( isset( $_GET[ 'post_type' ] ) and 'webcomic_transcript' === $_GET[ 'post_type' ] ) {
			if ( isset( $_GET[ 'webcomic_orphaned' ] ) ) {
				$where .= ' AND post_parent = 0';
			}
			
			if ( !empty( $_GET[ 'webcomic_collection' ] ) ) {
				$where .= $wpdb->prepare( " AND post_parent IN ( SELECT ID FROM {$wpdb->posts} WHERE post_type = %s )", $_GET[ 'webcomic_collection' ] );
			}
		}
		
		return $where;
	}
	
	/** Add transcript languages, parent, and custom author columns.
	 * 
	 * @param array $columns An array of post columns.
	 * @return array
	 * @hook manage_edit-webcomic_transcript_columns
	 */
	public function manage_columns( $columns ) {
		unset( $columns[ 'author' ] );
		
		$pre = array_slice( $columns, 0, 2 );
		
		$pre[ 'webcomic_author' ]    = __( 'Author', 'webcomic' );
		$pre[ 'webcomic_languages' ] = __( 'Languages', 'webcomic' );
		$pre[ 'webcomic_parent' ]    = __( 'Webcomic', 'webcomic' );
		
		return array_merge( $pre, $columns );
	}
	
	/** Add sortable parent and author columns.
	 * 
	 * @param array $columns An array of sortable columns.
	 * @return array
	 * @hook manage_edit-webcomic_transcript_sortable_columns
	 */
	public function manage_sortable_columns( $columns ) {
		return array_merge( array( 'webcomic_author' => 'webcomic_author', 'webcomic_parent' => 'webcomic_parent' ), $columns );
	}
	
	/** Rename the language 'Posts' column.
	 * 
	 * @param array $columns An array of term columns.
	 * @return array
	 * @hook manage_edit-webcomic_language_columns
	 */
	public function manage_language_columns( $columns ) {
		$columns[ 'posts' ] = __( 'Transcripts', 'webcomic' );
		
		return $columns;
	}
	
	/** Add Orphaned view for webcomic posts.
	 * 
	 * @param array $views Array of view links.
	 * @return array
	 * @hook views_edit-(webcomic\d+)
	 */
	public function view_orphans( $views ) {
		global $wpdb;
		
		if ( $orphans = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'webcomic_transcript' AND post_parent = 0" ) ) {
			$posts = wp_count_posts( 'webcomic_transcript', 'readable' );
			
			foreach ( get_post_stati( array( 'show_in_admin_all_list' => false) ) as $state ) {
				$orphans -= $posts->$state;
			}
			
			if ( $orphans >= 1 ) {
				$views[ 'webcomic_orphans' ] = sprintf( '<a href="%s"%s>%s <span class="count">(%d)</span></a>',
					esc_url( add_query_arg( array( 'post_type' => 'webcomic_transcript', 'post_status' => 'all', 'webcomic_orphaned' => true ), admin_url( 'edit.php' ) ) ),
					isset( $_GET[ 'webcomic_orphaned' ] ) ? ' class="current"' : '',
					__( 'Orphaned', 'webcomic' ),
					$orphans
				);
			}
		}
		
		return $views;
	}
	
	/** Render the transcript parent meta box.
	 * 
	 * @param object $post Current post object.
	 * @uses Webcomic::$config
	 * @uses WebcomicTranscripts::ajax_posts()
	 * @uses WebcomicTranscripts::ajax_post_transcripts()
	 * @uses WebcomicTranscripts::ajax_preview()
	 */
	public function parent( $post ) {
		$parent_type = get_post_type( $post->post_parent );
		
		if ( 'webcomic_transcript' === $parent_type ) {
			$parent_type = 'webcomic1';
		}
		
		wp_nonce_field( 'webcomic_meta_parent', 'webcomic_meta_parent' );
		?>
		<style>#webcomic_post_preview{overflow:auto}</style>
		<p>
			<select name="webcomic_collection" id="webcomic_collection" disabled>
				<optgroup label="<?php esc_attr_e( 'Collection', 'webcomic' ); ?>">
				<?php
					foreach ( self::$config[ 'collections' ] as $k => $v ) {
						printf( '<option value="%s"%s>%s</option>',
							$k,
							selected( $k, $parent_type, false ),
							esc_html( $v[ 'name' ] )
						);
					}
				?>
				</optgroup>
			</select>
			<input type="hidden" name="webcomic_parent" id="webcomic_parent" value="<?php echo $post->post_parent; ?>">
			<span id="webcomic_post_list"><?php self::ajax_posts( $parent_type, $post->post_parent ); ?></span>
			<span id="webcomic_post_transcripts"><?php self::ajax_post_transcripts( $post->post_parent ); ?></span>
		</p>
		<div id="webcomic_post_preview"><?php self::ajax_preview( $post->post_parent ); ?></div>
		<?php
	}
	
	/** Render the transcript author meta box.
	 * 
	 * @param object $post Current post object.
	 * @uses Webcomic::$config
	 */
	public function authors( $post ) {
		$count   = 0;
		$authors = get_post_meta( $post->ID, 'webcomic_author' );
		
		wp_nonce_field( 'webcomic_meta_authors', 'webcomic_meta_authors' );
		?>
		<p class="description"><?php _e( 'Checked authors will be deleted when the transcript is updated.', 'webcomic' ); ?></p>
		<div style="margin:0 -11px">
			<table id="webcomic_author_table" class="widefat fixed">
				<thead>
					<tr>
						<th class="check-column"><input type="checkbox"></th>
						<th><?php _e( 'Name', 'webcomic' ); ?></th>
						<th><?php _e( 'Email', 'webcomic' ); ?></th>
						<th><?php _e( 'URL', 'webcomic' ); ?></th>
						<th><?php _e( 'IP', 'webcomic' ); ?></th>
						<th><?php _e( 'Date', 'webcomic' ); ?></th>
					</tr>
				</thead>
				<tbody>
		<?php
		foreach ( $authors as $author ) {
			printf( '
				<tr>
					<th class="check-column">
						<input type="checkbox" name="webcomic_author[' . $count . '][delete]">
						<input type="hidden" name="webcomic_author[' . $count . '][original]" value="%s">
					</th>
					<td><input type="text" name="webcomic_author[' . $count . '][name]" value="%s"></td>
					<td><input type="email" name="webcomic_author[' . $count . '][email]" value="%s"></td>
					<td><input type="url" name="webcomic_author[' . $count . '][url]" value="%s"></td>
					<td><input type="text" name="webcomic_author[' . $count . '][ip]" value="%s"></td>
					<td>
						<input type="text" name="webcomic_author[' . $count . '][date]" value="%s">
						<input type="hidden" name="webcomic_author[' . $count . '][time]" value="%s">
					</td>
				</tr>',
				esc_attr( serialize( $author ) ),
				$author[ 'name' ],
				$author[ 'email' ],
				$author[ 'url' ],
				$author[ 'ip' ],
				date( get_option( 'date_format' ), ( integer ) $author[ 'time' ] ),
				date( 'H:i:s', ( integer ) $author[ 'time' ] )
			);
			
			$count++;
		}
		?>
				</tbody>
			</table>
		</div>
		<p style="text-align:right"><?php submit_button( __( 'Add Author', 'webcomic' ), 'secondary', 'webcomic_author_add', false ); ?></p>
		<?php
	}
	
	/** Render a `<selec>` element for transcript parent posts.
	 * 
	 * @param string $collection The collection ID to retrive posts from.
	 * @param integer $parent The parent post of the current transcript.
	 */
	public static function ajax_posts( $collection, $parent = 0 ) {
		?>
		<select name="webcomic_post" id="webcomic_post">
			<option value=""><?php _e( '(no parent)', 'webcomic' ); ?></option>
			<?php
				foreach ( get_posts( array( 'numberposts' => -1, 'post_status' => get_post_stati( array( 'show_in_admin_all_list' => true ) ), 'post_type' => $collection ) ) as $p ) {
					printf( '<option value="%s"%s>%s</option>',
						$p->ID,
						selected( $p->ID, ( integer ) $parent, false ),
						esc_html( $p->post_title )
					);
				}
			?>
		</select>
		<?php
	}
	
	/** Render a checkbox for enabling or disabling parent post transcribing.
	 * 
	 * @param integer $parent The parent post of the current transcript.
	 */
	public static function ajax_post_transcripts( $parent = 0 ) {
		?>
		<label><input type="checkbox" name="webcomic_transcripts"<?php checked( get_post_meta( $parent, 'webcomic_transcripts', true ) ); disabled( !$parent ); ?>> <?php _e( 'Allow transcribing', 'webcomic' ); ?></label>
		<?php
	}
	
	/** Render preview images for transcript parent posts.
	 * 
	 * @param integer $post Thep arent post to retrieve images for.
	 * @uses Webcomic::get_attachments()
	 */
	public static function ajax_preview( $post ) {
		if ( $post and $attachments = self::get_attachments( $post ) ) {
			foreach ( $attachments as $attachment ) {
				echo wp_get_attachment_image( $attachment->ID, 'full' ), '<br>';
			}
		} else if ( $post ) {
			printf( '<p>%s</p>', __( 'No Attachments', 'webcomic' ) );
		}
	}
}