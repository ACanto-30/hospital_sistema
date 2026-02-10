<?php
declare(strict_types=1);

namespace Payments\Policy;

use Authorization\IdentityInterface;
use Payments\Model\Entity\Payment;

class PaymentPolicy
{
    /**
     * Check if $user can see the receipt of $payment
     *
     * @param \Authorization\IdentityInterface $user The user.
     * @param \Payments\Model\Entity\Payment $payment The payment.
     * @return bool
     */
    public function canSeeReceipt(IdentityInterface $user, Payment $payment)
    {
        // El administrador y el cajero pueden ver cualquier recibo
        $role = $user->role_name ?? 'NOT_SET';

        \Cake\Log\Log::debug("[PaymentPolicy] canSeeReceipt - User: " . $user->getIdentifier() . " - Role: " . $role);

        if (in_array($role, ['Administrador', 'Cajero'])) {
            return true;
        }

        // El asociado solo puede ver SU propio recibo
        return (int) $user->getIdentifier() === (int) ($payment->associate->user_id ?? 0);
    }

    /**
     * Por defecto, el resto de acciones básicas si se requieren
     */
    public function canEdit(IdentityInterface $user, Payment $payment)
    {
        // Usar la misma lógica que canSeeReceipt para obtener el rol
        $role = $user->role_name ?? 'NOT_SET';
        
        \Cake\Log\Log::debug("[PaymentPolicy] canEdit - User: " . $user->getIdentifier() . " - Role: " . $role);
        
        return in_array($role, ['Administrador', 'Cajero']);
    }

    public function canProcessPayment(IdentityInterface $user, Payment $payment)
    {
        // Usar la misma lógica que canSeeReceipt para obtener el rol
        $role = $user->role_name ?? 'NOT_SET';
        
        \Cake\Log\Log::debug("[PaymentPolicy] canProcessPayment - User: " . $user->getIdentifier() . " - Role: " . $role);
        
        $allowed = in_array($role, ['Administrador', 'Cajero']);
        
        if (!$allowed) {
            \Cake\Log\Log::warning("[PaymentPolicy] canProcessPayment - Acceso denegado para usuario " . $user->getIdentifier() . " con rol: " . $role);
        }
        
        return $allowed;
    }
}
