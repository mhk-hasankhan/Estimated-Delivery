<?php

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;

class VendorEstimatedDelivery extends Plugin
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Detail' => 'onProductDetailPage',
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Checkout' => 'onCheckoutPage',
        ];
    }

    public function install(InstallContext $context): void
    {
        $this->createDatabase();
        $this->createMenu();
    }

    public function uninstall(UninstallContext $context): void
    {
        if (!$context->keepUserData()) {
            $this->removeDatabase();
        }
    }

    public function activate(ActivateContext $context): void
    {
        $context->scheduleClearCache(ActivateContext::CACHE_LIST_DEFAULT);
    }

    public function deactivate(DeactivateContext $context): void
    {
        $context->scheduleClearCache(DeactivateContext::CACHE_LIST_DEFAULT);
    }

    public function update(UpdateContext $context): void
    {
        $context->scheduleClearCache(UpdateContext::CACHE_LIST_DEFAULT);
    }

    public function onProductDetailPage(\Enlight_Event_EventArgs $args): void
    {
        /** @var \Shopware_Controllers_Frontend_Detail $controller */
        $controller = $args->getSubject();
        $view = $controller->View();

        $helper = $this->getDeliveryHelper();
        if (!$helper->isEnabled()) {
            return;
        }

        $view->addTemplateDir($this->getPath() . '/Views');
        $view->assign('estimatedDelivery', $helper->getDeliveryData());
    }

    public function onCheckoutPage(\Enlight_Event_EventArgs $args): void
    {
        /** @var \Shopware_Controllers_Frontend_Checkout $controller */
        $controller = $args->getSubject();
        $view = $controller->View();

        $helper = $this->getDeliveryHelper();
        if (!$helper->isEnabled()) {
            return;
        }

        $view->addTemplateDir($this->getPath() . '/Views');
        $view->assign('estimatedDelivery', $helper->getDeliveryData());
    }

    private function getDeliveryHelper(): \VendorEstimatedDelivery\Components\DeliveryHelper
    {
        return new \VendorEstimatedDelivery\Components\DeliveryHelper(
            Shopware()->Config(),
            Shopware()->Container()->get('shopware_plugininstaller.plugin_manager')
        );
    }

    private function createDatabase(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS `s_plugin_estimated_delivery_config` (
                `id`                INT(11) NOT NULL AUTO_INCREMENT,
                `shop_id`           INT(11) NOT NULL DEFAULT 1,
                `processing_days`   INT(11) NOT NULL DEFAULT 1,
                `exclude_weekends`  TINYINT(1) NOT NULL DEFAULT 1,
                `exclude_holidays`  TEXT NULL,
                `cutoff_hour`       INT(11) NOT NULL DEFAULT 14,
                `label_text`        VARCHAR(255) NOT NULL DEFAULT 'Estimated delivery',
                `date_format`       VARCHAR(50) NOT NULL DEFAULT 'D, d M',
                `active`            TINYINT(1) NOT NULL DEFAULT 1,
                PRIMARY KEY (`id`),
                UNIQUE KEY `shop_id` (`shop_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

            INSERT IGNORE INTO `s_plugin_estimated_delivery_config`
                (`shop_id`, `processing_days`, `exclude_weekends`, `cutoff_hour`, `label_text`, `date_format`, `active`)
            VALUES
                (1, 1, 1, 14, 'Estimated delivery', 'D, d M', 1);
        ";
        Shopware()->Db()->exec($sql);
    }

    private function removeDatabase(): void
    {
        Shopware()->Db()->exec('DROP TABLE IF EXISTS `s_plugin_estimated_delivery_config`');
    }

    private function createMenu(): void
    {
        // Menu entry added via plugin.xml
    }
}
