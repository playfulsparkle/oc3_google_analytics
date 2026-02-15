<?php
class ControllerExtensionAnalyticsPsGoogleAnalytics extends Controller
{
    public function index()
    {
        if (!$this->config->get('analytics_ps_google_analytics_status')) {
            return '';
        }

        $google_tag_id = $this->config->get('analytics_ps_google_analytics_google_tag_id');

        $gtag_config = array();

        if ($this->config->get('analytics_ps_google_analytics_debug_mode')) {
            $gtag_config['debug_mode'] = true;
        }

        if ($this->request->server['HTTPS']) {
            $gtag_config['cookie_flags'] = 'SameSite=None;Secure';
        }

        $gtag_config_json = $gtag_config ? json_encode($gtag_config) : null;

        $html = '<!-- Google tag (gtag.js) -->' . PHP_EOL;
        $html .= '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $google_tag_id . '"></script>' . PHP_EOL;
        $html .= "<script>" . PHP_EOL;
        $html .= "window.dataLayer = window.dataLayer || [];" . PHP_EOL;
        $html .= "function gtag() { dataLayer.push(arguments); }" . PHP_EOL . PHP_EOL;
        $html .= "gtag('js', new Date());" . PHP_EOL;

        if ($gtag_config_json) {
            $html .= "gtag('config', '" . $google_tag_id . "', " . $gtag_config_json . ");" . PHP_EOL;
        } else {
            $html .= "gtag('config', '" . $google_tag_id . "');" . PHP_EOL;
        }

        $html .= "</script>" . PHP_EOL;

        return $html;
    }
}
