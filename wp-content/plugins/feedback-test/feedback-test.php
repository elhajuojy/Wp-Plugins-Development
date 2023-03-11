<?php 
/**
 * Plugin Name: Feedback Test
 * Plugin URI: http://www.example.com
 * Description: This is a test plugin for feedback
 * Version: 1.0
 * Author: ELMahdi elhjuojy
 * Author URI: http://www.example.com
 * License: GPL2
*/


// Define the feedback form shortcode
function feedback_form_shortcode() {
    ob_start();
    ?>
    <form method="post" action="">
        <label for="note">Note (obligatoire):</label>
        <input type="number" name="note" min="0" max="5" required>
        <br><br>
        <label for="remarque">Remarque (obligatoire):</label>
        <textarea name="remarque" rows="5" required></textarea>
        <br><br>
        <label for="post_id">ID de post ou de page (obligatoire):</label>
        <input type="text" name="post_id" required>
        <br><br>
        <input type="submit" name="submit_feedback" value="Envoyer">
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode( 'feedback_form', 'feedback_form_shortcode' );

// Save feedback data to the database
function save_feedback() {
    if ( isset( $_POST['submit_feedback'] ) ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'feedback_data';
        $note = sanitize_text_field( $_POST['note'] );
        $remarque = sanitize_textarea_field( $_POST['remarque'] );
        $post_id = sanitize_text_field( $_POST['post_id'] );
        $wpdb->insert(
            $table_name,
            array(
                'note' => $note,
                'remarque' => $remarque,
                'post_id' => $post_id
            ),
            array(
                '%d',
                '%s',
                '%s'
            )
        );
    }
}
add_action( 'init', 'save_feedback' );

// Create the feedback data table in the database on plugin activation
function create_feedback_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'feedback_data';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id int(11) NOT NULL AUTO_INCREMENT,
        note int(1) NOT NULL,
        remarque text NOT NULL,
        post_id varchar(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'create_feedback_table' );

// Add an admin menu item for the feedback data
function feedback_menu_item() {
    add_menu_page(
        'Feedback Data',
        'Feedback Data',
        'manage_options',
        'feedback-data',
        'feedback_data_page'
    );
}
add_action( 'admin_menu', 'feedback_menu_item' );

// Create the feedback data page in the admin panel
function feedback_data_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'feedback_data';
    $feedback_data = $wpdb->get_results( "SELECT * FROM $table_name" );
    ?>
    <div class="wrap">
        <h1>Feedback Data</h1>
        <table class="widefat">
            <thead>
                <tr>
                    <th>ID</th>
                    <th  >    Note</th>
                    <th>Remarque</th>
                    <th>Post ID</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $feedback_data as $feedback ) : ?>
                <tr>
                    <td><?php echo $feedback->id; ?></td>
                    <td><?php echo $feedback->note; ?></td>
                    <td><?php echo $feedback->remarque; ?></td>
                    <td><?php echo $feedback->post_id; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
}