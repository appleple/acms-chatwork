<?php

namespace Acms\Plugins\ChatWork;

use DB;
use SQL;
use Field;
use Field_Validation;

class Engine
{
    /**
     * @var \Field
     */
    protected $config;

    /**
     * @var string
     */
    protected $endpoint = 'https://api.chatwork.com/v2/rooms/%s/messages';

    /**
     * Engine constructor.
     * @param string $code
     * @param \ACMS_POST
     */
    public function __construct($code, module)
    {
        $info = $module->loadForm($code);
        if (empty($info)) {
            throw new \RuntimeException('Not Found Form.');
        }
        $this->config = $info['data']->getChild('mail');
    }

    /**
     * Send
     */
    public function send()
    {
        $accessToken = $this->config->get('chatwork_form_token');
        $message = $this->config->get('chatwork_form_message');
        $room_id = $this->config->get('chatwork_form_room_id');
        $tpl = '<!-- BEGIN_MODULE Form --><!-- BEGIN step#result -->'.$message.'<!-- END step#result --><!-- END_MODULE Form -->';
        $text = build(setGlobalVars($tpl), Field_Validation::singleton('post'));
        $headers = array(
            'X-ChatWorkToken: '.$accessToken
        );
        $option = array(
            'body' => $text
        );

        $ch = curl_init(sprintf($this->endpoint, $room_id));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($option));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ( empty($response) || $status !== 200  ) {
            throw new \RuntimeException("$status: $response");
        }
    }
}
