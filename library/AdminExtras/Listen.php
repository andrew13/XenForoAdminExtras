<?php

class AdminExtras_Listen
{

    static $addExternal = false;

    /**
     * Listen for post render effects so we can append externals to the header in the PAGE_CONTAINER template
     *
     * @param   string                      $templateName
     * @param   string                      $content
     * @param   array                       array
     * @param   XenForo_Template_Abstract   $template
     *
     * @return  void
     */
    public static function template_post_render($templateName, &$content, array &$containerData, XenForo_Template_Abstract $template)
    {
        if (self::$addExternal AND ( $templateName == 'PAGE_CONTAINER' OR $templateName == 'LOGIN_PAGE') )
        {
            $params      = $template->getParams();
            $baseUrl     = $params['requestPaths']['fullBasePath'];

            $options     = XenForo_Application::get('options');
            $version     = $options->adminExtrasJsVersion;

            $append      = '<link rel="stylesheet" href="'.$baseUrl.'admin.php?_css/&css=EXTRA&v='.$version.'">' . "\n";

            $append      .= '<script src="'.$baseUrl.'js/adminExtras/extra.js?v='.$version.'"></script>' . "\n";

            $content     = str_replace('</head>', $append . '</head>', $content);

            self::$addExternal = false;
        }
    }

    /**
     * Listen for controller pre dispatch events so we can detect when the externals are required
     *
     * @param   XenForo_Controller  $controller
     * @param   string              $action
     *
     * @return  void
     */
    public static function controller_pre_dispatch(XenForo_Controller $controller, $action)
    {
        if ( ! $controller instanceof XenForo_ControllerAdmin_Abstract )
        {
            return;
        }

        self::$addExternal = true;
    }

}