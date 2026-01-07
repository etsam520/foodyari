<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Define all permissions based on admin routes
        $permissions = [
            // Dashboard
            'dashboard.view',
            
            // Profile Management
            'profile.view',
            'profile.edit',
            'profile.update',
            
            // Restaurant Management
            'restaurant.add',
            'restaurant.store',
            'restaurant.edit',
            'restaurant.update',
            'restaurant.list',
            'restaurant.view',
            'restaurant.access',
            'restaurant.sort',
            'restaurant.get-restaurants',
            'restaurant.get_addons',
            'restaurant.get_menus',
            
            // Owner Management
            'owner.list',
            'owner.update',
            'owner.edit',
            
            // Order Management
            'order.list',
            'order.search',
            'order.add-to-cart',
            'order.remove-from-cart',
            'order.update',
            'order.edit',
            'order.details',
            'order.top-orders',
            'order.order-status-update',
            'order.quick-view',
            'order.quick-view-cart-item',
            'order.generate-invoice',
            'order.generate-KOT',
            'order.add-payment-ref-code',
            'order.dm_assign_manually',
            
            // Category Management
            'category.add',
            'category.store',
            'category.edit',
            'category.update',
            'category.status',
            'category.get_categories',
            
            // Addon Management
            'addon.add',
            'addon.store',
            'addon.edit',
            'addon.destroy',
            'addon.update',
            'addon.status',
            'addon.view',
            'addon.get_addons',
            
            // Food Management
            'food.add',
            'food.store',
            'food.edit',
            'food.list',
            'food.getFoodZoneWise',
            'food.update',
            'food.status',
            'food.delete',
            'food.get-submenu-option',
            'food.reqeusts',
            'food.reqeusts.form',
            'food.reqeusts.form-submit',
            
            // Fund Management
            'fund.index',
            'fund.histories',
            
            // Document/KYC Management - Standard CRUD
            'doc.index',
            'doc.create',
            'doc.store',
            'doc.show',
            'doc.edit',
            'doc.update',
            'doc.destroy',
            // Legacy permissions for backward compatibility
            'doc.kyc',
            'doc.kyc-table',
            'doc.delete',
            
            // Join Request Management
            'joinas.restaurant',
            'joinas.restaurant-show',
            'joinas.restaurant-doc-update',
            'joinas.restaurant-doc-update-status',
            'joinas.restaurant-kyc-update-status',
            'joinas.restaurant-form-update-status',
            'joinas.restaurant-create',
            'joinas.deliveryman',
            'joinas.deliveryman-show',
            'joinas.deliveryman-doc-update',
            'joinas.deliveryman-doc-update-status',
            'joinas.deliveryman-kyc-update-status',
            'joinas.deliveryman-form-update-status',
            'joinas.deliveryman-create',
            
            // Mess Management
            'mess.add',
            'mess.edit',
            'mess.store',
            'mess.update',
            'mess.list',
            'mess.access',
            
            // Zone Management
            'zone.add',
            'zone.list',
            'zone.store',
            'zone.edit',
            'zone.update',
            'zone.status',
            'zone.set-order-zone',
            'zone.get-order-zone',
            
            // POS System
            'pos.index',
            'pos.get-foods',
            'pos.quick-view',
            'pos.quick-view-cart-item',
            'pos.get-food-item-details',
            'pos.add-to-cart',
            'pos.get-cart-items',
            'pos.delete-cart-item',
            'pos.customer-store',
            'pos.order',
            
            // Vehicle Management
            'vehicle.list',
            'vehicle.create',
            'vehicle.status',
            'vehicle.edit',
            'vehicle.store',
            'vehicle.update',
            'vehicle.delete',
            'vehicle.view',
            
            // Roles Management
            'roles.add',
            
            // Shift Management
            'shift.list',
            'shift.store',
            'shift.edit',
            'shift.update',
            'shift.delete',
            'shift.search',
            'shift.status',
            
            // Payment Management
            'payments.list',
            'payments.pay-form',
            'payments.pay-form-request',
            
            // Delivery Man Management
            'delivery-man.add',
            'delivery-man.store',
            'delivery-man.show',
            'delivery-man.list',
            'delivery-man.kyc',
            'delivery-man.preview',
            'delivery-man.status',
            'delivery-man.earning',
            'delivery-man.application',
            'delivery-man.edit',
            'delivery-man.update',
            'delivery-man.delete',
            'delivery-man.search',
            'delivery-man.get-deliverymen',
            'delivery-man.export-delivery-man',
            'delivery-man.pending',
            'delivery-man.denied',
            'delivery-man.update-fuel-rate',
            'delivery-man.add-fuel-balance',
            'delivery-man.reviews.list',
            'delivery-man.reviews.status',
            'delivery-man.incentive',
            'delivery-man.incentive-history',
            'delivery-man.update-incentive',
            'delivery-man.bonus',
            'delivery-man.message-view',
            'delivery-man.message-list',
            'delivery-man.message-list-search',
            'delivery-man.restaurantfilter',
            
            // Employee Management
            'employee.add-new',
            'employee.list',
            'employee.edit',
            'employee.update',
            'employee.delete',
            'employee.search',
            'employee.export-employee',
            
            // Subscription Management
            'subscription.list',
            'subscription.create',
            'subscription.subscription_store',
            'subscription.package_details',
            
            // Banner Management
            'banner.add-new',
            'banner.get-partials',
            'banner.get-partials-saved',
            'banner.store',
            'banner.edit',
            'banner.update',
            'banner.status',
            'banner.delete',
            'banner.search',
            
            // Marquee Management
            'marquee.add-new',
            'marquee.store',
            'marquee.edit',
            'marquee.update',
            'marquee.status',
            'marquee.delete',
            'marquee.search',
            
            // Coupon Management
            'coupon.add-new',
            'coupon.store',
            'coupon.update',
            'coupon.status',
            'coupon.delete',
            'coupon.search',
            
            // Customer Management
            'customer.add',
            'customer.list',
            'customer.view',
            'customer.getdata',
            'customer.access',
            'customer.status',
            'customer.add-wallet-fund',
            'customer.payments.history',
            'customer.rating',
            'customer.clear-stats-cache',
            
            // Review Management
            'review.list',
            'review.view',
            'review.edit',
            'review.delete',
            
            // Notification Management
            'notification.add-new',
            'notification.store',
            'notification.edit',
            'notification.update',
            'notification.status',
            'notification.delete',
            'notification.clear-data',
            'notification.export',
            'notification.targetClient',
            
            // Report Management
            'report.order',
            'report.product',
            'report.tax',
            
            // Earning Management
            'earning.deliveryman',
            'earning.dm_save_cash_txn',
            'earning.dm_save_wallet_txn',
            'earning.dm-cash-in-hand',
            'earning.dm-wallet-balance',
            'earning.payouts',
            'earning.create-payout',
            'earning.update-payout-status',
            'earning.payouts-by-admin',
            
            // Business Settings
            'business-settings.business-setup',
            'business-settings.config-setup',
            'business-settings.update-setup',
            'business-settings.email-setup',
            'business-settings.referral-settings',
            'business-settings.about-us',
            'business-settings.privacy-policy',
            'business-settings.terms-and-conditions',
            'business-settings.refund-policy',
            'business-settings.refund-policy-status',
            'business-settings.shipping-policy',
            'business-settings.shipping-policy-status',
            'business-settings.cancellation-policy',
            'business-settings.cancellation-policy-status',
            
            // Administration
            'administration.roles.permissions',
            'administration.assign.role.to.user',
            'administration.assign.permission.to.role',
            
            // Chat System
            'chat.index',
            'chat.conversation',
            'chat.get-messages',
            'chat.send-message',
            'chat.mark-read',
            'chat.customers',
            'chat.start',
            'chat.delete-message',
            'chat.clear-conversation',
            
            // Refund System
            'refund.index',
            'refund.show',
            'refund.process',
            'refund.create',
            'refund.update-deduction',
            'refund.reasons',
            'refund.reasons.store',
            'refund.reasons.toggle',
            'refund.reasons.delete',
            
            // Referral System
            'referral.index',
            'referral.store',
            'referral.configurations',
            'referral.toggle',
            'referral.delete',
            'referral.statistics',
            'referral.usage-statistics',
            'referral.usage-details',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Create default roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'admin']);
        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'admin']);

        // Assign all permissions to Super Admin
        $superAdminRole->syncPermissions(Permission::all());

        // Assign basic permissions to Admin
        $adminPermissions = [
            'dashboard.view',
            'profile.view',
            'profile.edit',
            'profile.update',
            'order.list',
            'order.details',
            'order.quick-view',
            'customer.list',
            'customer.view',
            'restaurant.list',
            'restaurant.view',
            'food.list',
            'report.order',
            'report.product',
        ];
        $adminRole->syncPermissions(Permission::whereIn('name', $adminPermissions)->get());

        // Assign limited permissions to Manager
        $managerPermissions = [
            'dashboard.view',
            'profile.view',
            'profile.edit',
            'order.list',
            'order.details',
            'customer.list',
            'restaurant.list',
            'food.list',
        ];
        $managerRole->syncPermissions(Permission::whereIn('name', $managerPermissions)->get());

        // Assign basic permissions to Editor
        $editorPermissions = [
            'dashboard.view',
            'profile.view',
            'food.add',
            'food.store',
            'food.edit',
            'food.update',
            'category.add',
            'category.store',
            'category.edit',
            'category.update',
        ];
        $editorRole->syncPermissions(Permission::whereIn('name', $editorPermissions)->get());

        $this->command->info('Permissions and roles created successfully!');
    }
}
