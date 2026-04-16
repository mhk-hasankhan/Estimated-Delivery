<?php

namespace VendorEstimatedDelivery\Components;

class DeliveryHelper
{
    /** @var array */
    private array $config;

    public function __construct(
        private readonly \Shopware_Components_Config $shopwareConfig,
        private readonly mixed $pluginManager
    ) {
        $this->config = $this->loadConfig();
    }

    public function isEnabled(): bool
    {
        return (bool) ($this->config['active'] ?? false);
    }

    /**
     * Returns delivery window data for template use.
     *
     * @return array{
     *   label: string,
     *   from: string,
     *   to: string,
     *   from_timestamp: int,
     *   to_timestamp: int,
     *   single_day: bool
     * }
     */
    public function getDeliveryData(): array
    {
        $processingDays  = (int) ($this->config['processing_days'] ?? 1);
        $excludeWeekends = (bool) ($this->config['exclude_weekends'] ?? true);
        $cutoffHour      = (int) ($this->config['cutoff_hour'] ?? 14);
        $holidays        = $this->parseHolidays($this->config['exclude_holidays'] ?? '');
        $label           = $this->config['label_text'] ?? 'Estimated delivery';
        $dateFormat      = $this->config['date_format'] ?? 'D, d M';

        $now = new \DateTime('now');

        // Orders placed after cutoff add an extra processing day
        if ((int) $now->format('H') >= $cutoffHour) {
            $processingDays++;
        }

        $fromDate = $this->addBusinessDays($now, $processingDays, $excludeWeekends, $holidays);
        // Delivery window: from + 2 business days
        $toDate   = $this->addBusinessDays(clone $fromDate, 2, $excludeWeekends, $holidays);

        $fromFormatted = $fromDate->format($dateFormat);
        $toFormatted   = $toDate->format($dateFormat);

        return [
            'label'          => $label,
            'from'           => $fromFormatted,
            'to'             => $toFormatted,
            'from_timestamp' => $fromDate->getTimestamp(),
            'to_timestamp'   => $toDate->getTimestamp(),
            'single_day'     => $fromFormatted === $toFormatted,
        ];
    }

    private function addBusinessDays(
        \DateTime $date,
        int $days,
        bool $excludeWeekends,
        array $holidays
    ): \DateTime {
        $result = clone $date;
        $added  = 0;

        while ($added < $days) {
            $result->modify('+1 day');

            if ($excludeWeekends && in_array((int) $result->format('N'), [6, 7], true)) {
                continue;
            }

            if (in_array($result->format('Y-m-d'), $holidays, true)) {
                continue;
            }

            $added++;
        }

        return $result;
    }

    private function parseHolidays(string $raw): array
    {
        if (empty(trim($raw))) {
            return [];
        }

        return array_filter(
            array_map('trim', explode(',', $raw)),
            static fn(string $d) => preg_match('/^\d{4}-\d{2}-\d{2}$/', $d)
        );
    }

    private function loadConfig(): array
    {
        try {
            $row = Shopware()->Db()->fetchRow(
                'SELECT * FROM `s_plugin_estimated_delivery_config` WHERE `shop_id` = ? LIMIT 1',
                [Shopware()->Shop()->getId()]
            );

            return $row ?: $this->defaults();
        } catch (\Exception $e) {
            return $this->defaults();
        }
    }

    private function defaults(): array
    {
        return [
            'active'           => 1,
            'processing_days'  => 1,
            'exclude_weekends' => 1,
            'cutoff_hour'      => 14,
            'label_text'       => 'Estimated delivery',
            'date_format'      => 'D, d M',
            'exclude_holidays' => '',
        ];
    }
}
