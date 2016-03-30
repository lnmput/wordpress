<?php
/**
 * main class
 */
class Main
{
    //默认站点注册URL,可在后台修改
    private static $registerURL="http://www.adapi.dev/api/addsite";
    //广告请求URL,可在后台修改
    private static $requestAdURL="http://www.adapi.dev/api/requestad";
    //站点更改url,可在后台修改
    private static $modifyURL="http://www.adapi.dev/api/updatesite";
    //站点名字
    private $siteName;
    //站点域名
    private $siteDomain;

    public function __construct()
    {
        $this->siteName=get_bloginfo('name');
        $this->siteDomain=home_url();
        //加载bootstrap.css
        wp_enqueue_style( 'yz_css',plugins_url('assets/css/bootstrap.min.css', __FILE__) , array() );
        //加载web.js 和 jquery
        wp_enqueue_script( 'yz_web', plugins_url('assets/js/web.js', __FILE__), array('jquery') );
        //在数据库中设置url
        add_action( 'init', array($this,'setDbUrl'),5);
        //启用ajax
        wp_localize_script( 'yz_web', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        //未登录链接修改
        add_action( 'wp_ajax_nopriv_yz_modify_url' , array( $this, 'yz_modify_url') );
        //以登录链接修改
         add_action( 'wp_ajax_yz_modify_url' , array( $this, 'yz_modify_url') );
        //设置菜单
        add_action( 'admin_menu', array($this,'yz_create_menu'));

        //判断站点是否已经注册
        if(get_option('is_register')!='yes'){
            //add_action( "wp_footer", array( $this,'yz_load_button'));
            //注册站点
            add_action( 'init', array($this,'yz_register_site'));
        }

        //请求广告
        add_filter( 'the_content',  array($this,'display_ads') );
    }


    /*
     *
     * 站点注册
     */
    public function yz_register_site()
    {
       //add_option('test000','yes');
        $registerURL=get_option('registerURL');
        $data='{"siteName":"'.$this->siteName.'" , "siteDomain":"'.$this->siteDomain.'" }';
        $info=$this->curl_post($registerURL,$data);
        $messageInfo= json_decode($info,'JSON_FORCE_OBJECT');
        if($messageInfo['status']==200){
            add_option('is_register','yes');
        }
        //echo $info;
       // wp_die();
    }

    /*
     * 给每篇文章添加广告
     */
    function display_ads( $content ) {
        //请求广告
        $requestAdURL=get_option('requestAdURL');
        $adData='{ "siteDomain":"'.$this->siteDomain.'" }';
        $result= $this->curl_post($requestAdURL,$adData);
        $message= json_decode($result,'JSON_FORCE_OBJECT');
        if($message['status']==200){
            //给每篇文章添加广告
            $content = $message['message'].$content ;
            return $content;
        }else{
            $content = $message['message'].$content ;
            return $content;
        }
    }

    /*
     * curl post
     */
    public function curl_post($url, $data)
    {
        $UserAgent="Mozilla/5.0 (Win94; I)";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent);
        $data = curl_exec($ch);
        curl_close($ch);
        if ($data) {
            return $data;
        } else {
            return $data;
        }
    }

    /*
     *
     * 创建菜单
     */
    public function yz_create_menu()
    {
        add_menu_page(
            '站点广告',
            '站点广告',
            'manage_options',
            __FILE__,
            array($this,'yz_settings_page'),//要显示菜单对应的页面内容所调用的函数
            plugins_url( 'register-site-request-ads/assets/images/ad.png', __FILE )
        );

    }

    /*
     *
     * 初始化请求链接
     */
     public function setDbUrl()
     {
        if( ! get_option('registerURL')){

            add_option('registerURL',self::$registerURL);

        }elseif(! get_option('modifyURL')){

            add_option('modifyURL',self::$modifyURL);

        }elseif(! get_option('requestAdURL')){

            add_option('requestAdURL',self::$requestAdURL);
        }
     }




    /*
     * 后台设置页面
     */
    public function yz_settings_page()
    {
        $registerURL=get_option('registerURL');
        $modifyURL= get_option('modifyURL');
        $requestAdURL=get_option('requestAdURL');
        $page = include "assets/html/admin.html";
        echo $page;
    }

    /*
     * 修改链接
     */
    public function yz_modify_url()
    {
        $registerURL=$_POST['register_url'];
        $modifyURL=$_POST['modify_url'];
        $requestAdURL=$_POST['requestad_url'];
        update_option('registerURL',$registerURL);
        update_option('modifyURL',$modifyURL);
        update_option('requestAdURL',$requestAdURL);
        echo "200";
        wp_die();
    }


    /*
     * 已经废弃
     */
    public function yz_load_button()
    {
        if(is_home()){
            $button = include "assets/html/registerbutton.html";
            echo $button;
        }
    }

}