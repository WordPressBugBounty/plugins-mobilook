<?php
namespace Pagup\Mobilook\Controllers;

use Pagup\Mobilook\Core\Option;

class FrontendController
{
    public function __construct() {

        add_filter('wp_head', array( &$this, 'viewport' ), 10);
        
    }

    public function viewport()
    {
        if (mobilook_fs()->can_use_premium_code__premium_only() && Option::check('viewport') && Option::get('viewport') === 'allow')
        {
            echo "<!-- Viewport Optimization added by Mobilook -->\n".'<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=0.1, maximum-scale=10.0">'."\n";
        }
      
    }
}

$FrontendController = new FrontendController;