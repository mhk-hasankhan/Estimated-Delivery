<?php

class Shopware_Controllers_Backend_EstimatedDeliveryConfig extends Shopware_Controllers_Backend_Application
{
    protected $model = null;

    public function loadAction(): void
    {
        try {
            $row = Shopware()->Db()->fetchRow(
                'SELECT * FROM `s_plugin_estimated_delivery_config` WHERE `shop_id` = 1 LIMIT 1'
            );

            $this->View()->assign([
                'success' => true,
                'data'    => $row ?: [],
            ]);
        } catch (\Exception $e) {
            $this->View()->assign(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function saveAction(): void
    {
        $request = $this->Request();

        $data = [
            'active'           => (int) $request->getPost('active', 1),
            'processing_days'  => (int) $request->getPost('processing_days', 1),
            'exclude_weekends' => (int) $request->getPost('exclude_weekends', 1),
            'cutoff_hour'      => (int) $request->getPost('cutoff_hour', 14),
            'label_text'       => $this->sanitize($request->getPost('label_text', 'Estimated delivery')),
            'date_format'      => $this->sanitize($request->getPost('date_format', 'D, d M')),
            'exclude_holidays' => $this->sanitizeHolidays($request->getPost('exclude_holidays', '')),
            'shop_id'          => 1,
        ];

        try {
            $existing = Shopware()->Db()->fetchOne(
                'SELECT id FROM `s_plugin_estimated_delivery_config` WHERE `shop_id` = 1'
            );

            if ($existing) {
                Shopware()->Db()->update('s_plugin_estimated_delivery_config', $data, 'shop_id = 1');
            } else {
                Shopware()->Db()->insert('s_plugin_estimated_delivery_config', $data);
            }

            $this->View()->assign(['success' => true]);
        } catch (\Exception $e) {
            $this->View()->assign(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function sanitize(string $value): string
    {
        return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
    }

    private function sanitizeHolidays(string $raw): string
    {
        $dates = array_filter(
            array_map('trim', explode(',', $raw)),
            static fn(string $d) => preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)
        );
        return implode(', ', $dates);
    }
}
