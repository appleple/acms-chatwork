<?php

namespace Acms\Plugins\ChatWork;

class Hook
{
    /**
     * POSTモジュール処理前
     * $thisModuleのプロパティを参照・操作するなど
     *
     * @param \ACMS_POST $thisModule
     */
    public function afterPostFire($thisModule)
    {
        $moduleName = get_class($thisModule);
        $formCode = $thisModule->Post->get('id');

        if ($moduleName !== 'ACMS_POST_Form_Submit') {
            return;
        }
        if (!$thisModule->Post->isValidAll()) {
            return;
        }
        if (empty($formCode)) {
            return;
        }
        try {
            $engine = new Engine($formCode);
            $engine->send();
        } catch (\Exception $e) {
            userErrorLog('ACMS Warning: ChatWork plugin, ' . $e->getMessage());
        }
    }
}
