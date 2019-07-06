<?php
namespace Lando\Cloud_City;

use \WP_CLI;
use \WP_CLI_Command;

/**
 * Use WP-CLI with Lando.
 */
class Command extends WP_CLI_Command {

    public static $developer_plugins = [
        'query-monitor',
        'wp-crontrol', 
        'user-switching', 
        'fakerpress',
        'duplicate-post',
        'transients-manager',
        'user-role-editor'
    ];
    
    public function content( $args, $assoc_args ) {
        $this->content_import();
    }
    
    /**
     * Check dat status.
     *
     * @param [type] $args
     * @param [type] $assoc_args
     * @return void
     */
    public function install( $args, $assoc_args ) {
        $url    = '';
        $title  = 'Site';

        if ( 
            ! empty( $args[0] )
            || ! empty( WP_CLI\Utils\get_flag_value( $assoc_args, 'url' ) )
        ) {
            $url = ! empty( $args[0] ) ? $args[0] : WP_CLI\Utils\get_flag_value( $assoc_args, 'url' );
            if ( false === stripos( $url, 'lndo.site' ) ) {
                $url = 'https://' . $url . '.lndo.site/';
            }
        } else {
            WP_CLI::error( 'Site URL is required. Try --url="slug" or --url="https://{slug}.lndo.site/' );
        }

        

        if ( 
            ( isset( $args[1] ) && ! empty( $args[1] ) )
            || ! empty( WP_CLI\Utils\get_flag_value( $assoc_args, 'title' ) )
        ) {
            $title = ( isset( $args[1] ) && ! empty( $args[1] ) ) ? $args[1] : WP_CLI\Utils\get_flag_value( $assoc_args, 'title' );
        }
        
        $this->green_log( 'Proceed to Landing Pad 327...' );

        $this->handle_install( $url, $title );
    }

    protected function handle_install( $url, $title ) {
        WP_CLI::runcommand( 'cli update' );
        WP_CLI::runcommand( 'core download' );

        WP_CLI::runcommand( 'config create --dbname=wordpress --dbuser=wordpress --dbpass=wordpress --dbhost=database' );

        WP_CLI::runcommand( 
            implode( ' ', 
                [
                    'core',
                    'install',
                    '--url=' . $url,
                    '--title=' . $title,
                    '--admin_user=lando',
                    '--admin_password=lando',
                    '--admin_email=lando@cloud-city.org',
                    '--skip-email'
                ] 
            )
        );

        WP_CLI::runcommand( 'package install wp-cli/doctor-command' );
        WP_CLI::runcommand( 'package install aaemnnosttv/wp-cli-http-command' );
        WP_CLI::runcommand( 'package install markri/wp-sec' );
        WP_CLI::runcommand( 'package install runcommand/hook' );
        WP_CLI::runcommand( 'package install runcommand/query-debug' );
        WP_CLI::runcommand( 'package install trepmal/wp-revisions-cli' );
        WP_CLI::runcommand( 'package install wp-cli/restful' );

        WP_CLI::runcommand( 'site empty --yes' );
        WP_CLI::runcommand( 'widget reset --all' );

        WP_CLI::runcommand( 
            implode( ' ', 
                [
                    'user',
                    'update',
                    '1',
                    '--first_name=Lando',
                    '--last_name=User',
                    '--admin_color=midnight',
                    '--skip-email',
                ] 
            ) 
        );

        WP_CLI::runcommand( 'option update permalink_structure "/%postname%/%post_id%/"' );
        WP_CLI::runcommand( 'eval "flush_rewrite_rules();"' );

        WP_CLI::runcommand( 'plugin delete akismet hello' );

        WP_CLI::runcommand( "option update blogdescription 'Welcome to Cloud City'" );
        WP_CLI::runcommand( "option update timezone_string America/Phoenix" );
        WP_CLI::runcommand( "option update blog_privacy 0" );
        WP_CLI::runcommand( "option update blog_public 0" );
        WP_CLI::runcommand( "option update default_ping_status closed" );
        WP_CLI::runcommand( "option update image_default_link_type none" );

        WP_CLI::runcommand( "user meta set 1 wp_media_library_mode list" );
        WP_CLI::runcommand( "user meta set 1 show_welcome_panel 0" );
       
        $this->enable_dev_plugins();
        $this->wipe_themes();
    }

    protected function enable_dev_plugins() {
        WP_CLI::runcommand( 'plugin install ' . implode( ' ', self::$developer_plugins ). ' --activate --force' );
        WP_CLI::runcommand( 'plugin install https://github.com/0aveRyan/patterns/archive/master.zip --activate --force');
    }

    protected function disable_dev_plugins() {
        WP_CLI::runcommand( 'plugin delete ' . implode( ' ', self::$developer_plugins ). ' patterns --force' );
    }

    protected function wipe_themes() {
        WP_CLI::runcommand( 'theme activate twentynineteen' );
        WP_CLI::runcommand( 'theme delete twentyseventeen twentysixteen' );
    }

    protected function green_log( $message ) {
        $this->colorize_log( $message, '2', 'k' );
    }

    protected function red_log( $message ) {
        $this->colorize_log( $message, '1', 'W' );
    }
    
    protected function content_import() {
        /**
         * Using WordPress' wp_remote_get over WP-CLI b/c WordPress gotta be 'round to import anyways
         */
        $response = \wp_remote_get(
            'https://raw.githubusercontent.com/0aveRyan/wp-lando/master/blockdemos.WordPress.2019-07-06.xml',
            array(
                'timeout' => 15,
                'stream'  => true,
                'filename' => 'content.xml',
            )
        );
    }

    protected function colorize_log( $message = '', $bg_color = '', $text_color = '' ) {
        if ( ! empty( $bg_color ) ) {
            $bg_color = '%' . $bg_color;
        }
        if ( ! empty( $text_color ) ) {
            $text_color = '%' . $text_color;
        }
        
        $end = ( ! empty( $bg_color ) || ! empty( $text_color ) ) ? '%n' : '';

        WP_CLI::log( WP_CLI::colorize( $bg_color . $text_color . $message . $end ) );
    }
}
