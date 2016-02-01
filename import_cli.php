<?php
/**
 * Implements import command.
 */
class ImportUsers_Command extends WP_CLI_Command {

    /**
     * Imports a CSV file full of users.
     *
     * ## OPTIONS
     *
     * <file>
     * : The CSV file .
     *
     * --update
     * : Update existing users
     *
     * --nag
     * : Display the password nag to new users
     *
     * --welcome
     * : Send a welcome email to new users
     *
     * ## EXAMPLES
     *
     *     wp importUsers user.csv --welcome
     *
     * @synopsis <file> [--update] [--nag] [--welcome]
     */
    function __invoke( $args, $assoc_args ) {

        list( $filename ) = $args;
        $password_nag          = isset($assoc_args['nag']);
        $users_update          = isset($assoc_args['update']);
        $new_user_notification = isset($assoc_args['welcome']);

        if (file_exists($filename)) {
            $results = IS_IU_Import_Users::import_csv( $filename, array(
                'password_nag' => $password_nag,
                'new_user_notification' => $new_user_notification,
                'users_update' => $users_update
            ) );

            // No users imported?
            WP_CLI::line( count($results['user_ids'])." users imported");

            // Some users imported?
            foreach ($results['errors'] as $error ) {
                 WP_CLI::line( $error." not imported");
            }

        }
        else
            WP_CLI::line( "File does not exist: $filename");
    }
}

WP_CLI::add_command( 'importUsers', 'ImportUsers_Command' );