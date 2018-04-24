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
    protected $formField;

    /**
     * @var \Field
     */
    protected $config;

    /**
     * Engine constructor.
     * @param string $code
     */
    public function __construct($code)
    {
        $field = $this->loadFrom($code);
        if (empty($field)) {
            throw new \RuntimeException('Not Found Form.');
        }
        $this->formField = $field;
        $this->config = $field->getChild('mail');
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

        $ch = curl_init('https://api.chatwork.com/v2/rooms/'.$room_id.'/messages');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($option));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * @param string $code
     * @return bool|Field
     */
    protected function loadFrom($code)
    {
        $DB = DB::singleton(dsn());
        $SQL = SQL::newSelect('form');
        $SQL->addWhereOpr('form_code', $code);
        $row = $DB->query($SQL->get(dsn()), 'row');

        if (!$row) {
            return false;
        }
        $Form = new Field();
        $Form->set('code', $row['form_code']);
        $Form->set('name', $row['form_name']);
        $Form->set('scope', $row['form_scope']);
        $Form->set('log', $row['form_log']);
        $Form->overload(unserialize($row['form_data']), true);

        return $Form;
    }
}
