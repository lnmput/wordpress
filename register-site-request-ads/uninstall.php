<?php
if( !defined( 'WP_UNINSTALL_PLUGIN' ) ){
    exit();
}
//删除数据库

delete_option('is_register');
delete_option('registerURL');
delete_option('modifyURL');
delete_option('requestAdURL');
$file=wp_upload_dir();
$filename=$file['path'].'/log.txt';
@unlink($filename);