<?php
class ControllerExtensionAnalyticsPsGoogleAnalytics extends Controller
{
    /**
     * @var string The support email address.
     */
    const EXTENSION_EMAIL = 'support@playfulsparkle.com';

    /**
     * @var string The documentation URL for the extension.
     */
    const EXTENSION_DOC = 'https://github.com/playfulsparkle/oc3_google_analytics.git';

    private $error = array();

    public function index()
    {
        $this->load->language('extension/analytics/ps_google_analytics');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->request->post['analytics_ps_google_analytics_google_tag_id'] = strtoupper($this->request->post['analytics_ps_google_analytics_google_tag_id']);

            $this->model_setting_setting->editSetting('analytics_ps_google_analytics', $this->request->post, $this->request->get['store_id']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics', true));
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['google_tag_id'])) {
            $data['error_google_tag_id'] = $this->error['google_tag_id'];
        } else {
            $data['error_google_tag_id'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/analytics/ps_google_analytics', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true)
        );

        $data['action'] = $this->url->link('extension/analytics/ps_google_analytics', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $this->request->get['store_id'], true);

        $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=analytics', true);

        if (isset($this->request->post['analytics_ps_google_analytics_status'])) {
            $data['analytics_ps_google_analytics_status'] = (bool) $this->request->post['analytics_ps_google_analytics_status'];
        } else {
            $data['analytics_ps_google_analytics_status'] = (bool) $this->model_setting_setting->getSettingValue('analytics_ps_google_analytics_status', $this->request->get['store_id']);
        }

        if (isset($this->request->post['analytics_ps_google_analytics_debug_mode'])) {
            $data['analytics_ps_google_analytics_debug_mode'] = (bool) $this->request->post['analytics_ps_google_analytics_debug_mode'];
        } else {
            $data['analytics_ps_google_analytics_debug_mode'] = (bool) $this->model_setting_setting->getSettingValue('analytics_ps_google_analytics_debug_mode', $this->request->get['store_id']);
        }

        if (isset($this->request->post['analytics_ps_google_analytics_google_tag_id'])) {
            $data['analytics_ps_google_analytics_google_tag_id'] = (string) $this->request->post['analytics_ps_google_analytics_google_tag_id'];
        } else {
            $data['analytics_ps_google_analytics_google_tag_id'] = (string) $this->model_setting_setting->getSettingValue('analytics_ps_google_analytics_google_tag_id', $this->request->get['store_id']);
        }

        $data['text_contact'] = sprintf($this->language->get('text_contact'), self::EXTENSION_EMAIL, self::EXTENSION_EMAIL, self::EXTENSION_DOC);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/analytics/ps_google_analytics', $data));
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/analytics/ps_google_analytics')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            if (empty($this->request->post['analytics_ps_google_analytics_google_tag_id'])) {
                $this->error['google_tag_id'] = $this->language->get('error_google_tag_id');
            } elseif (preg_match('/^G-[A-Z0-9]{10}$/', strtoupper($this->request->post['analytics_ps_google_analytics_google_tag_id'])) !== 1) {
                $this->error['google_tag_id'] = $this->language->get('error_google_tag_id_invalid');
            }
        }

        return !$this->error;
    }

    public function install()
    {
        $this->load->model('setting/setting');

        $data = array(
            'analytics_ps_google_analytics_status' => 0,
            'analytics_ps_google_analytics_debug_mode' => 0,
            'analytics_ps_google_analytics_google_tag_id' => '',
        );

        $this->model_setting_setting->editSetting('analytics_ps_google_analytics', $data);
    }

    public function uninstall()
    {

    }
}
